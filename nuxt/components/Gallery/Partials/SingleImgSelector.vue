<template>
  <v-card>
    <v-card-text>
      <v-slide-group v-model="selectedCoverIndex" show-arrows center-active>
        <v-slide-group-item
          v-for="(img, index) in ['', ...(gallery as string[])]"
          :key="index"
          v-slot="{ isSelected, toggle, selectedClass }"
        >
          <v-sheet class="text-center">
            <v-card
              :class="['ma-4', selectedClass]"
              :height="cardHeight ? cardHeight : 300"
              :width="cardHeight ? cardHeight * 0.75 : 200"
              :image="img || ''"
              border
              @click="toggle"
            >
              <template #image>
                <v-img :src="img" cover>
                  <template v-if="index !== 0" #placeholder>
                    <v-sheet height="100%">
                      <div
                        class="d-flex align-center justify-center fill-height"
                      >
                        <v-progress-circular indeterminate />
                      </div>
                    </v-sheet>
                  </template>
                </v-img>
              </template>
              <v-scroll-y-transition>
                <div
                  v-if="isSelected"
                  :class="[
                    'd-flex fill-height w-100 flex-column align-center justify-center position-relative',
                    { 'bg-selected-cover': isSelected && img },
                    { 'bg-surface': isSelected && !img },
                  ]"
                >
                  <v-btn icon variant="tonal" color="white">
                    <v-icon size="x-large" class="position-absolute">{{
                      img ? "mdi-check" : "mdi-close"
                    }}</v-icon>
                  </v-btn>
                </div>
              </v-scroll-y-transition>
            </v-card>
            <div class="mb-2">
              <v-label v-if="img">{{
                $t("general.img") + " " + index
              }}</v-label>
              <v-label v-else>{{ $t("general.empty") }}</v-label>
            </div>
          </v-sheet>
        </v-slide-group-item>
      </v-slide-group>
    </v-card-text>
    <v-card-actions>
      <v-spacer />
      <v-btn
        prepend-icon="mdi-check"
        color="primary"
        @click="$emit('img:select', selectedCoverIndex)"
        >{{ $t("actions.submit") }}</v-btn
      >
    </v-card-actions>
  </v-card>
</template>

<script lang="ts" setup>
defineEmits(["img:select"]);
const props = defineProps<{
  coverIndex?: number;
  gallery: string[];
  cardHeight?: number;
}>();
const selectedCoverIndex = ref(props.coverIndex || 0);
</script>

<style></style>
