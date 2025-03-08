<template>
  <div>
    <Head>
      <Title>{{ definePageTitle(personFullName) }}</Title>
      <Meta name="description" :content="person?.bio" />
    </Head>
    <v-sheet max-width="1200" class="mx-auto" rounded="lg">
      <DetailCard
        display-avatar
        :page-name="$t('pages.persons.details')"
        :cover="person?.cover || ''"
        :is-auth="isAuthenticated"
        :loading="loading"
        :notification="!isAuthenticated"
      >
        <template #general_info>
          <TopInfo
            :loading="loading"
            :general-info="computedPersonDetails"
            :avatar="person?.avatar || ''"
            :title="personFullName"
            :subtitle="specialtyNames"
            @avatar:edit="chooseAvatar"
          />
        </template>
        <template #notification>
          <NotAuthWarning v-if="!isAuthenticated" />
        </template>
        <template #menu>
          <v-menu location="bottom end">
            <template #activator="{ props }">
              <v-btn icon :disabled="!currentUser" v-bind="props">
                <v-icon>mdi-dots-vertical</v-icon>
              </v-btn>
            </template>
            <v-list density="compact">
              <v-list-item
                :title="$t('actions.choose_cover')"
                prepend-icon="mdi-image"
                value="cover"
                @click="chooseCover"
              />
              <v-list-item
                :title="$t('actions.edit_avatar')"
                prepend-icon="mdi-account"
                value="avatar"
                @click="chooseAvatar"
              />
              <v-list-item
                :title="$t('actions.edit')"
                prepend-icon="mdi-pencil"
                value="edit"
              >
                <template #append>
                  <v-icon icon="mdi-menu-right" size="x-small" />
                </template>
                <v-menu activator="parent" submenu open-on-hover>
                  <v-list density="compact">
                    <v-list-item
                      :title="$t('pages.general_info')"
                      prepend-icon="mdi-information"
                      value="info"
                      @click="generalInfoEdit = true"
                    />
                    <v-list-item
                      :title="$t('pages.detailed_info')"
                      prepend-icon="mdi-details"
                      value="details"
                      @click="handleBioEdit"
                    />
                    <v-list-item
                      :title="$t('pages.gallery')"
                      prepend-icon="mdi-view-gallery"
                      value="gallery"
                      @click="photoEditMode = true"
                    />
                  </v-list>
                </v-menu>
              </v-list-item>
              <v-list-item
                :title="$t('actions.remove')"
                prepend-icon="mdi-delete"
                value="remove"
                base-color="error"
                @click="showDeleteWarning = true"
              />
            </v-list>
          </v-menu>
        </template>

        <template #text>
          <v-expansion-panels
            v-model="mainAccordion"
            variant="accordion"
            multiple
          >
            <v-expansion-panel
              id="bio"
              value="bio"
              class="content-item"
              tag="section"
              :title="$t('pages.persons.bio')"
            >
              <v-expansion-panel-text>
                <IndentedEditableText
                  v-if="person?.bio"
                  :edit-mode="bioEditMode"
                  :messages="$t('pages.persons.edit_bio')"
                  :text="person?.bio || ''"
                  @sumbit:edit="submitBioEdit"
                />
                <div v-else class="w-100 text-center">
                  <span>{{ $t("general.no_data") }}</span>
                </div>
              </v-expansion-panel-text>
            </v-expansion-panel>
            <v-expansion-panel
              v-if="person?.filmWorks"
              id="filmography"
              tag="section"
              value="filmography"
              class="content-item"
              :title="$t('pages.persons.filmography')"
            >
              <v-expansion-panel-text>
                <Filmography :person="person" />
              </v-expansion-panel-text>
            </v-expansion-panel>
            <v-expansion-panel
              id="gallery"
              tag="section"
              class="content-item"
              value="gallery"
              :title="$t('pages.persons.photos')"
            >
              <v-expansion-panel-text>
                <GalleryViewer
                  :slider-arr="sliderGalleryArr || []"
                  :disabled="!isAuthenticated"
                  :gallery="person?.photos || []"
                  :entity-name="personFullName"
                  :loading="loading"
                  with-avatar
                  @editor:open="photoEditMode = true"
                  @cover:set="handleCoverChange"
                  @avatar:set="handleChangeAvatar"
                  @delete:img="handleDeleteImg"
                />
              </v-expansion-panel-text>
            </v-expansion-panel>
          </v-expansion-panels>
        </template>
      </DetailCard>
      <BaseDialog
        v-model:opened="generalInfoEdit"
        :max-width="1000"
        :title="$t('actions.edit_person') + ' ' + personFullName"
       
        @close="generalInfoEdit = false"
      >
        <template #text>
          <div class="pa-4">
            <PersonForm
              :loading="loading"
              :person-form="personForm"
              :genders="genders"
              :specialties="specialties"
              @validate="isFormValid = $event"
              @form:submit="submitGeneralInfoEdit"
              @update:model-value="personForm = $event"
            />
          </div>
        </template>
      </BaseDialog>
      <SuccessSnackbar
        v-model:show="showSnackbar"
        @close="showSnackbar = false"
      />
      <BaseDialog
        v-model:opened="photoEditMode"
        :title="computedGalleryEditTitle"
        :max-width="1200"
        @close="photoEditMode = false"
      >
        <template #text>
          <PersonGalleryEdit
            v-model:selected="selectedImagesIndices"
            :active-tab="activeTab"
            :person="person"
            :slider-arr="sliderGalleryArr || []"
            :upload-count="uploadCount"
            :edit-mode="photoEditMode"
            :upload-disabled="uploadCount === 0"
            :remove-disabled="!person?.photos.length"
            :card-height="GALLERY_CARD_HEIGHT"
            @avatar:change="handleChangeAvatar"
            @avatar:upload="handleAvatarUpload"
            @update:selected="selectedImagesIndices = $event"
            @delete:selected="showConfirmDialog = true"
            @cover:change="handleCoverChange"
            @upload="handlePhotosUpload"
          />
        </template>
      </BaseDialog>
      <ConfirmDialog
        v-model="showConfirmDialog"
        type="error"
        :text="$t('forms.film.gallery_item_delete_confirm')"
        :loading="loading"
        @confirm="handlePhotosDelete"
      />
      <ConfirmDialog
        v-model="showCoverReplacementWarning"
        :text="$t('general.file_replacement_warning')"
        @cancel="showCoverReplacementWarning = false"
        @confirm="replaceCover"
      />
      <ConfirmDialog
        v-model="showDeleteWarning"
        :text="$t('general.entity_delete_warning')"
        @cancel="showDeleteWarning = false"
        @confirm="handlePersonDelete"
      />
    </v-sheet>
  </div>
