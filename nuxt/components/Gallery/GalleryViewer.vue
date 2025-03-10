<template>
  <div>
    <v-slide-group center-active>
      <v-slide-group-item
        v-for="(img, index) in sliderArr"
        :key="index"
        :value="index"
      >
        <v-card
          :height="SLIDER_HEIGHT"
          :width="CARD_WIDTH"
          class="ma-2"
          @click="openFullScreenModeOnClick(index)"
        >
          <template #image>
            <v-img v-if="img" :src="img" cover>
              <template #placeholder>
                <div class="d-flex align-center justify-center fill-height">
                  <v-progress-circular indeterminate />
                </div>
              </template>
              <template #error>
                <ErrorPlaceHolder />
              </template>
            </v-img>
            <v-sheet
              v-else
              rounded="lg"
              class="pa-2 cursor-pointer glassed"
              width="100%"
              height="100%"
            >
              <div
                class="d-flex flex-column align-center justify-center fill-height"
              >
                <v-list-item lines="two">
                  <v-list-item-subtitle class="text-caption text-center">{{
                    $t("general.available_for_upload")
                  }}</v-list-item-subtitle>
                </v-list-item>
              </div>
            </v-sheet>
          </template>
        </v-card>
      </v-slide-group-item>
    </v-slide-group>
    <GalleryFullscreenViewer
      v-model:show-gallery="fullscreenMode"
      v-model:active-img="activeImg"
      :gallery-content="gallery"
      :name="entityName"
      :no-content-label="$t('pages.films.no_gallery')"
      :with-avatar="withAvatar"
      @close="fullscreenMode = false"
      @avatar:set="handleSetAvatar"
      @cover:set="handleSetCover"
      @poster:set="handleSetPoster"
      @delete:img="handleDeleteImg"
    />
  </div>
</template>

<script lang="ts" setup>
import GalleryFullscreenViewer from "./GalleryFullscreenViewer.vue";
import ErrorPlaceHolder from "../Containment/Img/ErrorPlaceHolder.vue";
const emit = defineEmits<{
  (event: "editor:open"): void;
  (event: "avatar:set" | "cover:set" | "delete:img" | "poster:set", index: number): void;
}>();
const props = defineProps<{
  sliderArr: string[];
  gallery: string[];
  entityName: string;
  loading: boolean;
  disabled?: boolean;
  withAvatar: boolean;
}>();
const fullscreenMode = ref<boolean>(false);
const activeImg = ref<number>(0);
const SLIDER_HEIGHT: number = 275;
const CARD_WIDTH: number = 180;

const openFullScreenModeOnClick = (index: number): void => {
  if (props.gallery[index]) {
    fullscreenMode.value = true;
    activeImg.value = index;
  } else if (props.disabled) return;
  else {
    emit("editor:open");
  }
};
const handleSetAvatar = (index: number): void => {
  emit("avatar:set", index);
  fullscreenMode.value = false;
};

const handleSetCover = (index: number): void => {
  emit("cover:set", index);
  fullscreenMode.value = false;
};

const handleDeleteImg = (index: number): void => {
  emit("delete:img", index);
  fullscreenMode.value = false;
};

const handleSetPoster = (index: number): void => {
  console.log(1)
  emit("poster:set", index);
  fullscreenMode.value = false;
};
</script>
