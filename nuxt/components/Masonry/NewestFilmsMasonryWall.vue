<template>
  <masonry-wall :items="latestFilms" :gap="16">
    <template #default="{ item, index }">
      <MasonryCard
        :loading="loading"
        :index="index"
        :item="item"
        :img="item?.cover || ''"
        :link="`/films/${item?.id}`"
      >
        <template #append>
          <ClientOnly>
            <v-rating
              :model-value="item?.rating || 0"
              density="compact"
              size="small"
              readonly
              active-color="yellow-darken-3"
            />
          </ClientOnly>
        </template>

        <template #default>
          <v-list-item
            :subtitle="item?.description"
            border
            rounded="lg"
            class="ma-2"
            density="compact"
            lines="two"
          />
          <v-list v-if="item.assessments.length > 0" :nav="sidebar">
            <v-list-subheader>{{
              $t("pages.films.last_comments")
            }}</v-list-subheader>
            <v-list-item
              v-for="(comment, i) in item.assessments.slice(0, 5)"
              :key="i"
              :title="comment?.authorName ? comment?.authorName : 'Anonymous'"
              :prepend-avatar="
                comment?.authorAvatar ? comment?.authorAvatar : undefined
              "
              :subtitle="comment.comment"
            >
              <template #append>
                <v-chip
                  color="warning"
                  density="compact"
                  prepend-icon="mdi-star"
                >
                  {{ comment.rating }}
                </v-chip>
              </template>
            </v-list-item>
          </v-list>
          <div
            v-if="item.assessments.length > 2"
            class="d-flex flex-column justify-center align-center"
          >
            <span class="text-h6 mb-2">...</span>
            <v-btn
              prepend-icon="mdi-arrow-right"
              variant="plain"
              @click="navigateTo(`/films/${item?.id}`)"
            >
              {{ $t("actions.to_page") }}</v-btn
            >
          </div>
        </template>
      </MasonryCard>
    </template>
  </masonry-wall>
</template>

<script lang="ts" setup>
import MasonryCard from "./partials/MasonryCard.vue";

defineProps<{
  latestFilms: IFilm[];
  loading: boolean;
  sidebar?: boolean;
  link?: string;
}>();
</script>

<style></style>
