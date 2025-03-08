<template>
  <div class="d-flex ga-1 align-center">
    <v-menu location="bottom end">
      <template #activator="{ props }">
        <v-btn icon :disabled="!isAuthenticated" v-bind="props">
          <v-icon>mdi-dots-vertical</v-icon>
        </v-btn>
      </template>
      <v-list density="compact">
        <v-list-item
          :title="$t('actions.choose_cover')"
          prepend-icon="mdi-image"
          value="cover"
          @click="$emit('choose:cover')"
        />
        <v-list-item
          :title="$t('actions.edit')"
          prepend-icon="mdi-pencil"
          value="edit"
        >
          <template #append>
            <v-icon icon="mdi-menu-right" size="x-small" />
          </template>
          <v-menu activator="parent" submenu open-on-hover>
            <v-list density="compact">
              <v-list-item
                :title="$t('pages.general_info')"
                prepend-icon="mdi-information"
                value="info"
                @click="$emit('edit:general')"
              />
              <v-list-item
                :title="$t('pages.detailed_info')"
                prepend-icon="mdi-details"
                value="details"
                @click="$emit('edit:description')"
              />
              <v-list-item
                :title="$t('pages.gallery')"
                prepend-icon="mdi-view-gallery"
                value="gallery"
                @click="$emit('edit:gallery')"
              />
            </v-list>
          </v-menu>
        </v-list-item>
        <v-list-item
          :title="$t('actions.remove')"
          prepend-icon="mdi-delete"
          value="remove"
          base-color="error"
          @click="$emit('delete:film')"
        />
      </v-list>
    </v-menu>
  </div>
</template>

<script lang="ts" setup>
defineProps<{
  isAuthenticated: boolean;
}>();
defineEmits([
  "choose:cover",
  "edit:general",
  "edit:description",
  "edit:gallery",
  "delete:film",
]);
</script>

<style></style>
