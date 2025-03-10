<template>
  <v-footer class="d-flex flex-column text-subtitle-2">
    <span>© Nebelschwimmer, {{ currentYear }}</span>
    <nuxt-link style="text-decoration: underline" to="/about">{{
      $t("general.about")
    }}</nuxt-link>
  </v-footer>
</template>

<script lang="ts" setup>
  const currentYear: number = new Date().getFullYear();
</script>
