<template>
  <v-card rounded="lg" class="pa-2 mb-3">
    <v-tabs v-model="active">
      <v-tab value="cover" prepend-icon="mdi-image" color="primary">{{
        $t("actions.edit_cover")
      }}</v-tab>
       <v-tab value="poster" prepend-icon="mdi-post" color="primary">{{
        $t("actions.edit_poster")
      }}</v-tab>
      <v-tab
        value="upload"
        prepend-icon="mdi-upload"
        :disabled="uploadDisabled"
        color="primary"
        >{{ $t("actions.upload") }}</v-tab
      >
      <v-tab
        value="remove"
        prepend-icon="mdi-delete"
        :disabled="removeDisabled"
        color="error"
        >{{ $t("actions.remove") }}</v-tab
      >
    </v-tabs>
    <v-card-text>
      <v-tabs-window v-model="active">
        <v-tabs-window-item value="cover">
          <SingleImgSelector
            :cover-index="selectedCoverIndex"
            :gallery="film?.gallery || []"
            :card-height="cardHeight"
            @img:select="$emit('cover:change', $event)"
          />
        </v-tabs-window-item>
        <v-tabs-window-item value="poster">
          <SingleImgSelector
            :cover-index="selectedPosterIndex"
            :gallery="film?.gallery || []"
            :card-height="cardHeight"
            @img:select="$emit('poster:change', $event)"
          />
        </v-tabs-window-item>
        <v-tabs-window-item value="upload">
          <GalleryUploader
            :upload-count="uploadCount"
            :upload-error="uploadError"
            @files:upload="$emit('upload', $event)"
          />
        </v-tabs-window-item>
        <v-tabs-window-item value="remove">
          <MultipleImgSelector
            :slider-gallery-arr="sliderArr || []"
            :card-height="cardHeight"
            @delete:selected="$emit('delete:selected', $event)"
            @update:selected="$emit('update:selected', $event)"
            @gallery:clear="clearGalleryFiles"
          />
        </v-tabs-window-item>
      </v-tabs-window>
    </v-card-text>
  </v-card>
</template>

<script lang="ts" setup>
import GalleryUploader from "./GalleryUploader.vue";
import MultipleImgSelector from "./Partials/MultipleImgSelector.vue";
import SingleImgSelector from "./Partials/SingleImgSelector.vue";

defineEmits(["update:selected", "delete:selected", "upload", "cover:change", "poster:change"]);

const props = defineProps<{
  activeTab?: number;
  film?: IFilm | null;
  selected: number[];
  uploadCount: number;
  uploadDisabled: boolean;
  removeDisabled: boolean;
  sliderArr?: string[];
  cardHeight?: number;
}>();
const active = ref(props.activeTab || 0);
const galleryFiles = ref<File[]>([]);
const selectedCoverIndex = ref(
  props.film?.gallery?.findIndex(
    (item: string) => item === props.film?.cover
  ) === -1
    ? 0
    : (props.film?.gallery?.findIndex(
        (item: string) => item === props.film?.cover
      ) ?? 0) + 1
);

const selectedPosterIndex = ref(
  props.film?.gallery?.findIndex(
    (item: string) => item === props.film?.poster
  ) === -1
    ? 0
    : (props.film?.gallery?.findIndex(
        (item: string) => item === props.film?.poster
      ) ?? 0) + 1
);

const computedUploadCount = computed((): number => {
  return props.uploadCount - galleryFiles.value.length;
});

const uploadError = computed((): boolean => {
  return computedUploadCount.value <= 0;
});

const clearGalleryFiles = () => {
  galleryFiles.value = [];
};
</script>

<style lang="scss">
.bg-selected-remove {
  background-color: rgba(255, 0, 0, 0.2) !important;
}
.bg-selected-cover {
  background-color: rgba(0, 255, 0, 0.2) !important;
}
</style>
