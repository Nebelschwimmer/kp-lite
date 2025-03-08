<template>
  <v-card>
    <v-card-text>
      <v-form ref="formRef" @submit.prevent>
        <v-text-field
          v-model="form.name"
          name="name"
          :rules="nameRules"
          :label="$t('forms.film.name')"
          prepend-inner-icon="mdi-movie-play"
          @update:model-value="handleUpdateModelValue"
        />
        <v-text-field
          v-model="form.slogan"
          name="slogan"
          :label="$t('forms.film.slogan')"
          prepend-inner-icon="mdi-format-quote-close"
          @update:model-value="handleUpdateModelValue"
        />
        <v-combobox
          v-model.number="form.releaseYear"
          name="releaseYear"
          :rules="releaseYearRules"
          prepend-inner-icon="mdi-calendar"
          :label="$t('forms.film.release_year')"
          :items="yearsOfReleaseItems"
          @update:model-value="handleUpdateModelValue"
        />
        <v-select
          v-model="form.genreIds"
          name="genreIds"
          multiple
          :items="genres"
          :rules="multipleSelectRules"
          :label="$t('forms.film.genres')"
          item-value="value"
          item-title="name"
          prepend-inner-icon="mdi-filmstrip"
          @update:model-value="handleUpdateModelValue"
        />
        <v-select
          v-model.number="form.age"
          name="age"
          :rules="ageRules"
          prepend-inner-icon="mdi-cake-variant"
          :label="$t('forms.film.age')"
          :items="ageItems"
          @update:model-value="handleUpdateModelValue"
        />
        <v-text-field
          v-model="form.duration"
          :label="$t('forms.film.duration')"
          type="time"
          name="duration"
          prepend-inner-icon="mdi-timer"
          :rules="durationRules"
          @update:model-value="handleUpdateModelValue"
        />
        <v-select
          v-model="form.actorIds"
          :loading="loading"
          name="actorIds"
          multiple
          :items="actors"
          item-value="id"
          item-title="name"
          :rules="multipleSelectRules"
          :label="$t('forms.film.actors')"
          prepend-inner-icon="mdi-account"
          @update:model-value="handleUpdateModelValue"
        />
        <v-select
          v-model.number="form.directorId"
          name="directorId"
          :loading="loading"
          item-value="id"
          item-title="name"
          :items="directors"
          :rules="selectRules"
          :label="$t('forms.film.director')"
          prepend-inner-icon="mdi-account"
          @update:model-value="handleUpdateModelValue"
        />
        <v-select
          v-model.number="form.producerId"
          name="producerId"
          item-value="id"
          :loading="loading"
          item-title="name"
          :items="directors"
          :rules="selectRules"
          :label="$t('forms.film.producer')"
          prepend-inner-icon="mdi-account"
          @update:model-value="handleUpdateModelValue"
        />
        <v-select
          v-model.number="form.writerId"
          name="writerId"
          item-value="id"
          :loading="loading"
          item-title="name"
          :items="writers"
          :rules="selectRules"
          :label="$t('forms.film.writer')"
          prepend-inner-icon="mdi-account"
          @update:model-value="handleUpdateModelValue"
        />
        <v-select
          v-model.number="form.composerId"
          name="composerId"
          item-value="id"
          :loading="loading"
          item-title="name"
          :rules="selectRules"
          :items="composers"
          :label="$t('forms.film.composer')"
          prepend-inner-icon="mdi-account"
          @update:model-value="handleUpdateModelValue"
        />

        <v-textarea
          v-if="showDescription"
          v-model="form.description"
          :label="$t('forms.film.description')"
          name="description"
          density="compact"
          prepend-inner-icon="mdi-pencil"
          variant="filled"
          auto-grow
          rows="3"
          no-resize
          :rules="descriptionRules"
          @update:model-value="handleUpdateModelValue"
        />
      </v-form>
    </v-card-text>

    <v-card-actions>
      <v-spacer />
      <SubmitBtn
        :loading="Boolean(loading)"
        @click="handleValidationAndSubmit"
      />
    </v-card-actions>
  </v-card>
</template>

<script lang="ts" setup>
import SubmitBtn from "../Containment/Btns/SubmitBtn.vue";

const { t } = useI18n();
const formRef = ref<HTMLFormElement | null>(null);
const emit = defineEmits<{
  (event: "form:submit"): void;
  (event: "update:modelValue", value: Partial<IFilm>): void;
}>();

const props = defineProps<{
  filmForm: Partial<IFilm>;
  showDescription?: boolean;
  genres: IGenre[];
  actors: Partial<IPerson>[];
  producers: Partial<IPerson>[];
  writers: Partial<IPerson>[];
  composers: Partial<IPerson>[];
  directors: Partial<IPerson>[];
  loading?: boolean;
}>();

const form = ref<Partial<IFilm>>({ ...props.filmForm });

const isFormValid = ref(true);

const currentYear = new Date().getFullYear();
const startYear = 1900;
const releaseYearRules = [
  (v: number) => !!v || t("forms.rules.required"),
  (v: number) => typeof v === "number" || t("forms.rules.type_num"),
  (v: number) => v >= startYear || t("forms.rules.min_year") + " " + startYear,
  (v: number) =>
    v <= currentYear || t("forms.rules.max_year") + " " + currentYear,
];

const yearsOfReleaseItems = Array.from(
  { length: currentYear - startYear + 1 },
  (_, index) => startYear + index
);
const MAX_NAME_LENGHT = 50;
const MIN_NAME_LENGHT = 2;
const nameRules = [
  (v: string) => !!v || t("forms.rules.required"),
  (v: string) =>
    v.length <= MAX_NAME_LENGHT ||
    t("forms.rules.max_chars") + " " + MAX_NAME_LENGHT,
  (v: string) =>
    v.length >= MIN_NAME_LENGHT ||
    t("forms.rules.min_chars") + " " + MIN_NAME_LENGHT,
];
const selectRules = [(v: string) => !!v || t("forms.rules.required")];
const durationRules = [(v: string) => !!v || t("forms.rules.required")];
const descriptionRules = [(v: string) => !!v || t("forms.rules.required")];
const multipleSelectRules = [(v: string) => !!v || t("forms.rules.required")];
const ageRules = [(v: number) => !!v || t("forms.rules.required")];
const ageItems = [0, 3, 12, 16, 18];
const validate = async (): Promise<void> => {
  if (!formRef.value) return;
  const { valid } = await formRef.value.validate();
  if (!valid) {
    isFormValid.value = false;
  } else {
    isFormValid.value = true;
  }

};

const handleUpdateModelValue = (): void =>
  emit("update:modelValue", form.value);

const handleValidationAndSubmit = async (): Promise<void> => {
  await validate();
  if (isFormValid.value) {
    emit("form:submit");
  }
};

watch(
  (): Partial<IFilm> => props.filmForm,
  (): void => {
    form.value = { ...props.filmForm };
  },
  {
    deep: true,
  }
);
</script>

<style lang="scss"></style>
