<template>
  <div class="position-relative">
    <v-sheet min-height="260">
      <div class="d-flex justify-center">
        <div class="position-absolute text-center" style="top: -50%">
          <div class="position-relative">
            <v-avatar
              size="250"
              border
              class="position-relative cursor-pointer"
              @click="showAvatarEditBtns = !showAvatarEditBtns"
            >
              <v-img :src="avatar || ''" cover>
                <template #placeholder>
                  <ErrorPlaceHolder />
                </template>
                <template #error>
                  <v-sheet height="100%">
                    <div class="d-flex align-center justify-center h-100">
                      <v-icon color="error" icon="mdi-image-broken" />
                    </div>
                  </v-sheet>
                </template>
              </v-img>
            </v-avatar>
            <v-fab
              :active="showAvatarEditBtns"
              :disabled="!isAuthenticated"
              absolute
              color="secondary"
              location="bottom end"
              icon="mdi-pencil"
              @click="handleEditAvatar"
            />
          </div>
          <template v-if="!loading">
            <div class="d-flex flex-column ga-1">
              <span class="text-h6 text-lg-h4 font-weight-bold text-primary">
                {{ title }}</span
              >
              <div class="text-caption text-lg-subtitle-1">
                {{ generalInfo?.map((item: Detail) => item.value).join(", ") }}
              </div>
              <div class="text-capitalize text-caption text-lg-subtitle-1">
                {{ subtitle }}
              </div>
            </div>
          </template>
          <template v-else>
            <v-skeleton-loader
              class="stained-glass"
              type="list-item-three-line"
            />
          </template>
        </div>
      </div>
    </v-sheet>
  </div>
</template>

<script lang="ts" setup>
import { useAuthStore } from "~/stores/authStore";
import ErrorPlaceHolder from "../../Img/ErrorPlaceHolder.vue";

defineProps<{
  title: string | Detail[];
  subtitle: string | Detail[];
  generalInfo: Detail[];
  avatar: string;
  loading: boolean;
}>();

const { isAuthenticated } = storeToRefs(useAuthStore());

const showAvatarEditBtns = ref<boolean>(false);
const emit = defineEmits<{
  (event: "avatar:edit"): void;
}>();

const handleEditAvatar = () => {
  emit("avatar:edit");
  showAvatarEditBtns.value = false;
};
</script>

<style></style>
