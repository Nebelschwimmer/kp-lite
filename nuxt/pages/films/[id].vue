<template>
  <div>
    <Head>
      <Title>{{ definePageTitle(film?.name || "") }}</Title>
      <Meta name="description" :content="film?.description" />
    </Head>
    <main>
      <DetailCard
        :page-name="film?.name + ' (' + film?.releaseYear + ')' || ''"
        :loading="loading"
        :display-avatar="false"
        :cover="film?.cover || ''"
        :is-auth="isAuthenticated"
        left-drawer
        :notification="!isAuthenticated"
        @drawer:toggle="showLeftDrawer = !showLeftDrawer"
      >
        <template #sidebar>
          <client-only>
            <v-navigation-drawer width="400">
              <FilmDrawerContent
                :poster="film?.poster || ''"
                :general-info="generalInfo"
                :starring="starring"
                :team="team"
              />
            </v-navigation-drawer>
          </client-only>
        </template>
        <template #notification>
          <NotAuthWarning v-if="!isAuthenticated" />
        </template>
        <template #menu>
          <FilmDetailMenu
            :is-authenticated="isAuthenticated"
            @choose:cover="chooseCover"
            @choose:poster="choosePoster"
            @edit:general="handleGeneralInfoEdit"
            @edit:description="handleEditDescription"
            @edit:gallery="openGalleryEditor"
            @delete:film="showDeleteWarning = true"
          />
        </template>
        <template #text>
          <main>
            <FilmDrawerContent
              v-if="$vuetify.display.smAndDown"
              :poster="film?.poster || ''"
              :general-info="generalInfo"
              :starring="starring"
              :team="team"
            />
            <v-expansion-panels
              v-model="mainAccordion"
              variant="accordion"
              bg-color="transparent"
              multiple
              border
            >
              <v-expansion-panel
                id="gallery"
                value="gallery"
                tag="section"
                :title="$t('pages.films.gallery')"
              >
                <v-expansion-panel-text>
                  <GalleryViewer
                    :slider-arr="sliderGalleryArr || []"
                    :disabled="!isAuthenticated"
                    :gallery="film?.gallery || []"
                    :entity-name="film?.name || ''"
                    :loading="loading"
                    :with-avatar="false"
                    @poster:set="handleChangePoster"
                    @editor:open="openGalleryEditor"
                    @cover:set="handleChangeCover"
                    @delete:img="handleDeleteImg"
                  />
                </v-expansion-panel-text>
              </v-expansion-panel>
              <v-expansion-panel
                id="description"
                :title="$t('pages.films.description')"
                tag="section"
                class="content-item"
                value="description"
              >
                <v-expansion-panel-text>
                  <IndentedEditableText
                    :edit-mode="editDescriptionMode"
                    :messages="$t('pages.films.edit_description')"
                    :text="film?.description || ''"
                    @sumbit:edit="submitDescriptionEdit"
                  />
                </v-expansion-panel-text>
              </v-expansion-panel>
              <v-expansion-panel
                id="rating"
                tag="section"
                value="rating"
                class="content-item"
                :title="$t('pages.films.rating')"
              >
                <v-expansion-panel-text>
                  <div class="d-flex flex-column justify-center ga-1">
                    <FilmAssessments
                      :current-rating="film?.rating || ''"
                      :assessments="film?.assessments || []"
                      :is-assessing="isAssessing"
                      :is-authenticated="isAuthenticated"
                      :rating="rating"
                      :comment="comment"
                      @assession:submit="submitAssessment"
                      @assession:enable="isAssessing = true"
                      @assession:cancel="cancelAssessment"
                      @comment:update="comment = $event"
                      @rating:update="rating = $event"
                    />
                  </div>
                </v-expansion-panel-text>
              </v-expansion-panel>
            </v-expansion-panels>
          </main>
          <v-footer class="glassed">
            <v-spacer />
            <div class="d-flex align-center text-caption ga-1">
              <span>{{ $t("general.published_by") }}</span>
              <nuxt-link class="text-secondary">{{
                film?.publisherData ? film?.publisherData.name : ""
              }}</nuxt-link>
              {{ film?.createdAt || "" }}
            </div>
          </v-footer>
        </template>
      </DetailCard>
    </main>
    <ConfirmDialog
      v-model="showConfirmDialog"
      type="error"
      :text="$t('forms.film.gallery_item_delete_confirm')"
      :loading="loading"
      @confirm="handleGalleryItemsDelete"
      @cancel="showConfirmDialog = false"
    />
    <BaseDialog
      v-model:opened="generalInfoEdit"
      :max-width="800"
      :title="$t('pages.films.edit') + ' ' + film?.name"
      :loading="loading"
      @close="generalInfoEdit = false"
    >
      <template #text>
        <FilmForm
          :film-form="filmForm"
          :show-description="false"
          :genres="genres"
          :actors="actors"
          :directors="directors"
          :producers="producers"
          :writers="writers"
          :composers="composers"
          :is-valid="isFormValid"
          @validate="isFormValid = $event"
          @form:submit="sumbitEdit"
          @update:model-value="filmForm = $event"
        />
      </template>
    </BaseDialog>
    <BaseDialog
      v-model:opened="editGalleryMode"
      :loading="loading"
      :title="computedGalleryEditTitle"
      :max-width="1200"
      @close="editGalleryMode = false"
    >
      <template #text>
        <FilmGalleryEdit
          v-model:selected="selectedImagesIndices"
          :active-tab="activeTab"
          :film="film"
          :slider-arr="sliderGalleryArr || []"
          :upload-count="uploadCount"
          :edit-mode="editGalleryMode"
          :upload-disabled="uploadCount === 0"
          :remove-disabled="!film?.gallery.length"
          :card-height="GALLERY_CARD_HEIGHT"
          @cover:change="handleChangeCover"
          @poster:change="handleChangePoster"
          @update:selected="selectedImagesIndices = $event"
          @delete:selected="showConfirmDialog = true"
          @upload="handleGalleryUpload"
        />
      </template>
    </BaseDialog>
    <SuccessSnackbar
      v-model:show="showSnackbar"
      @close="showSnackbar = false"
    />
    <ConfirmDialog
      v-model="showDeleteWarning"
      :text="$t('general.entity_delete_warning')"
      @cancel="showDeleteWarning = false"
      @confirm="handleFilmDelete"
    />
  </div>
