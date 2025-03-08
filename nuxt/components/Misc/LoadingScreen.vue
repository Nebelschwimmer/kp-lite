<template>
  <div
    class="d-flex flex-column p-5 gap-2 align-center justify-center overlay bg-gradient"
  >
    <span class="text-center">{{ $t("state.loading") }}...</span>
    <span class="progress mx-auto" />
  </div>
</template>

<script lang="ts" setup></script>

<style lang="scss">
.overlay {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 9999;
  width: 100%;
  height: 100%;
  background-color: #22222249;
}

.progress {
  width: 48px;
  height: 48px;
  border: 5px solid #5e5e5e17;
  border-bottom-color: rgb(255, 174, 0);
  border-radius: 50%;
  display: inline-block;
  box-sizing: border-box;
  animation: rotation 1s linear infinite;
}

@keyframes rotation {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
