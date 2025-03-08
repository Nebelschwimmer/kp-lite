<template>
	<v-fab
		icon="mdi-plus"
		color="primary"
		absolute
		offset
		location="top end"
		:to="to"/>
</template>

<script lang="ts" setup>
	defineProps<{ to: string }>();
</script>

<style></style>
