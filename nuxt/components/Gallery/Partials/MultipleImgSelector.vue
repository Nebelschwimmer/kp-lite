<template>
  <v-card>
    <v-card-text>
      <v-item-group
        v-model="selectedItems"
        multiple
        @update:model-value="$emit('update:selected', $event)"
      >
        <v-container>
          <v-row>
            <v-col
              v-for="(img, index) in sliderGalleryArr"
              :key="index"
              cols="auto"
            >
              <v-item v-slot="{ isSelected, selectedClass, toggle }">
                <v-sheet class="text-center">
                  <v-card
                    v-if="img"
                    :value="index"
                    rounded="lg"
                    :color="isSelected ? 'error' : ''"
                    :class="[
                      'd-flex align-center justify-center',
                      selectedClass,
                    ]"
                    :height="cardHeight"
                    :width="cardHeight ? cardHeight * 0.75 : 0"
                    :image="img || ''"
                    @click="toggle"
                  >
                    <v-scroll-y-transition>
                      <div
                        v-if="isSelected"
                        :class="[
                          'd-flex fill-height w-100 flex-column align-center justify-center position-relative',
                          { 'bg-selected-remove': isSelected },
                        ]"
                      >
                        <v-btn icon variant="tonal" color="white">
                          <v-icon size="x-large" class="position-absolute">{{
                            img ? "mdi-close" : "mdi-plus"
                          }}</v-icon>
                        </v-btn>
                      </div>
                    </v-scroll-y-transition>
                  </v-card>
                  <v-label v-if="img" class="mt-2">{{
                    $t("general.img") + " " + (index + 1)
                  }}</v-label>
                </v-sheet>
              </v-item>
            </v-col>
          </v-row>
        </v-container>
      </v-item-group>
    </v-card-text>
    <template #actions>
      <v-spacer />
      <v-btn
        prepend-icon="mdi-close"
        :disabled="!selectedItems.length"
        @click="emit('gallery:clear')"
        >{{ $t("actions.clear") }}</v-btn
      >
      <v-btn
        prepend-icon="mdi-delete"
        color="error"
        :disabled="!selectedItems.length"
        @click="$emit('delete:selected', selectedItems)"
        >{{ $t("actions.remove") }}</v-btn
      >
    </template>
  </v-card>
</template>

<script lang="ts" setup>
defineProps<{
  sliderGalleryArr: string[];
  cardHeight?: number;
}>();
const emit = defineEmits([
  "update:selected",
  "delete:selected",
  "gallery:clear",
]);

const selectedItems = ref<Array<number>>([]);
</script>

<style></style>
