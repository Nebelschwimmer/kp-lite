<template>
  <v-list-item variant="flat">
    <v-list-item-title>
      <nuxt-link
        class="d-flex align-center font-weight-bold text-lg-h5 text-h6 ga-2 text-primary"
        :to="localeRoute('/')"
      >
        <v-icon>mdi-movie</v-icon>
        {{ config.appName }}
      </nuxt-link>
    </v-list-item-title>
    <v-list-item-subtitle v-if="$vuetify.display.mdAndUp">
      {{ $t("logo_description") }}
    </v-list-item-subtitle>
  </v-list-item>
</template>

<script lang="ts" setup>
const localeRoute = useLocaleRoute();

const config = useRuntimeConfig().public;
</script>

<style scoped></style>