</template>

<script lang="ts" setup>
import { useFilmStore } from "~/stores/filmStore";
import { useAuthStore } from "~/stores/authStore";

import DetailCard from "~/components/Containment/Cards/DetailCard.vue";
import BaseDialog from "~/components/Dialogs/BaseDialog.vue";
import FilmGalleryEdit from "~/components/Gallery/FilmGalleryEdit.vue";
import ConfirmDialog from "~/components/Dialogs/ConfirmDialog.vue";
import FilmForm from "~/components/Forms/FilmForm.vue";
import IndentedEditableText from "~/components/Misc/IndentedEditableText.vue";
import GalleryViewer from "~/components/Gallery/GalleryViewer.vue";
import SuccessSnackbar from "~/components/Misc/SuccessSnackbar.vue";
import FilmAssessments from "~/components/FilmPartials/FilmAssessments.vue";
import FilmDrawerContent from "~/components/FilmPartials/FilmDrawerContent.vue";
import FilmDetailMenu from "~/components/FilmPartials/FilmDetailMenu.vue";
import NotAuthWarning from "~/components/Misc/NotAuthWarning.vue";


const GALLERY_CARD_HEIGHT: number = 200;

const localeRoute = useLocaleRoute();
const { locale, t } = useI18n();

const showDeleteWarning = ref<boolean>(false);
const editDescriptionMode = ref<boolean>(false);
const showConfirmDialog = ref<boolean>(false);
const editGalleryMode = ref<boolean>(false);
const showSnackbar = ref<boolean>(false);
const generalInfoEdit = ref<boolean>(false);
const isFormValid = ref<boolean>(true);
const isAssessing = ref<boolean>(false);
const showAssessDialog = ref<boolean>(false);
const showLeftDrawer = ref<boolean>(true);

