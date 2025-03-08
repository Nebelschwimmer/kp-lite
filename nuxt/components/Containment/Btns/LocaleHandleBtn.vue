<template>
  <v-btn stacked>
    <v-badge color="transparent" :content="computedBadgeFlag">
      <v-icon>mdi-translate</v-icon>
    </v-badge>
    <v-menu activator="parent">
      <v-list>
        <v-list-item
          v-for="(item, index) in languageOptions"
          :key="index"
          :active="item.value === locale"
          :color="item.value === locale ? 'primary' : ''"
          :value="item"
          :title="item.title"
          @click="changeLanguage(item.value)"
        />
      </v-list>
    </v-menu>
  </v-btn>
</template>

<script lang="ts" setup>
const { setLocale, locale } = useI18n();
const changeLanguage = (lang: Language) => {
  setLocale(lang);
};
interface LanguageOptions {
  title: string;
  value: Language;
  flag: string;
}
const languageOptions = [
  {
    title: "🇷🇺 Русский",
    value: "ru",
    flag: "🇷🇺",
  },
  {
    title: "🇬🇧 English",
    value: "en",
    flag: "🇬🇧",
  },
  {
    title: "🇫🇷 Français",
    value: "fr",
    flag: "🇫🇷",
  },
] as LanguageOptions[];

const computedBadgeFlag = computed((): string => {
  return (
    languageOptions.find((item) => item.value === locale.value)?.flag || ""
  );
});
</script>

<style></style>
