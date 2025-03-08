<template>
  <v-dialog
    :model-value="opened"
    :max-width="maxWidth ? maxWidth : 800"
    :fullscreen="$vuetify.display.mobile"
  >
    <v-card :loading="loading">
      <v-toolbar :title="title">
        <template #append>
          <CloseBtn @close="$emit('close')" />
        </template>
      </v-toolbar>
      <v-card-text>
        <slot name="text" />
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script lang="ts" setup>
import CloseBtn from "../Containment/Btns/CloseBtn.vue";
defineEmits(["close"]);
defineProps<{
  maxWidth?: number;
  opened: boolean;
  title: string;
  loading?: boolean;
}>();
</script>

<style></style>
