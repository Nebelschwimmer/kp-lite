<template>
  <div>
    <v-btn
      v-if="!isAuthenticated && $vuetify.display.mdAndUp"
      prepend-icon="mdi-login"
      variant="tonal"
      :to="localeRoute('/auth/sign-in')"
    >
      {{ $t("auth.sign_in") }}
    </v-btn>
    <template v-else>
      <v-list-item
        v-if="$vuetify.display.mdAndUp"
        density="compact"
        rounded="lg"
        lines="one"
        :title="currentUser?.displayName || currentUser?.username || ''"
        :subtitle="currentUser?.email"
        :to="localeRoute('/profile')"
      >
        <template #prepend>
          <v-avatar v-if="isAuthenticated">
            <v-img
              v-if="currentUser?.avatar"
              :src="currentUser?.avatar || ''"
              @click="navigateTo(localeRoute('/profile'))"
            >
              <template #error>
                <v-avatar border>
                  <v-icon color="error" size="x-small">mdi-image-broken</v-icon>
                </v-avatar>
              </template>
            </v-img>
            <v-icon v-else @click="navigateTo(localeRoute('/profile'))">
              mdi-account</v-icon
            >
          </v-avatar>
        </template>
      </v-list-item>
      <v-avatar v-else class="cursor-pointer">
        <v-img
          v-if="currentUser?.avatar"
          :src="currentUser?.avatar || ''"
          @click="navigateTo(localeRoute('/profile'))"
        >
          <template #error>
            <v-avatar border>
              <v-icon color="error" size="x-small">mdi-image-broken</v-icon>
            </v-avatar>
          </template>
        </v-img>
        <v-icon v-else @click="navigateTo(localeRoute('/profile'))">
          mdi-account</v-icon
        >
      </v-avatar>
    </template>
    <ConfirmDialog
      v-model="showConfirmDialog"
      :text="$t('auth.logout_confirm')"
      @cancel="showConfirmDialog = false"
      @confirm="handleSignOut"
    />
  </div>
</template>

<script lang="ts" setup>
import { useAuthStore } from "~/stores/authStore";
import ConfirmDialog from "../Dialogs/ConfirmDialog.vue";

const { currentUser, isAuthenticated } = storeToRefs(useAuthStore());
const { signOut, fetchCurrentUser } = useAuthStore();

const showConfirmDialog = ref<boolean>(false);
const localeRoute = useLocaleRoute();
const handleSignOut = async (): Promise<void> => {
  await signOut();
  showConfirmDialog.value = false;
};

watch(isAuthenticated, () => {
  if (!isAuthenticated.value) {
    fetchCurrentUser();
  }
});

</script>
