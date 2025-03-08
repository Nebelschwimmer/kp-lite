<template>
  <v-dialog :model-value="showGallery" fullscreen>
    <v-sheet>
      <v-toolbar>
        <v-toolbar-title>
          {{ name }}:
          <span class="text-lowercase">{{ $t("pages.films.gallery") }}</span>
        </v-toolbar-title>
        <template v-if="$vuetify.display.smAndDown">
          <v-menu>
            <template #activator="{ props }">
              <v-btn v-bind="props" icon :disabled="!isAuthenticated">
                <v-icon>mdi-dots-vertical</v-icon>
              </v-btn>
            </template>

            <v-list rounded="">
              <v-list-item
                v-if="withAvatar"
                :title="$t('actions.set_avatar')"
                prepend-icon="mdi-account"
                @click="$emit('avatar:set', currentImgIndex + 1)"
              />

              <v-list-item
                :title="$t('actions.set_cover')"
                prepend-icon="mdi-image"
                @click="$emit('cover:set', currentImgIndex + 1)"
              />
              <v-list-item
                :title="$t('actions.remove')"
                base-color="error"
                prepend-icon="mdi-delete"
                @click="showConfirmDialog = true"
              />
            </v-list>
          </v-menu>
        </template>
        <template v-else>
          <v-btn
            v-if="withAvatar"
            :disabled="!isAuthenticated"
            prepend-icon="mdi-account"
            @click="$emit('avatar:set', currentImgIndex + 1)"
            >{{ $t("actions.set_avatar") }}</v-btn
          >
          <v-btn
            prepend-icon="mdi-image"
            :disabled="!isAuthenticated"
            @click="$emit('cover:set', currentImgIndex + 1)"
            >{{ $t("actions.set_cover") }}</v-btn
          >
          <v-btn
            :disabled="!isAuthenticated"
            color="error"
            prepend-icon="mdi-delete"
            @click="showConfirmDialog = true"
            >{{ $t("actions.remove") }}</v-btn
          >
        </template>
        <CloseBtn @click="$emit('close')" />
      </v-toolbar>
      <v-card-text>
        <v-carousel
          :model-value="activeImg"
          touch
          hide-delimiter-background
          :show-arrows="galleryContent.length > 1"
          color="primary"
          progress="primary"
          height="800"
        >
          <v-carousel-item
            v-for="(item, i) in galleryContent"
            :key="i"
            :value="item"
            :src="item"
            @update:model-value="currentImgIndex = i"
          />
        </v-carousel>
      </v-card-text>
    </v-sheet>
    <ConfirmDialog
      v-model="showConfirmDialog"
      :text="$t('general.img_delete_warning')"
      @cancel="showConfirmDialog = false"
      @confirm="handleConfirm"
    />
  </v-dialog>
</template>

<script lang="ts" setup>
import CloseBtn from "../Containment/Btns/CloseBtn.vue";
import { useAuthStore } from "~/stores/authStore";
import ConfirmDialog from "../Dialogs/ConfirmDialog.vue";
const emits = defineEmits<{
  (event: "close"): void;
  (event: "avatar:set" | "cover:set" | "delete:img", index: number): void;
}>();
defineProps<{
  showGallery: boolean;
  galleryContent: string[] | string;
  noContentLabel?: string;
  name?: string;
  activeImg: number;
  withAvatar: boolean;
}>();

const { isAuthenticated } = storeToRefs(useAuthStore());

const currentImgIndex = ref<number>(0);
const showConfirmDialog = ref<boolean>(false);

const handleConfirm = () => {
  emits("delete:img", currentImgIndex.value + 1);
  showConfirmDialog.value = false;
};
</script>

<style></style>
