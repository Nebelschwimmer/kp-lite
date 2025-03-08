<template>
  <div class="d-flex flex-column ga-2">
    <transition-group>
      <v-card
        v-if="!isAssessing"
        rounded="lg"
        elevation="2"
        variant="text"
        border
      >
        <v-card-text>
          <div class="d-flex flex-column justify-center ga-1 align-center">
            <div class="d-flex ga-1 align-center">
              <v-icon icon="mdi-star" size="x-large" color="warning" />
              <span class="text-h4">{{ currentRating }}</span>
            </div>
            <v-label>{{ computedAssessmentNumber }}</v-label>
            <v-btn
              :disabled="!isAuthenticated"
              color="secondary"
              @click="$emit('assession:enable')"
            >
              {{ $t("pages.films.assess") }}
            </v-btn>
          </div>
        </v-card-text>
      </v-card>
    </transition-group>
    <v-scroll-y-transition>
      <AssessmentForm
        v-if="isAssessing"
        :rating="rating"
        :comment="comment"
        @update:rating="$emit('rating:update', $event)"
        @update:comment="$emit('comment:update', $event)"
        @cancel="$emit('assession:cancel')"
        @submit="$emit('assession:submit')"
      />
    </v-scroll-y-transition>
    <v-card v-if="assessments.length > 0" rounded="lg" variant="text" border>
      <v-data-iterator
        :items="assessments"
        :page="page"
        :items-per-page="itemsPerPage"
      >
        <template #header="{ page: currPage, pageCount, prevPage, nextPage }">
          <div class="d-flex justify-space-between align-center pa-2">
            <h4>{{ $t("pages.films.comments") }}</h4>
            <v-btn
              class="me-8"
              variant="text"
              :disabled="assessments.length > itemsPerPage"
              @click="seeAllOnClick"
            >
              <span
                class="text-decoration-underline text-none text-subtitle-2"
                >{{ $t("general.see_all") }}</span
              >
            </v-btn>
            <div class="d-inline-flex">
              <v-btn
                :disabled="currPage === 1"
                class="me-2"
                icon="mdi-arrow-left"
                size="small"
                variant="plain"
                density="compact"
                @click="prevPage"
              />

              <v-btn
                :disabled="currPage === pageCount"
                icon="mdi-arrow-right"
                size="small"
                variant="plain"
                density="compact"
                @click="nextPage"
              />
            </div>
          </div>
          <v-divider />
        </template>
        <template #default="{ items }">
          <template v-for="(item, i) in items" :key="i">
            <v-list-item
              v-bind="item.raw"
              variant="tonal"
              class="rounded-lg ma-2"
              lines="two"
              :title="item.raw.authorName ? item.raw.authorName : 'Anonymous'"
              :prepend-avatar="
                item.raw.authorAvatar ? item.raw.authorAvatar : undefined
              "
              :subtitle="item.raw.comment"
            >
              <template #append>
                <ClientOnly>
                  <v-rating
                    density="compact"
                    size="small"
                    active-color="warning"
                    :readonly="true"
                    :model-value="item.raw.rating"
                  />
                </ClientOnly>
              </template>
              <template #title>
                <div class="d-flex ga-1 align-end">
                  <span>{{
                    item.raw.authorName ? item.raw.authorName : "Anonymous"
                  }}</span>
                  <span
                    v-if="$vuetify.display.mdAndUp"
                    class="text-caption text-disabled"
                    >{{ dateFormatter(item.raw.createdAt) }}</span
                  >
                </div>
              </template>
            </v-list-item>
          </template>
        </template>
        <template #footer="{ pageCount }">
          <v-footer class="justify-space-between text-subtitle-2 mt-4">
            {{ $t("general.total") }}: {{ assessments.length }}

            <div>
              {{ $t("general.page") }} {{ page }} {{ $t("general.of") }}
              {{ pageCount }}
            </div>
          </v-footer>
        </template>
      </v-data-iterator>
    </v-card>
  </div>
</template>

<script lang="ts" setup>
import AssessmentForm from "../Forms/AssessmentForm.vue";

defineEmits([
  "assession:submit",
  "assession:enable",
  "assession:cancel",
  "comment:update",
  "rating:update",
]);
const props = defineProps<{
  currentRating: string | number;
  assessments: IAssessment[];
  isAssessing: boolean;
  isAuthenticated: boolean;
  rating: number;
  comment: string;
}>();
const page = ref<number>(1);
const itemsPerPage = ref<number>(5);

const seeAllOnClick = () => {
  itemsPerPage.value = itemsPerPage.value === 5 ? props.assessments.length : 5;
};

const { t, locale } = useI18n();

const computedAssessmentNumber = computed(() => {
  const label =
    locale.value == "ru"
      ? declineInRussian(props.assessments.length, [
          "оценка",
          "оценки",
          "оценок",
        ])
      : t("pages.films.assessments");
  return label || t("pages.films.no_assessments");
});
</script>

<style></style>
