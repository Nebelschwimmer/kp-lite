<template>
  <span :class="['section-divider', { 'brighter my-2': extended }]" />
</template>

<script lang="ts" setup>
defineProps<{ extended?: boolean }>();
</script>

<style>
.section-divider {
  width: 100%;
  height: 2px;
  background-image: linear-gradient(
    to right,
    rgba(0, 0, 0, 0),
    rgba(150, 150, 150, 0.6),
    rgba(0, 0, 0, 0)
  );
}
.brighter {
  backdrop-filter: saturate(2);
}
</style>
