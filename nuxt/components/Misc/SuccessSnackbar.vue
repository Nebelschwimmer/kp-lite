<template>
  <v-snackbar :model-value="show" color="success" class="text-center">
    {{ $t("toast.messages.success.edit") }}
    <template #actions>
      <CloseBtn @close="$emit('close')" />
    </template>
  </v-snackbar>
</template>

<script lang="ts" setup>
import CloseBtn from "../Containment/Btns/CloseBtn.vue";
defineEmits<{
  (event: "close"): void;
}>();
defineProps<{
  show: boolean;
}>();
</script>

<style></style>
