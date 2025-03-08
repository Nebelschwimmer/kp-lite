<template>
  <v-layout class="bg-gradient">
    <client-only>
      <v-navigation-drawer
        v-if="$vuetify.display.smAndDown"
        v-model="drawer"
        order="1"
        :location="$vuetify.display.smAndDown ? 'end' : 'start'"
      >
        <NavDrawerContent />
      </v-navigation-drawer>
    </client-only>
    <v-app-bar order="0">
      <v-app-bar-title>
        <Logo />
      </v-app-bar-title>
      
      <div v-if="!$vuetify.display.smAndDown" class="d-flex ga-1">
        <NavBtns />
      </div>
      <v-spacer v-if="$vuetify.display.mdAndUp"/>
      <template v-if="$vuetify.display.mdAndUp" #append>
        <div class="d-flex ga-1 align-center">
          <ProfileNav />
          <LanguageChangeBtn />
        </div>
      </template>
      <v-menu v-if="$vuetify.display.smAndDown">
        <template #activator="{ props }">
          <v-btn v-bind="props" icon>
            <v-icon>mdi-dots-vertical</v-icon>
          </v-btn>
        </template>
        <v-list>
          <v-list-item
            v-for="lang in languageOptions"
            :key="lang.value"
            v-model="activeLocale"
            :active="activeLocale === lang.value"
            :title="lang.title"
            :value="lang.value"
            @click="changeLanguage(lang.value)"
          />
        </v-list>
      </v-menu>
    </v-app-bar>

    <v-main style="min-height: 100vh">
      <v-container fluid>
        <slot />
      </v-container>
    </v-main>

    <v-bottom-navigation
      v-if="$vuetify.display.smAndDown && !error"
      v-model="activeBottomBtn"
      color="primary"
      grow
    >
      <v-btn value="home" :to="localeRoute('/')" icon="mdi-home" />
      <v-btn value="films" :to="localeRoute('/films')" icon="mdi-filmstrip" />
      <v-btn
        value="persons"
        :to="localeRoute('/persons')"
        icon="mdi-account-group"
      />
      <v-btn
        v-if="isAuthenticated"
        value="profile"
        icon="mdi-account"
        :to="localeRoute('/profile')"
      />
      <v-btn
        v-else
        value="profile"
        :to="localeRoute('/auth/sign-in')"
        icon="mdi-login"
      />
    </v-bottom-navigation>
  </v-layout>
</template>

<script lang="ts" setup>
import { useAuthStore } from "~/stores/authStore";
import NavBtns from "~/components/Navigation/NavBtns.vue";
import Logo from "~/components/Misc/Logo.vue";
import LanguageChangeBtn from "~/components/Containment/Btns/LocaleHandleBtn.vue";
import ProfileNav from "~/components/Navigation/ProfileNav.vue";
import NavDrawerContent from "~/components/Navigation/AppNavDrawerContent.vue";

const { isAuthenticated } = storeToRefs(useAuthStore());
const { setLocale, locale } = useI18n();

const error = useError();
const route = useRoute();
const localeRoute = useLocaleRoute();
const drawer = ref(false);
const activeBottomBtn = ref("home");
const activeLocale = ref(locale);
const showBackBtn = ref(false);
const checkBrowserHistory = () => {
  if (window.history.length > 1) {
    showBackBtn.value = true;
  }
};

const changeLanguage = (lang: string) => {
  const language = lang as Language;
  setLocale(language);
};

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

watch(
  () => route.path,
  () => {
    checkBrowserHistory();
  }
);
</script>
