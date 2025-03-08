<template>
  <v-expansion-panels
    v-model="leftColumnAccordion"
    variant="accordion"
    bg-color="transparent"
    multiple
  >
    <v-expansion-panel :title="$t('pages.general_info')" value="info">
      <v-expansion-panel-text>
        <v-list rounded="lg" nav border>
          <div v-for="(detail, index) in generalInfo" :key="index">
            <v-list-item
              :key="index"
              :subtitle="$t(detail.title)"
              :prepend-icon="detail.icon"
              class="my-2"
            >
              <v-list-item-title :class="{ 'text-secondary': detail.to }">
                {{ detail.value || $t("general.no_data") }}
              </v-list-item-title>
            </v-list-item>
            <v-tooltip
              v-if="
                typeof detail.value === 'string' && detail.value.length > 50
              "
              activator="parent"
            >
              <span> {{ detail.value }}</span>
            </v-tooltip>
          </div>
        </v-list>
      </v-expansion-panel-text>
    </v-expansion-panel>

    <v-expansion-panel :title="$t('pages.films.starring')" value="starring">
      <v-expansion-panel-text>
        <v-list rounded="lg" nav border>
          <v-list-item
            v-for="(actor, index) in starring"
            :key="index"
            :title="actor.value"
            :value="index"
            :to="localeRoute(actor.to || '/')"
            base-color="secondary"
          >
            <template #prepend>
              <v-avatar>
                <v-img v-if="actor.avatar" :src="actor.avatar">
                  <template #placeholder>
                    <v-icon size="x-small">mdi-account</v-icon>
                  </template>
                  <template #error>
                    <ErrorPlaceHolder />
                  </template>
                </v-img>
                <v-icon v-else icon="mdi-account" />
              </v-avatar>
            </template>
          </v-list-item>
        </v-list>
      </v-expansion-panel-text>
    </v-expansion-panel>

    <v-expansion-panel value="team" :title="$t('pages.films.team')">
      <v-expansion-panel-text>
        <v-list rounded="lg" nav border>
          <v-list-item
            v-for="(person, index) in team"
            :key="index"
            rounded="lg"
            :subtitle="$t(person.title)"
            :title="person.value"
            :value="index"
            :to="localeRoute(person.to || '/')"
            base-color="secondary"
          >
            <template #prepend>
              <v-avatar>
                <v-img :src="person.avatar">
                  <template #placeholder>
                    <v-icon size="x-small">mdi-account</v-icon>
                  </template>
                  <template #error>
                    <ErrorPlaceHolder />
                  </template>
                </v-img>
              </v-avatar>
            </template>
          </v-list-item>
        </v-list>
      </v-expansion-panel-text>
    </v-expansion-panel>
  </v-expansion-panels>
</template>

<script lang="ts" setup>
import ErrorPlaceHolder from "../Containment/Img/ErrorPlaceHolder.vue";

defineProps<{
  generalInfo: Detail[];
  starring: Detail[];
  team: Detail[];
}>();
const leftColumnAccordion = ref(["info", "starring", "team"]);

const localeRoute = useLocaleRoute();
</script>

<style></style>