const comment = ref<string>("");
const rating = ref<number>(0);
const activeTab = ref<number>(0);
const selectedImagesIndices = ref<number[]>([]);

const mainAccordion = ref<string[]>(["gallery", "rating", "description"]);

const { isAuthenticated } = storeToRefs(useAuthStore());
const {
  film,
  genres,
  filmForm,
  actors,
  composers,
  producers,
  writers,
  directors,
  loading,
  // similarGenreFilms
} = storeToRefs(useFilmStore());
const {
  editFilm,
  fetchFilmForm,
  fetchFilmById,
  fetchGenres,
  uploadGallery,
  clearFilmForm,
  deleteGalleryItems,
  assessFilmById,
  deleteFilm,
  fetchSpecialists,
  // fetchFilmsWithSimilarGenres,
  GALLERY_SIZE,
} = useFilmStore();

const imagesToDelete = computed(() => {
  return film.value?.gallery
    .filter((_: string, index: number): boolean =>
      selectedImagesIndices.value.includes(index)
    )
    .map((imageName: string): string => {
      const fileName = imageName ? imageName.split("/").at(-1) : "";

      return fileName ? fileName.split(".")[0] : "";
    });
}) as ComputedRef<string[]>;

const generalInfo = computed((): Detail[] => {
  const info = [
    {
      title: "forms.film.name",
      value: film.value?.name || "",
      icon: "mdi-movie",
      tooltip: film.value && film.value.name?.length > 60 ? true : false,
    },
    {
      title: "forms.film.slogan",
      value: film.value?.slogan || "",
      icon: "mdi-format-title",
      tooltip:
        film.value?.slogan && film.value.slogan?.length > 60 ? true : false,
    },
    {
      title: "forms.film.duration",
      value: film.value?.duration || "",
      icon: "mdi-timer",
    },
    {
      title: "forms.film.genres",
      value: film.value?.genreNames ? film.value?.genreNames.join(", ") : "",
      icon: "mdi-filmstrip",
      tooltip:
        film.value?.genreNames && film.value?.genreNames.join(", ").length > 120
          ? true
          : false,
    },
    {
      title: "forms.film.age",
      value: film.value?.age + "+" || "",
      icon: "mdi-account-supervisor",
    },
  ];

  return info as Detail[];
});

const starring = computed((): Detail[] => {
  return film.value
    ? film.value?.actorsData.map((person: FilmPerson): Detail => {
        return {
          title: "",
          value: person?.name || "",
          to: person?.id ? "/persons/" + person?.id : "",
          avatar: person.avatar || "",
        };
      })
    : [];
});

const team = computed((): Detail[] => {
  const teamMembersTitles = [
    "forms.film.director",
    "forms.film.writer",
    "forms.film.producer",
    "forms.film.composer",
    "forms.film.actors",
  ];
  return film.value
    ? film.value.teamData?.map((person: FilmPerson, index: number): Detail => {
        return {
          title: teamMembersTitles[index],
          value: person?.name || "",
          to: person?.id ? "/persons/" + person?.id : "",
          avatar: person.avatar || "",
        };
      })
    : [];
});

const computedGalleryEditTitle = computed((): string => {
  return t("pages.films.edit_gallery");
});
const fetchData = async (): Promise<void> => {
  const filmId = Number(useRoute().params.id);
  await Promise.allSettled([
    fetchGenres(locale.value),
    fetchFilmById(filmId, locale.value),
    fetchFilmForm(filmId, locale.value),
    fetchSpecialists(),
    // fetchFilmsWithSimilarGenres(filmId),
  ]);
};

