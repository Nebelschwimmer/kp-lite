<template>
  <v-sheet height="100%">
    <div
      class="d-flex flex-column align-center justify-center fill-height text-error text-caption"
    >
      <v-icon color="error" icon="mdi-image-broken" />
      <v-label v-if="showLabel">{{ $t("general.img_broken") }}</v-label>
    </div>
  </v-sheet>
</template>

<script lang="ts" setup>
defineProps<{
  showLabel?: boolean;
}>();
</script>

<style></style>
