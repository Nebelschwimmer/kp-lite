<template>
  <div>
    <Head>
      <Title>{{ definePageTitle($t("pages.persons.title")) }}</Title>
      <Meta
        name="description"
        :content="$t('page_descriptions.persons_list')"
      />
    </Head>
    <v-navigation-drawer location="start" width="400">
      <v-card
        :title="$t('pages.home.popular_actors')"
        class="text-center pa-4"
        variant="text"
      >
        <PopularActorsMasonry
          v-if="popularActors.length"
          :popular-actors="popularActors"
          :loading="loading"
          sidebar
        />
        <span v-else class="text-disabled">{{ $t("general.no_data") }}</span>
      </v-card>
    </v-navigation-drawer>

    <ListPage
      v-if="personsPresent"
      :items="personItems || []"
      :loading="loading"
      :total-pages="totalPages"
      :page="currentPage"
      :limit="limit !== 'all' ? (limit as number) : 15"
      :list-title="$t('nav.persons')"
      new-page-link="/persons/new"
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
import PopularActorsMasonry from "~/components/Masonry/PopularActorsMasonry.vue";
import { usePersonStore } from "~/stores/personStore";
import definePageTitle from "~/utils/definePageTitle";

const { locale, t } = useI18n();

const {
  loading,
  totalPages,
  currentPage,
  personsPresent,
  persons,
  popularActors,
} = storeToRefs(usePersonStore());
const {
  fetchFilteredPersons,
  listPopularActors,
  fetchGenders,
  fetchSpecialties,
  checkPersonsPresence,
} = usePersonStore();
const limit = ref<number | string>(5);
const offset = ref<number>(0);
const search = ref<string>("");
const sortBy = ref<string>("firstname");
const order = ref<string>("asc");
const specialtySort = ref<string>("all");

const sortOptions = [
  { value: "firstname", title: t("filters.sort.persons.firstname") },
  { value: "age", title: t("filters.sort.persons.age") },
];

const fetchData = async (): Promise<void> => {
  await checkPersonsPresence();
  if (personsPresent.value) {
    await Promise.allSettled([
      fetchFilteredPersons(
        limit.value,
        offset.value,
        search.value,
        sortBy.value,
        order.value,
        specialtySort.value,
        locale.value
      ),
      fetchGenders(locale.value),
      fetchSpecialties(locale.value),
      listPopularActors(),
    ]);
  } else {
    navigateTo("/persons/empty");
  }
};

const personItems = computed((): Detail[] => {
  return (
    persons.value &&
    persons.value?.map((person): Detail => {
      return {
        title: person?.firstname + " " + person?.lastname,
        value: person?.specialtyNames.join(", "),
        avatar: person?.avatar || "",
        to: "/persons/" + person?.id,
        createdAt: person?.createdAt || "",
        updatedAt: person?.updatedAt || "",
        publishedBy: person?.publisherData.name || "",
      };
    })
  );
});

const updateQueryParams = (page: number): void => {
  if (limit.value !== "all") {
    offset.value = (page - 1) * Number(limit.value);
  }
};

watch(
  [limit, offset, sortBy, order, specialtySort, locale],
  async ([
    newLimit,
    newOffset,
    newSortBy,
    newOrder,
    newSpecialtySort,
    newLocale,
  ]) => {
    await fetchFilteredPersons(
      newLimit,
      newOffset,
      "",
      newSortBy,
      newOrder,
      newSpecialtySort,
      newLocale
    );
  },
  {
    immediate: true,
  }
);

watch(
  search,
  debounce(async (newVal: string): Promise<void> => {
    await fetchFilteredPersons(
      limit.value,
      0,
      newVal,
      sortBy.value,
      order.value,
      specialtySort.value,
      locale.value
    );
  }, 1000)
);

onMounted(async (): Promise<void> => {
  await fetchData();
});

definePageMeta({
  name: "persons",
  path: "/persons",
  key: (route) => route.fullPath,
});
</script>

<style></style>
