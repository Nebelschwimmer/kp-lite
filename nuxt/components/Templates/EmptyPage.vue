<template>
 <div>
    <Head>
      <Title>{{ definePageTitle('') }}</Title>
    </Head>
    <v-sheet
      class="d-flex align-center justify-center"
      style="height: calc(100vh - 96px)"
    >
      <slot />
    </v-sheet>
 </div>
</template>

<script lang="ts" setup>

</script>

<style></style>
