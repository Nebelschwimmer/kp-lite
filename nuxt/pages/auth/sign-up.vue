<template>
  <div>
	<Head>
      <Title>{{ definePageTitle($t("auth.sign_up")) }}</Title>
    </Head>
    <v-card class="pa-4 mx-auto" style="max-width: 600px" variant="outlined">
      <v-toolbar>
        <template #prepend>
          <BackBtn />
        </template>
        <v-toolbar-title> {{ $t("auth.register") }}</v-toolbar-title>
      </v-toolbar>

      <div class="mt-4">
        <UserForm :is-new="true" />
      </div>
      <div class="d-flex flex-column ga-4 mt-5">
        <v-btn
          color="primary"
          variant="tonal"
          size="large"
          :loading="loading"
          block
          @click="submit"
        >
          {{ $t("auth.sign_up") }}
        </v-btn>

        <v-btn
          prepend-icon="mdi-account-off"
          color="secondary"
          variant="plain"
          size="large"
          block
          to="/"
        >
          {{ $t("auth.continue_as_guest") }}
        </v-btn>
      </div>
    </v-card>

    <v-snackbar
      v-model="showErrorMessage"
      color="error"
      :text="authError?.message || ''"
    />
  </div>
</template>

<script lang="ts" setup>
import BackBtn from "~/components/Containment/Btns/BackBtn.vue";
import UserForm from "~/components/Forms/UserForm.vue";
import { useAuthStore } from "~/stores/authStore";

interface AuthError {
  message: string;
}

const { loading, authError, showErrorMessage } = storeToRefs(
  useAuthStore()
) as {
  loading: Ref<boolean>;
  authError: Ref<AuthError | null>;
  showErrorMessage: Ref<boolean>;
};

const { register } = useAuthStore();

const submit = async () => {
  await register();
  if (!showErrorMessage.value) {
    navigateTo("/auth/sign-in");
  }
};
definePageMeta({
  name: "signUp",
  path: "/auth/sign-up",
});
</script>

<style></style>
