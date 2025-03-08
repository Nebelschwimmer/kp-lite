<template>
  <div>
    <Head>
      <Title>{{ definePageTitle($t("pages.films.title")) }}</Title>
      <Meta name="description" :content="$t('page_descriptions.films_list')" />
    </Head>
    <ClientOnly>
      <v-navigation-drawer location="start" width="400">
        <v-card
          class="pa-4 text-center"
          :title="$t('pages.home.newest')"
          variant="text"
        >
          <NewestFilmsMasonryWall
            v-if="latestFilms.length"
            :latest-films="latestFilms"
            :loading="loading"
            sidebar
          />
          <span v-else class="text-disabled">{{ $t("general.no_data") }}</span>
        </v-card>
      </v-navigation-drawer>
    </ClientOnly>
    <ListPage
      v-if="filmsPresent"
      :items="filmItems || []"
      :loading="loading"
      :total-pages="totalPages"
      :page="currentPage"
      :limit="computedLimitProp"
      :list-title="$t('nav.films_list')"
      new-page-link="/films/new"
      @update:page="updateQueryParams"
      @update:search="search = $event"
    >
      <template #filters>
        <Filters
          :sort-options="sortOptions"
          @update:limit="limit = $event.value"
          @update:order="order = $event.value"
          @update:search="search = $event.value"
          @update:sort="sortBy = $event.value"
        />
      </template>
    </ListPage>
  </div>
</template>

<script lang="ts" setup>
import ListPage from "~/components/Templates/ListPage.vue";
import Filters from "~/components/Misc/Filters.vue";
import NewestFilmsMasonryWall from "~/components/Masonry/NewestFilmsMasonryWall.vue";
import { useFilmStore } from "~/stores/filmStore";
import definePageTitle from "~/utils/definePageTitle";

const { films, loading, totalPages, currentPage, filmsPresent, latestFilms } =
  storeToRefs(useFilmStore());
const { fetchFilteredFilms, checkFilmsPresence, fetchLatestFilms } =
  useFilmStore();
const { locale, t } = useI18n();
const limit = ref<number>(5);
const offset = ref<number>(0);
const search = ref<string>("");
const order = ref<string>("asc");
const sortBy = ref<string>("name");

const sortOptions = [
  { value: "name", title: t("forms.film.name") },
  { value: "year_of_release", title: t("forms.film.release_year") },
] as IOption[];

const fetchData = async (): Promise<void> => {
  await checkFilmsPresence();
  if (filmsPresent.value) {
    await Promise.allSettled([
      fetchFilteredFilms(
        limit.value,
        offset.value,
        search.value,
        order.value,
        sortBy.value,
        locale.value
      ),
      fetchLatestFilms(),
    ]);
  } else {
    navigateTo("/films/empty");
  }
};

const filmItems = computed((): Detail[] => {
  return films.value[0] !== null
    ? films.value?.map((film) => {
        return {
          title:
            film?.name +
            " (" +
            (film?.releaseYear ? film.releaseYear.toString() : "") +
            ")",
          value: film.description || "",
          avatar: film.cover || film.gallery[0] || "",
          to: "/films/" + film.id,
          createdAt: film.createdAt || "",
        };
      })
    : [];
});

const computedLimitProp = computed((): number => {
  return typeof limit.value === "number" ? limit.value : 15;
});

const updateQueryParams = (page: number): void => {
  offset.value = (page - 1) * limit.value;
};

watch(
  [limit, offset, search, order, sortBy, locale],
  async ([newLimit, newOffset, newSearch, newOrder, newSortBy, newLocale]) => {
    await fetchFilteredFilms(
      newLimit,
      newOffset,
      newSearch === null ? "" : newSearch,
      newOrder,
      newSortBy,
      newLocale
    );
  },
  {
    immediate: true,
  }
);

onMounted(async () => {
  await fetchData();
});

definePageMeta({
  layout: "default",
  name: "films",
  path: "/films",
  key: (route) => route.fullPath,
});
</script>

<style></style>