const sliderGalleryArr = computed((): string[] => {
  const initialArr = Array.from({ length: GALLERY_SIZE }, (_, i) => i);

  return initialArr.map((item) => {
    return (film.value && film?.value.gallery[item]) || "";
  });
}) as ComputedRef<string[]>;

const uploadCount = computed(() => {
  return film.value?.gallery.length
    ? sliderGalleryArr.value.filter((item: string) => item === "").length
    : GALLERY_SIZE;
});

const handleFilmDelete = async () => {
  showDeleteWarning.value = false;
  const filmId = Number(useRoute().params.id);
  await deleteFilm(filmId);
  navigateTo(localeRoute("/films"));
};

const handleGeneralInfoEdit = () => {
  generalInfoEdit.value = true;
};

const openGalleryEditor = () => {
  editGalleryMode.value = true;
  activeTab.value = 1;
};
const cancelAssessment = () => {
  showAssessDialog.value = false;
  isAssessing.value = false;
  rating.value = 0;
  comment.value = "";
};

const submitAssessment = async () => {
  await assessFilmById(
    Number(useRoute().params.id),
    rating.value,
    comment.value,
    locale.value
  );
  await fetchData();
  await nextTick(() => {
    showSnackbar.value = true;
    cancelAssessment();
  });
};

const handleEditDescription = async () => {
  editDescriptionMode.value = true;
  if (mainAccordion.value.indexOf("description") === -1) {
    mainAccordion.value.push("description");
  }
  const descriptionPanelElement = document.getElementById("description");
  await nextTick(() => {
    if (descriptionPanelElement) {
      descriptionPanelElement.scrollIntoView({ behavior: "smooth" });
    }
  });
};

const sumbitEdit = async () => {
  await editFilm(locale.value);
  await fetchData();
  await nextTick(() => {
    showSnackbar.value = true;
    editDescriptionMode.value = !editDescriptionMode.value;
    generalInfoEdit.value = false;
  });
};

const handleGalleryItemsDelete = async () => {
  await deleteGalleryItems(imagesToDelete.value);
  await fetchData();
  await nextTick(() => {
    showSnackbar.value = true;
    showConfirmDialog.value = false;
    editGalleryMode.value = false;
  });
};

const handleDeleteImg = async (index: number) => {
  selectedImagesIndices.value = [index];
  await handleGalleryItemsDelete();
};

const chooseCover = (): void => {
  editGalleryMode.value = true;
  activeTab.value = 0;
};

const choosePoster = (): void => {
  editGalleryMode.value = true;
  activeTab.value = 1;
};

const handleGalleryUpload = async (files: File[]) => {
  const id = Number(useRoute().params.id);
  await uploadGallery(files, id);
  editGalleryMode.value = false;
  await fetchData();
  await nextTick(() => {
    showSnackbar.value = true;
  });
};

const handleChangeCover = async (index: number) => {
  filmForm.value.cover = film.value?.gallery[index - 1] || "";
  await editFilm(locale.value);
  editGalleryMode.value = false;
  await fetchData();
  await nextTick(() => {
    showSnackbar.value = true;
  });
};

const handleChangePoster = async (index: number) => {
  console.log(index);
  filmForm.value.poster = film.value?.gallery[index - 1] || "";
  await editFilm(locale.value);
  editGalleryMode.value = false;
  await fetchData();
  await nextTick(() => {
    showSnackbar.value = true;
  });
};

const submitDescriptionEdit = async (text: string) => {
  filmForm.value.description = text;
  await editFilm(locale.value);
  await fetchData();
  editDescriptionMode.value = false;
};

watch(
  locale,
  async (newVal) => {
    const filmId = Number(useRoute().params.id);
    await fetchFilmById(filmId, newVal);
  },
  { immediate: true }
);

onMounted(async () => {
  clearFilmForm();
  await fetchData();
});

definePageMeta({
  name: "filmDetails",
  path: "/films/:id",
  middleware: ["content-present"],
});
</script>
