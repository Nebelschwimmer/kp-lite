<template>
  <div>
    <Head>
      <Title>{{ definePageTitle($t("forms.person.add")) }}</Title>
    </Head>
    <v-card class="mx-auto" style="max-width: 800px" :loading="loading">
      <v-toolbar :title="$t('forms.person.add')">
        <template #prepend>
          <BackBtn />
        </template>
      </v-toolbar>

      <v-stepper v-model="step" :mobile="!$vuetify.display.mdAndUp">
        <v-stepper-header>
          <v-stepper-item
            value="1"
            :title="$t('stepper.general')"
            :complete="step === 1"
            :color="step === 1 ? 'success' : 'primary'"
          />

          <v-divider />
          <v-stepper-item
            value="2"
            :title="$t('stepper.gallery')"
            :complete="step === 2"
            :color="step === 2 ? 'success' : 'primary'"
          />

          <v-divider />
          <v-stepper-item
            value="3"
            :title="$t('stepper.avatar')"
            :complete="step === 3"
            :color="step === 3 ? 'success' : 'primary'"
          />
          <v-divider />
          <v-stepper-item
            value="4"
            color="primary"
            :title="$t('stepper.cover')"
          />
        </v-stepper-header>
        <v-stepper-window>
          <v-stepper-window-item value="1">
            <PersonForm
              :loading="loading"
              :person-form="personForm"
              :genders="genders"
              :specialties="specialties"
              @form:submit="handleGeneralInfoSubmit"
              @update:model-value="personForm = $event"
            />
          </v-stepper-window-item>
          <v-stepper-window-item value="2">
            <GalleryUploader
              :upload-count="GALLERY_SIZE"
              @files:upload="handlePhotoSubmit"
            />
          </v-stepper-window-item>
          <v-stepper-window-item value="3">
            <SingleImgSelector
              :gallery="personForm.photos || []"
              @img:select="handleAvatarSubmit"
            />
          </v-stepper-window-item>
          <v-stepper-window-item value="4">
            <GalleryUploader
              :upload-count="1"
              @files:upload="handleCoverSubmit"
            />
          </v-stepper-window-item>
          <v-btn :disabled="step === 0" @click="handleFinish">{{
            $t("actions.finish")
          }}</v-btn>
        </v-stepper-window>
      </v-stepper>
    </v-card>
    <v-snackbar v-model="showFirstStepSnackbar" color="success">
      {{ $t("toast.messages.success.add") }}
    </v-snackbar>
    <v-snackbar v-model="showSecondStepSnackbar" color="success">
      {{ $t("toast.messages.success.files_added") }}
    </v-snackbar>
    <v-snackbar v-model="showThirdStepSnackbar" color="success">
      {{ $t("toast.messages.success.edit") }}
    </v-snackbar>
    <v-snackbar v-model="showErrorSnackbar" color="error">
      {{ $t("toast.messages.error.add") }}
    </v-snackbar>
  </div>
</template>

<script lang="ts" setup>
import { usePersonStore } from "~/stores/personStore";
import PersonForm from "~/components/Forms/PersonForm.vue";
import GalleryUploader from "~/components/Gallery/GalleryUploader.vue";
import SingleImgSelector from "~/components/Gallery/Partials/SingleImgSelector.vue";
import BackBtn from "~/components/Containment/Btns/BackBtn.vue";
import definePageTitle from "~/utils/definePageTitle";

const { locale } = useI18n();
const localeRoute = useLocaleRoute();

const step = ref<number>(0);
const showFirstStepSnackbar = ref<boolean>(false);
const showSecondStepSnackbar = ref<boolean>(false);
const showThirdStepSnackbar = ref<boolean>(false);
const showErrorSnackbar = ref<boolean>(false);

const { loading, personForm, genders, specialties, networkError } =
  storeToRefs(usePersonStore());
const nextStep = () => {
  if (!networkError.value) {
    step.value++;
    switch (step.value) {
      case 1:
        showFirstStepSnackbar.value = true;
        break;
      case 2:
        showSecondStepSnackbar.value = true;
        break;
      case 3:
        showThirdStepSnackbar.value = true;
        break;
    }
  }
};
const {
  fetchGenders,
  fetchSpecialties,
  addPerson,
  editPerson,
  uploadPhotos,
  clearPersonForm,
  uploadCover,
  GALLERY_SIZE,
} = usePersonStore();

const handleGeneralInfoSubmit = async (): Promise<void> => {
  if (await addPerson()) {
    nextStep();
  } else if (networkError.value) {
    showErrorSnackbar.value = true;
  }
};

const handlePhotoSubmit = async (files: File[]): Promise<void> => {
  const id = personForm.value.id;
  if (id) {
    await uploadPhotos(files, id || 0);
    nextStep();
  } else if (networkError.value) {
    showErrorSnackbar.value = true;
  }
};

const handleFinish = (): void => {
  if (step.value <= 2) {
    navigateTo(localeRoute(`/persons`));
  } else {
    navigateTo(localeRoute(`/persons/${personForm.value.id}`));
  }
};
const handleCoverSubmit = async (files: File[]): Promise<void> => {
  const coverFile = files[0];
  const id = personForm.value.id;
  await uploadCover(coverFile, id || 0);
  if (networkError.value) {
    showErrorSnackbar.value = true;
  } else {
    navigateTo(localeRoute(`/persons/${personForm.value.id}`));
  }
};

const handleAvatarSubmit = async (index: number): Promise<void> => {
  personForm.value.avatar = personForm.value?.photos?.length
    ? personForm.value?.photos[index - 1]
    : "";
  await editPerson();
  if (networkError.value) {
    showErrorSnackbar.value = true;
  }
  nextStep();
};

const fetchData = async (): Promise<void> => {
  await Promise.allSettled([
    fetchGenders(locale.value),
    fetchSpecialties(locale.value),
  ]);
};

onMounted(async (): Promise<void> => {
  clearPersonForm();
  await fetchData();
  showFirstStepSnackbar.value = false;
  showSecondStepSnackbar.value = false;
  showThirdStepSnackbar.value = false;
});

definePageMeta({
  name: "newPerson",
  path: "/persons/new",
  middleware: ["auth"],
});
</script>

<style></style>