</template>

<script lang="ts" setup>
import DetailCard from "~/components/Containment/Cards/DetailCard.vue";
import BaseDialog from "~/components/Dialogs/BaseDialog.vue";
import PersonForm from "~/components/Forms/PersonForm.vue";
import IndentedEditableText from "~/components/Misc/IndentedEditableText.vue";
import GalleryViewer from "~/components/Gallery/GalleryViewer.vue";
import PersonGalleryEdit from "~/components/Gallery/PersonGalleryEdit.vue";
import ConfirmDialog from "~/components/Dialogs/ConfirmDialog.vue";
import SuccessSnackbar from "~/components/Misc/SuccessSnackbar.vue";
import NotAuthWarning from "~/components/Misc/NotAuthWarning.vue";
import TopInfo from "~/components/Containment/Cards/partials/TopInfo.vue";
import Filmography from "~/components/PersonPartials/Filmography.vue";
import { usePersonStore } from "~/stores/personStore";
import { useAuthStore } from "~/stores/authStore";
import definePageTitle from "~/utils/definePageTitle";

const localeRoute = useLocaleRoute();
const { locale, t } = useI18n();

const isFormValid = ref<boolean>(false);
const showSnackbar = ref<boolean>(false);
const showDeleteWarning = ref<boolean>(false);
const showConfirmDialog = ref<boolean>(false);
const photoEditMode = ref<boolean>(false);
const showCoverReplacementWarning = ref<boolean>(false);
const generalInfoEdit = ref<boolean>(false);
const bioEditMode = ref<boolean>(false);

const GALLERY_CARD_HEIGHT: number = 200;
const activeTab = ref<number>(0);
const selectedImagesIndices = ref<number[]>([]);
const mainAccordion = ref<string[]>(["bio", "gallery", "filmography"]);
const coverFile = ref<File>();
const avatarFile = ref<File | null>(null);
const { currentUser, isAuthenticated } = storeToRefs(useAuthStore());
const { person, genders, specialties, personForm, loading } =
  storeToRefs(usePersonStore());
const {
  fetchPersonDetails,
  editPerson,
  fetchGenders,
  fetchPersonForm,
  fetchSpecialties,
  uploadPhotos,
  uploadCover,
  removePerson,
  deleteGalleryItems,
  GALLERY_SIZE,
} = usePersonStore();

const imagesToDelete = computed(() => {
  return person.value?.photos
    .filter((_: string, index: number): boolean =>
      selectedImagesIndices.value.includes(index)
    )
    .map((imageName: string): string => {
      const fileName = imageName ? imageName.split("/").at(-1) : "";

      return fileName ? fileName.split(".")[0] : "";
    });
}) as ComputedRef<string[]>;

const chooseCover = (): void => {
  photoEditMode.value = true;
  activeTab.value = 1;
};

const handleBioEdit = (): void => {
  bioEditMode.value = true;
  if (mainAccordion.value.indexOf("bio") === -1) {
    mainAccordion.value.push("bio");
  }
  const element = document.getElementById("bio");
  if (element) {
    element.scrollIntoView({ behavior: "smooth" });
  }
};

