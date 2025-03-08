<template>
  <v-btn icon variant="plain" @click="$emit('close')">
    <v-icon icon="mdi-close"/>
    <v-tooltip activator="parent" location="bottom">
      {{ $t("actions.close") }}
    </v-tooltip>
  </v-btn>
</template>

<script lang="ts" setup>
defineEmits(["close"]);
</script>

<style></style>
