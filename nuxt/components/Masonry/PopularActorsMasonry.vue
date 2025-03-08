<template>
  <masonry-wall :items="popularActors" :max-columns="6" :gap="16">
    <template #default="{ item, index }">
      <MasonryCard
        :loading="loading"
        :item="item"
        :index="index"
        :img="item?.avatar || ''"
        :link="`/persons/${item?.id}`"
      />
    </template>
  </masonry-wall>
</template>

<script lang="ts" setup>
import MasonryCard from './partials/MasonryCard.vue';

defineProps<{
  popularActors: IPerson[];
  loading: boolean;
  sidebar: boolean;
}>();
</script>

<style></style>
