<template>
  <div>
    <Head>
      <Title>{{ definePageTitle($t("pages.profile.title")) }}</Title>
    </Head>
    <v-sheet max-width="1200" class="mx-auto" rounded="lg">
      <DetailCard
        display-avatar
        :page-name="$t('pages.profile.title')"
        :cover="currentUser?.cover || ''"
        :loading="loading"
        :is-auth="isAuthenticated"
      >
        <template #menu>
          <v-menu location="bottom end">
            <template #activator="{ props }">
              <v-btn icon v-bind="props">
                <v-icon>mdi-dots-vertical</v-icon>
              </v-btn>
            </template>
            <v-list density="compact">
              <v-list-item
                :title="$t('actions.choose_cover')"
                prepend-icon="mdi-image"
                value="cover"
                @click="showCoverChooseDialog = true"
              />
              <v-list-item
                :title="$t('actions.edit_avatar')"
                prepend-icon="mdi-account"
                value="avatar"
                @click="showAvatarUploadDialog = true"
              />
              <v-list-item
                :title="$t('actions.edit')"
                prepend-icon="mdi-pencil"
                value="edit"
                @click="handleEdit"
              />
              <v-list-item
                :title="$t('auth.sign_out')"
                prepend-icon="mdi-logout"
                value="logout"
                @click="showConfirmLogoutDialog = true"
              />
            </v-list>
          </v-menu>
        </template>
        <template #general_info>
          <TopInfo
            :loading="loading"
            :general-info="computedGeneralInfo"
            :avatar="currentUser?.avatar || ''"
            :title="currentUser?.displayName || ''"
            :subtitle="computedLastLogin"
          />
        </template>
      </DetailCard>
    </v-sheet>

    <ConfirmDialog
      v-model="showAvatarWarning"
      :text="$t('general.img_replacement_warning')"
      @cancel="showAvatarWarning = false"
      @confirm="replaceAvatar"
    />
    <ConfirmDialog
      v-model="showCoverWarning"
      :text="$t('general.img_replacement_warning')"
      @cancel="showCoverWarning = false"
      @confirm="replaceCover"
    />
    <BaseDialog
      v-model:opened="showAvatarUploadDialog"
      :title="
        currentUser?.avatar
          ? $t('pages.profile.change_avatar')
          : $t('pages.profile.upload_avatar')
      "
      :loading="loading"
      @close="showAvatarUploadDialog = false"
    >
      >
      <template #text>
        <GalleryUploader
          :upload-count="1"
          :upload-error="false"
          @files:upload="handleUploadAvatar"
        />
      </template>
    </BaseDialog>
    <BaseDialog
      v-model:opened="showCoverChooseDialog"
      :title="
        currentUser?.cover
          ? $t('pages.profile.change_cover')
          : $t('pages.profile.upload_cover')
      "
      :loading="loading"
      @close="showCoverChooseDialog = false"
    >
      >
      <template #text>
        <GalleryUploader
          :upload-count="1"
          :upload-error="false"
          @files:upload="handleUploadCover"
        />
      </template>
    </BaseDialog>
    <ConfirmDialog
      v-model="showConfirmLogoutDialog"
      :text="$t('auth.logout_confirm')"
      @cancel="showConfirmLogoutDialog = false"
      @confirm="handleSignOut"
    />
    <BaseDialog
      v-model:opened="editMode"
      :loading="loading"
      :title="$t('pages.profile.edit')"
      @close="editMode = false"
    >
      <template #text>
        <v-card>
          <v-card-text>
            <UserForm :is-new="false" @cancel="editMode = false" />
          </v-card-text>
          <v-card-actions>
            <v-btn
              prepend-icon="mdi-check"
              color="primary"
              @click="submitEdit"
              >{{ $t("actions.submit") }}</v-btn
            >
          </v-card-actions>
        </v-card>
      </template>
      ></BaseDialog
    >
  </div>
</template>

<script lang="ts" setup>
import { useAuthStore } from "~/stores/authStore";

import DetailCard from "~/components/Containment/Cards/DetailCard.vue";
import ConfirmDialog from "~/components/Dialogs/ConfirmDialog.vue";
import BaseDialog from "~/components/Dialogs/BaseDialog.vue";
import GalleryUploader from "~/components/Gallery/GalleryUploader.vue";
import UserForm from "~/components/Forms/UserForm.vue";
import TopInfo from "~/components/Containment/Cards/partials/TopInfo.vue";
import definePageTitle from "~/utils/definePageTitle";

const { t, locale } = useI18n();
const { currentUser, loading, userForm } = storeToRefs(useAuthStore());
const showConfirmLogoutDialog = ref<boolean>(false);
const showCoverChooseDialog = ref<boolean>(false);
const showAvatarWarning = ref<boolean>(false);
const showCoverWarning = ref<boolean>(false);
const showAvatarUploadDialog = ref<boolean>(false);
const editMode = ref<boolean>(false);
const avatarFile = ref<File>();
const coverFile = ref<File>();
const computedLastLogin = computed((): string => {
  const lastLogin = new Date(
    currentUser.value?.lastLogin || 0
  ).toLocaleString();
  return t("pages.profile.last_login", { time: lastLogin });
});

const {
  signOut,
  uploadAvatar,
  uploadCover,
  fetchCurrentUser,
  editUser,
  isAuthenticated,
} = useAuthStore();

const handleSignOut = async (): Promise<void> => {
  await signOut();
  showConfirmLogoutDialog.value = false;
  navigateTo("/");
};
const handleUploadCover = async (files: File[]) => {
  const file = files[0];
  const id = Number(currentUser.value?.id);
  if (file) {
    if (!currentUser.value?.cover) {
      await uploadCover(file, id || 0);
    } else {
      showCoverReplacementWarning(file);
    }
  }
  showCoverChooseDialog.value = false;
};

const showAvatarReplacementWarning = (file: File): void => {
  showAvatarWarning.value = true;
  avatarFile.value = file;
};

const showCoverReplacementWarning = (file: File): void => {
  showCoverWarning.value = true;
  coverFile.value = file;
};

const handleUploadAvatar = async (files: File[]): Promise<void> => {
  const file = files[0];
  const id = Number(currentUser.value?.id);
  if (file) {
    if (!currentUser.value?.avatar) {
      await uploadAvatar(file, id || 0);
    } else {
      showAvatarReplacementWarning(file);
    }
  }
  showAvatarUploadDialog.value = false;
};

const handleEdit = () => {
  editMode.value = true;
  userForm.value = { ...currentUser.value };
};

const submitEdit = async () => {
  await editUser();
  editMode.value = false;
};

const replaceAvatar = async () => {
  const id = Number(currentUser.value?.id);
  await uploadAvatar(avatarFile.value as File, id || 0);
  await fetchCurrentUser();
  showAvatarWarning.value = false;
};

const replaceCover = async () => {
  const id = Number(currentUser.value?.id);
  await uploadCover(coverFile.value as File, id || 0);
  await fetchCurrentUser();
  showCoverWarning.value = false;
};

const computedGeneralInfo = computed((): Detail[] => {
  return [
    {
      title: t("auth.email"),
      value: currentUser.value?.email || "",
      icon: "mdi-email",
    },
    {
      title: t("forms.person.age"),
      value:
        locale.value === "ru"
          ? declineInRussian(currentUser.value?.age || 0, [
              "год",
              "года",
              "лет",
            ])
          : currentUser.value?.age + " " + t("general.years_old") || "",
      icon: "mdi-cake",
    },
  ];
});

definePageMeta({
  title: "Profile",
  middleware: ["auth"],
});
</script>

<style></style>
