<template>
  <v-chip color="warning" density="compact" class="text-caption py-2" variant="tonal">
    <nuxt-link to="/auth/sign-in">
      {{ $t("auth.not_auth") }}
    </nuxt-link>
  </v-chip>
</template>

<script lang="ts" setup></script>

<style></style>
