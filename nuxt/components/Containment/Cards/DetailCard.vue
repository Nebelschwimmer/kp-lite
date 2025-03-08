<template>
  <div>
    <v-card rounded="lg">
      <v-toolbar order="1" scroll-behavior="fade-image" class="glassed">
        <template #image>
          <v-img gradient="to top right, rgba(0,0,0,.5), rgba(0,0,0,.5)" />
        </template>
        <template #prepend>
          <BackBtn />
        </template>
        <v-toolbar-title>
          <span class="font-weight-bold"> {{ pageName }} </span>
        </v-toolbar-title>

        <slot name="menu" />
      </v-toolbar>
      <template v-if="notification">
        <div class="d-flex justify-center w-100 glassed pa-2">
          <slot name="notification" location="start" />
        </div>
      </template>
      <template v-if="loading" #loader>
        <v-progress-linear indeterminate color="primary" />
      </template>
      <v-card>
        <template #image>
          <v-parallax height="500" :src="cover" class="img-blur" />
        </template>
        <v-img v-if="cover" :src="cover" cover height="350">
          <template #placeholder>
            <v-sheet height="100%">
              <div class="fill-height d-flex align-center justify-center">
                <v-progress-circular indeterminate />
              </div>
            </v-sheet>
          </template>
          <template #error>
            <ErrorPlaceHolder show-label />
          </template>
        </v-img>
        <v-sheet v-else class="default-cover img-blur" height="350" />
        <slot name="general_info" />
        <slot name="text" />
      </v-card>
    </v-card>
  </div>
</template>

<script lang="ts" setup>
import BackBtn from "../Btns/BackBtn.vue";
import ErrorPlaceHolder from "../Img/ErrorPlaceHolder.vue";
defineEmits(["drawer:toggle"]);
defineProps<{
  pageName?: string;
  title?: string;
  loading: boolean;
  cover?: string;
  drawer?: boolean;
  notification?: boolean;
}>();
</script>

<style>
.default-cover {
  height: 250px;
  width: 100%;
  background-image:
    linear-gradient(
      306deg,
      rgba(54, 54, 54, 0.05) 0%,
      rgba(54, 54, 54, 0.05) 33.333%,
      rgba(85, 85, 85, 0.05) 33.333%,
      rgba(85, 85, 85, 0.05) 66.666%,
      rgba(255, 255, 255, 0.05) 66.666%,
      rgba(255, 255, 255, 0.05) 99.999%
    ),
    linear-gradient(
      353deg,
      rgba(81, 81, 81, 0.05) 0%,
      rgba(81, 81, 81, 0.05) 33.333%,
      rgba(238, 238, 238, 0.05) 33.333%,
      rgba(238, 238, 238, 0.05) 66.666%,
      rgba(32, 32, 32, 0.05) 66.666%,
      rgba(32, 32, 32, 0.05) 99.999%
    ),
    linear-gradient(
      140deg,
      rgba(192, 192, 192, 0.05) 0%,
      rgba(192, 192, 192, 0.05) 33.333%,
      rgba(109, 109, 109, 0.05) 33.333%,
      rgba(109, 109, 109, 0.05) 66.666%,
      rgba(30, 30, 30, 0.05) 66.666%,
      rgba(30, 30, 30, 0.05) 99.999%
    ),
    linear-gradient(
      189deg,
      rgba(77, 77, 77, 0.05) 0%,
      rgba(77, 77, 77, 0.05) 33.333%,
      rgba(55, 55, 55, 0.05) 33.333%,
      rgba(55, 55, 55, 0.05) 66.666%,
      rgba(145, 145, 145, 0.05) 66.666%,
      rgba(145, 145, 145, 0.05) 99.999%
    ),
    linear-gradient(90deg, rgb(63, 53, 21), rgba(3, 117, 211, 0.603));
}
</style>
