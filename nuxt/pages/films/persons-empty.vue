<template>
  <EmptyPage>
    <template #default>
      <v-empty-state
        :title="$t('empty_states.persons')"
        :text="$t('empty_states.no_persons_found')"
        icon="mdi-account-off"
        :action-text="$t('empty_states.actions.add')"
        @click:action="navigateTo(localeRoute('/persons/new'))"
      />
    </template>
  </EmptyPage>
</template>

<script lang="ts" setup>
import EmptyPage from "~/components/Templates/EmptyPage.vue";

const localeRoute = useLocaleRoute();
</script>

<style></style>
