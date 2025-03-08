<template>
  <v-list nav>
    <v-list-subheader> {{ $t("nav.title") }}</v-list-subheader>
    <v-divider />
    <v-list-item
      :active="activeRoute === '/'"
      :title="$t('nav.home')"
      prepend-icon="mdi-home"
      value="home"
      :to="localeRoute('/')"
    />
    <v-list-group>
      <template #activator="{ props }">
        <v-list-item
          v-bind="props"
          value="films_group"
          :active="activeRoute.startsWith('/films')"
          :base-color="activeRoute.startsWith('/films') ? 'primary' : ''"
          prepend-icon="mdi-filmstrip"
          :title="$t('nav.films')"
        />
      </template>
      <v-list-item
        prepend-icon="mdi-list-box"
        value="films_list"
        :base-color="activeRoute === '/films' ? 'primary' : ''"
        :active="activeRoute === '/films'"
        :title="$t('nav.films_list')"
        :to="localeRoute('/films')"
      />
      <v-list-item
        prepend-icon="mdi-plus"
        value="films_new"
        :base-color="activeRoute === '/films/new' ? 'primary' : ''"
        :active="activeRoute === '/films/new'"
        :title="$t('nav.films_add')"
        :to="localeRoute('/films/new')"
      />
    </v-list-group>
    <v-list-group>
      <template #activator="{ props }">
        <v-list-item
          v-bind="props"
          value="persons_group"
          :active="activeRoute.startsWith('/persons')"
          :base-color="activeRoute.startsWith('/persons') ? 'primary' : ''"
          prepend-icon="mdi-account-group"
          :title="$t('nav.persons')"
        />
      </template>
      <v-list-item
        prepend-icon="mdi-list-box"
        value="persons_list"
        :active="activeRoute === '/persons'"
        :base-color="activeRoute === '/persons' ? 'primary' : ''"
        :title="$t('nav.persons_list')"
      :to="localeRoute('/persons')"
      />
      <v-list-item
        prepend-icon="mdi-plus"
        value="persons_new"
        :active="activeRoute === '/persons/new'"
        :base-color="activeRoute === '/persons/new' ? 'primary' : ''"
        :title="$t('nav.persons_add')"
        :to="localeRoute('/persons/new')"
      />
    </v-list-group>

    <template v-if="$vuetify.display.smAndDown">
      <v-divider />
      <v-list-subheader> {{ $t("nav.settings") }}</v-list-subheader>

      <v-select
        v-model="locale"
        :label="$t('nav.language')"
        :items="languageOptions"
        :value="locale"
        density="compact"
        @update:model-value="changeLanguage"
      />
    </template>
  </v-list>
</template>

<script lang="ts" setup>
const { setLocale, locale } = useI18n();
const route = useRoute();
const activeRoute = computed((): string => route.path);

const changeLanguage = (lang: Language) => {
  setLocale(lang);
};

const localeRoute = useLocaleRoute();

const languageOptions: { title: string; value: string; flag: string }[] = [
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
];

</script>

<style></style>
