<template>
  <v-btn
    variant="plain"
    :prepend-icon="expanded ? 'mdi-chevron-up' : 'mdi-chevron-down'"
    @click="$emit('toggle:expanded')"
    >{{
      !expanded
        ? $t("actions.expand") + " " + (what ? what : "")
        : $t("actions.collapse") + " " + (what ? what : "")
    }}</v-btn
  >
</template>

<script lang="ts" setup>
defineProps<{ expanded: boolean; what?: string }>();
defineEmits(["toggle:expanded"]);
</script>

<style></style>
