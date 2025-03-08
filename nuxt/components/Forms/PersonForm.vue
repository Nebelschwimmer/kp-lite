<template>
  <v-card variant="text" flat>
    <v-card-text>
      <v-form ref="formRef">
        <v-text-field
          v-model="form.firstname"
          name="firstname"
          prepend-inner-icon="mdi-account"
          :rules="nameRules"
          :label="$t('forms.person.firstname')"
          @update:model-value="handleUpdateModelValue"
        />
        <v-text-field
          v-model="form.lastname"
          name="lastname"
          prepend-inner-icon="mdi-account"
          :rules="nameRules"
          :label="$t('forms.person.lastname')"
          @update:model-value="handleUpdateModelValue"
        />
        <v-select
          v-model="form.genderId"
          name="genderId"
          item-value="value"
          item-title="name"
          prepend-inner-icon="
  		mdi-gender-male-female
  		"
          :items="genders"
          :rules="selectRules"
          :hint="$t('forms.person.gender_hint')"
          :label="$t('forms.person.gender')"
          @update:model-value="handleUpdateModelValue"
        />
        <v-select
          v-model="form.specialtyIds"
          name="specialtyIds"
          multiple
          item-value="value"
          item-title="name"
          prepend-inner-icon="mdi-account-group"
          :items="specialties"
          :rules="multipleSelectRules"
          :label="$t('forms.person.specialties')"
          :hint="$t('forms.person.specialty_hint')"
          @update:model-value="handleUpdateModelValue"
        />
        <v-text-field
          v-model="form.birthday"
          name="birthday"
          type="date"
          prepend-inner-icon="mdi-calendar"
          :rules="birthdayRules"
          :label="$t('forms.person.birthday')"
          @update:model-value="handleUpdateModelValue"
        />
        <v-textarea
          v-model="form.bio"
          name="bio"
          density="compact"
          prepend-inner-icon="mdi-pencil"
          variant="filled"
          auto-grow
          rows="3"
          counter
          no-resize
          :label="$t('forms.person.bio')"
          :rules="bioRules"
          @update:model-value="handleUpdateModelValue"
        />
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-spacer />
      <SubmitBtn :loading="loading" @click:submit="validateAndSubmit" />
    </v-card-actions>
  </v-card>
</template>

<script lang="ts" setup>
import SubmitBtn from "../Containment/Btns/SubmitBtn.vue";
const { t } = useI18n();
const formRef = ref<HTMLFormElement>();
const emit = defineEmits<{
  (event: "form:submit"): void;
  (event: "update:modelValue", value: Partial<IPerson>): void;
}>();

const props = defineProps<{
  personForm: Partial<IPerson>;
  genders: IGender[];
  specialties: ISpecialty[];
  loading: boolean;
}>();
const isFormValid = ref<boolean>(false);
const form = ref<Partial<IPerson>>({ ...props.personForm });

const MAX_NAME_LENGHT: number = 50;
const MIN_NAME_LENGHT: number = 2;
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

const date = useDate();
const MIN_BIRTHDAY: Date = new Date(1900, 0, 1);
const MINAGE: number = 7;
const currentYear: number = new Date().getFullYear();
const MAX_BIRTHDAY: Date = new Date(currentYear - MINAGE, 0, 1);
const bioRules = [(v: string) => !!v || t("forms.rules.required")];
const birthdayRules = [
  (v: string) => !!v || t("forms.rules.required"),
  (v: string) =>
    new Date(v) >= MIN_BIRTHDAY ||
    t("forms.rules.min_birthday", MINAGE) + " - " + date.getYear(MIN_BIRTHDAY),
  (v: string) =>
    new Date(v) <= MAX_BIRTHDAY ||
    t("forms.rules.max_birthday") + " - " + date.getYear(MAX_BIRTHDAY),
];

const multipleSelectRules = [
  (v: string) => v?.length > 0 || t("forms.rules.required"),
];

const handleUpdateModelValue = (): void =>
  emit("update:modelValue", form.value);

const validate = async (): Promise<void> => {
  if (!formRef.value) return;
  const { valid } = await formRef.value.validate();
  if (!valid) {
    isFormValid.value = false;
  } else {
    isFormValid.value = true;
  }
};
const validateAndSubmit = async (): Promise<void> => {
  await validate();
  if (isFormValid.value) {
    emit("form:submit");
  }
};
watch(
  (): Partial<IPerson> => props.personForm,
  (): void => {
    form.value = { ...props.personForm };
  },
  {
    deep: true,
  }
);
</script>
