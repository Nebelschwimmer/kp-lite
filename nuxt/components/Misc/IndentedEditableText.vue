<template>
  <v-card rounded="lg" class="pa-2" variant="text" border>
    <div v-if="!editMode" class="text-body-1 pa-2">
      <div v-if="text" :class="['text-container', { expanded: !collapsed }]">
        <p v-for="(paragraph, index) in text ? text?.split('\n') : []" :key="index">
          {{ paragraph || "" }}
        </p>
      </div>
      <v-skeleton-loader v-else type="text" />
      <div class="mt-2 text-center">
        <ExpandBtn
          v-if="shouldShowExpandButton"
          :expanded="!collapsed"
          @click="collapsed = !collapsed"
        />
      </div>
    </div>
    <v-confirm-edit
      v-else
      v-model="editModeText"
      @save="$emit('sumbit:edit', editModeText)"
    >
      <template #default="{ model: proxyModel, actions }">
        <v-card variant="outlined">
          <template #text>
            <v-textarea
              v-model="proxyModel.value"
              :messages="messages"
              auto-grow
              rows="5"
            />
          </template>
          <template #actions>
            <v-spacer />
            <component :is="actions" />
          </template>
        </v-card>
      </template>
    </v-confirm-edit>
  </v-card>
</template>

<script lang="ts" setup>
import ExpandBtn from "../Containment/Btns/ExpandBtn.vue";

const props = defineProps<{
  editMode: boolean;
  text: string;
  messages: string;
}>();
defineEmits(["sumbit:edit"]);

const editModeText = ref<string>(props.text);
const collapsed = ref<boolean>(true);

const shouldShowExpandButton = computed((): boolean => {
  const lineHeight = 1.4; 
  const maxLines = 3; 
  const maxHeight = lineHeight * maxLines; 
  const textHeight = props.text ? (props.text?.split("\n").length * lineHeight + 1) : 0; 

  return textHeight > maxHeight;
});
</script>

<style sroped>
.text-container {
  display: -webkit-box;
  line-clamp: 3;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  line-height: 1.4em;
  max-height: calc(1.4em * 6);
}

.expanded {
  max-height: none;
  line-clamp: unset;
  -webkit-line-clamp: unset;
}
</style>
