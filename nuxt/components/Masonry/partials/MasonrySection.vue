<template>
  <v-card
    v-if="present"
    tag:section
    class="my-3"
    rounded="lg"
    :color="darkAccentColor"
    border
  >
    <v-card-title>
      <div class="pa-2 d-flex flex-column ga-2 mb-2">
        <h4 class="text-lg-h4 text-h5 text-center text-uppercase">
          {{ title }}
        </h4>
        <SectionDivider />
      </div>
    </v-card-title>
    <v-card-text v-if="!loading">
      <slot />
    </v-card-text>
    <v-row v-else>
      <v-col v-for="i in 2" :key="i">
        <v-skeleton-loader type="card" height="100vh" />
      </v-col>
    </v-row>
  </v-card>
</template>

<script lang="ts" setup>
import SectionDivider from "~/components/Misc/SectionDivider.vue";
defineProps<{
  present: boolean;
  loading: boolean;
  title: string;
  darkAccentColor?: string;
}>();
</script>
