<template>
	<v-fab
		v-if="$vuetify.display.mdAndUp"
		:active="show"
		icon="mdi-arrow-up"
		color="secondary"
		location="bottom end"
		size="64"
		app
		@click="$emit('scroll:top')"/>
</template>

<script lang="ts" setup>
defineProps<{ show: boolean }>();
defineEmits(["scroll:top"]);
</script>

<style></style>
