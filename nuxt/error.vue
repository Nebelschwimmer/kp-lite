<template>
  <NuxtLayout>
    <v-app>
      <Head>
        <Title>{{
          definePageTitle(
            $t(`toast.messages.server_errors.${error?.statusCode}`)
          )
        }}</Title>
      </Head>
      <v-empty-state
        :headline="error?.statusCode.toString()"
        :title="$t(`toast.messages.server_errors.${error?.statusCode}`)"
        icon="mdi-alert-rhombus"
        :action-text="$t('empty_states.actions.home')"
        @click:action="handleError"
      />
    </v-app>
  </NuxtLayout>
</template>

<script setup lang="ts">
const error = useError();

const handleError = () => {
  clearError({
    redirect: "/",
  });
};
</script>
