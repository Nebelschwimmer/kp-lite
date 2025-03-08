<template>
  <v-scroll-x-transition>
    <v-card rounded="lg">
      <v-divider />
      <v-card-text>
        <div>
          <span>{{ $t("actions.limit") }}</span>
          <v-chip-group
            v-model="selectedLimit"
            mandatory
            column
            selected-class="text-primary"
            @update:model-value="$emit('update:limit', $event)"
          >
            <v-chip
              v-for="(limit, index) in limitOptions"
              :key="index"
              :value="limit"
              :text="limit.title"
              filter
            />
          </v-chip-group>
        </div>

        <div>
          <span>{{ $t("filters.order.title") }}</span>
          <v-chip-group
            v-model="selectedOrder"
            mandatory
            column
            selected-class="text-primary"
            @update:model-value="$emit('update:order', $event)"
          >
            <v-chip
              v-for="(order, index) in orderOptions"
              :key="index"
              :value="order"
              filter
              :text="order.title"
            />
          </v-chip-group>
        </div>

        <div>
          <span>{{ $t("filters.sort.title") }}:</span>
          <v-chip-group
            v-model="selectedSortOption"
            mandatory
            column
            selected-class="text-secondary"
            @update:model-value="$emit('update:sort', $event)"
          >
            <v-chip
              v-for="(sort, index) in sortOptions"
              :key="index"
              :value="sort"
              filter
              :text="sort.title"
            />
          </v-chip-group>
        </div>
      </v-card-text>
    </v-card>
  </v-scroll-x-transition>
</template>

<script lang="ts" setup>
const props = defineProps<{
  sortOptions: { value: string; title: string }[];
}>();
defineEmits(["update:limit", "update:order", "update:sort"]);

const { t } = useI18n();
const orderOptions = [
  { value: "asc", title: t("actions.asc") },
  { value: "desc", title: t("actions.desc") },
];
const selectedOrder = ref({ value: "asc", title: t("actions.asc") });

const limitOptions = [
  { value: 5, title: "5" },
  { value: 10, title: "10" },
  { value: 15, title: "15" },
  { value: 0, title: t("filters.sort.all") },
];
const selectedLimit = ref(limitOptions[0]);
const selectedSortOption = ref(props.sortOptions[0]);
</script>

<style></style>
