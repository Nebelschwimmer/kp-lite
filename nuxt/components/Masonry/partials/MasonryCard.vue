<template>
  <v-card
    :key="index"
    :loading="loading"
    :title="item?.name"
    rounded="lg"
    height="100%"
    elevation="5"
  >
    <template #image>
      <v-img :src="img" class="img-blur" />
    </template>
    <template #append>
      <slot name="append" />
    </template>
    <v-img
      :src="img"
      min-height="400"
      cover
      rounded="lg"
      class="cursor-pointer ma-2"
      @click="navigateTo(localeRoute(link))"
    >
      <template #placeholder>
        <v-card height="100%" variant="plain" rounded="lg" class="glassed">
          <div class="d-flex align-center justify-center h-100">
            <v-icon size="64">mdi-image-off</v-icon>
          </div>
        </v-card>
      </template>
      <template #error>
        <ErrorPlaceHolder />
      </template>
    </v-img>
    <slot />
  </v-card>
</template>

<script lang="ts" setup>
import ErrorPlaceHolder from "~/components/Containment/Img/ErrorPlaceHolder.vue";
defineProps<{
  loading: boolean;
  img: string;
  index: number;
  item: IFilm | IPerson;
  link: string;
}>();

const localeRoute = useLocaleRoute();
</script>

<style></style>