const personFullName = computed((): string => {
  return `${person.value?.firstname} ${person.value?.lastname}`;
});
const computedGalleryEditTitle = computed((): string => {
  return t("pages.films.edit_gallery") + " " + personFullName.value;
});
const computedPersonDetails = computed((): Detail[] => {
  return [
    {
      title: t("forms.person.gender"),
      value: person.value?.gender || "",
      icon: "mdi-gender-male-female",
    },
    {
      title: t("forms.person.birthday"),
      value:
        locale.value === "ru"
          ? `${declineInRussian(person.value?.age || 0, ["год", "года", "лет"])}`
          : `${person.value?.age}`,
      icon: "mdi-calendar",
    },
  ];
});

const sliderGalleryArr = computed((): string[] => {
  const initialArr = Array.from({ length: GALLERY_SIZE }, (_, i) => i);

  return initialArr.map((item: number) => {
    return (person.value && person?.value.photos[item]) || "";
  });
});

const uploadCount = computed((): number => {
  return person.value?.photos.length
    ? sliderGalleryArr.value.filter((item: string) => item === "").length
    : GALLERY_SIZE;
});

const specialtyNames = computed((): string => {
  return person.value?.specialtyNames
    ? person.value.specialtyNames.join(", ")
    : "";
});

const submitGeneralInfoEdit = async (): Promise<void> => {
  await editPerson();
  await fetchData();
  generalInfoEdit.value = false;
  showSnackbar.value = !showSnackbar.value;
};

const submitBioEdit = async (text: string): Promise<void> => {
  personForm.value.bio = text;
  await editPerson();
  await fetchData();
  bioEditMode.value = false;
  showSnackbar.value = !showSnackbar.value;
};

const handleChangeAvatar = async (index: number): Promise<void> => {
  personForm.value.avatar = person.value?.photos[index - 1] || "";
  await editPerson();
  photoEditMode.value = false;
  await fetchData();
  showSnackbar.value = !showSnackbar.value;
};

const handleAvatarUpload = async (files: File[]): Promise<void> => {
  avatarFile.value = files[0];
  const personId: number = Number(useRoute().params.id);
  await uploadPhotos([avatarFile.value], personId);
  personForm.value.avatar = person.value?.photos[0] || "";
  await editPerson();
  photoEditMode.value = false;
  await fetchData();
  showSnackbar.value = !showSnackbar.value;
};

const chooseAvatar = (): void => {
  if (person.value?.avatar) {
    photoEditMode.value = true;
    activeTab.value = 0;
  } else {
    photoEditMode.value = true;
    activeTab.value = 2;
  }
};

const handlePhotosUpload = async (files: File[]): Promise<void> => {
  const personId: number = Number(useRoute().params.id);
  console.log(personId);
  await uploadPhotos(files, personId);
  photoEditMode.value = false;
  await fetchData();
  showSnackbar.value = !showSnackbar.value;
};

const handleCoverChange = async (files: File[]): Promise<void> => {
  const file: File = files[0];
  const id = Number(useRoute().params.id);
  if (person.value?.cover !== "") {
    showCoverReplacementWarning.value = true;
    coverFile.value = file;
    return;
  }
  await uploadCover(file, id || 0);
  await fetchData();
  photoEditMode.value = false;
  showSnackbar.value = !showSnackbar.value;
};

const replaceCover = async (): Promise<void> => {
  showCoverReplacementWarning.value = false;
  const file: File = coverFile.value as File;
  const id: number = Number(useRoute().params.id);
  await uploadCover(file, id || 0);
  await fetchData();
  photoEditMode.value = false;
  showSnackbar.value = !showSnackbar.value;
};

const handlePhotosDelete = async () => {};

const handlePersonDelete = async (): Promise<void> => {
  await removePerson(Number(useRoute().params.id));
  navigateTo(localeRoute("/persons"));
};

const handleGalleryItemsDelete = async () => {
  await deleteGalleryItems(imagesToDelete.value);
  await fetchData();
  await nextTick(() => {
    showSnackbar.value = true;
    showConfirmDialog.value = false;
  });
};

const handleDeleteImg = async (index: number) => {
  selectedImagesIndices.value = [index];
  await handleGalleryItemsDelete();
};

const fetchData = async (): Promise<void> => {
  const personId: number = Number(useRoute().params.id);

  await Promise.allSettled([
    fetchPersonDetails(personId, locale.value),
    fetchPersonForm(personId),
    fetchGenders(locale.value),
    fetchSpecialties(locale.value),
  ]);
};

onMounted(async (): Promise<void> => {
  await fetchData();
});

definePageMeta({
  name: "personDetails",
  path: "/persons/:id",
  middleware: ["content-present"],
});
</script>

<style></style>
