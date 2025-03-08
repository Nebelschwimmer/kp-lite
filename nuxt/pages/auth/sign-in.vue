<template>
  <div>
    <Head>
      <Title>{{ definePageTitle($t("auth.sign_in")) }}</Title>
    </Head>
    <v-card class="mt-15 mx-auto" style="max-width: 600px">
      <v-toolbar>
        <template #prepend>
          <BackBtn />
        </template>
        <v-toolbar-title>
          {{ $t("auth.sign_in") }}
        </v-toolbar-title>
      </v-toolbar>

      <v-card-text>
        <v-form ref="loginFormRef" class="mt-4">
          <div class="d-flex flex-column ga-2">
            <v-text-field
              v-model="userForm.username"
              :label="$t('auth.login')"
              :placeholder="$t('auth.login_placeholder')"
              prepend-inner-icon="mdi-account-key"
              variant="outlined"
              :rules="requiredRules"
            />
            <v-text-field
              v-model="userForm.password"
              :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
              :type="visible ? 'text' : 'password'"
              :label="$t('auth.password')"
              :placeholder="$t('auth.password_placeholder')"
              prepend-inner-icon="mdi-lock-outline"
              variant="outlined"
              :rules="requiredRules"
              @click:append-inner="visible = !visible"
            />
          </div>
        </v-form>
        <div class="d-flex flex-column ga-4 mt-5">
          <v-btn
            color="primary"
            size="large"
            variant="tonal"
            block
            :loading="loading"
            @click="validate"
          >
            {{ $t("auth.sign_in") }}
          </v-btn>
          <v-btn
            to="/auth/sign-up"
            variant="outlined"
            block
            size="large"
            color="secondary"
            prepend-icon="mdi-account-plus"
          >
            {{ $t("auth.register") }}
          </v-btn>

          <v-btn
            prepend-icon="mdi-account-off"
            variant="plain"
            block
            size="large"
            to="/"
          >
            {{ $t("auth.continue_as_guest") }}
          </v-btn>
        </div>
      </v-card-text>
    </v-card>
    <v-snackbar
      v-model="showErrorMessage"
      color="error"
      :text="$t('auth.invalid_credentials')"
    />
  </div>
</template>

<script lang="ts" setup>
import BackBtn from "~/components/Containment/Btns/BackBtn.vue";
import { useAuthStore } from "~/stores/authStore";

const { userForm, loading, showErrorMessage } = storeToRefs(useAuthStore());
const { t } = useI18n();
const loginFormRef = ref();
const { login, fetchCurrentUser } = useAuthStore();
const requiredRules = [
  (value: string) => {
    if (value) return true;
    return t("forms.rules.required");
  },
];
const validate = async () => {
  const { valid } = await loginFormRef.value.validate();
  if (valid) {
    await login();
    if (!showErrorMessage.value) {
      await fetchCurrentUser();
      navigateTo("/");
    }
  }
};

definePageMeta({
  name: "signIn",
  path: "/auth/sign-in",
  middleware: "auth",
});

const visible = ref(false);
</script>
