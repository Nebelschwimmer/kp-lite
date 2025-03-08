<template>
    <EmptyPage>
      <template #default>
        <v-empty-state
          :title="$t('empty_states.films')"
          icon="mdi-filmstrip-off"
          :action-text="$t('empty_states.actions.add')"
          @click:action="navigateTo(localeRoute('/films/new'))"
        />
      </template>
    </EmptyPage>
</template>

<script lang="ts" setup>
import EmptyPage from "~/components/Templates/EmptyPage.vue";

const localeRoute = useLocaleRoute();
</script>
