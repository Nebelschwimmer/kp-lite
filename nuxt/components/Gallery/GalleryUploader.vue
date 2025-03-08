<template>
  <v-card>
    <v-toolbar>
      <v-alert
        icon="mdi-information"
        border="start"
        :border-color="!uploadError ? 'primary' : 'error'"
      >
        <v-chip label>
          <template v-if="!uploadError">
            {{ $t("general.available_for_upload") }}:
            {{ computedUploadCount }}
          </template>
          <template v-else> {{ $t("general.max_files_error") }}! </template>
        </v-chip>
      </v-alert>
    </v-toolbar>
    <v-file-upload
      v-model="previews"
      show-size
      chips
      clearable
      multiple
      :density="$vuetify.display.mdAndUp ? 'default' : 'comfortable'"
      accept="image/*"
      divider-text="general.or"
      browse-text="general.browse_files"
      :title="$vuetify.display.mdAndUp ? 'general.drag_and_drop' : ''"
      @update:model-value="handlePreupload"
    >
      <template #item="{ props: itemProps }">
        <v-file-upload-item v-bind="itemProps" lines="one" nav>
          <template #clear="{ props: clearProps }">
            <v-btn color="primary" v-bind="clearProps" />
          </template>
        </v-file-upload-item>
      </template>
    </v-file-upload>

    <template #actions>
      <v-spacer />
      <v-btn
        prepend-icon="mdi-close"
        :disabled="!previews.length"
        :color="uploadError ? 'error' : ''"
        @click="clearPreviews"
        >{{ $t("actions.clear") }}</v-btn
      >
      <v-btn
        prepend-icon="mdi-cloud"
        color="warning"
        :disabled="!previews.length || uploadError"
        @click="$emit('files:upload', previews)"
        >{{ $t("actions.upload") }}</v-btn
      >
    </template>
  </v-card>
</template>

<script lang="ts" setup>
const emit = defineEmits(["files:preupload", "files:upload"]);
const previews = ref<File[]>([]);
const props = defineProps<{
  uploadCount: number;
}>();

const uploadError = computed((): boolean => {
  return previews.value.length > props.uploadCount;
});

const handlePreupload = () => {
  if (previews.value.length > props.uploadCount) {
    return;
  }

  emit("files:preupload", previews);
};
const computedUploadCount = computed((): number => {
  return props.uploadCount - previews.value.length;
});

const clearPreviews = () => {
  previews.value = [];
};
</script>

<style></style>
