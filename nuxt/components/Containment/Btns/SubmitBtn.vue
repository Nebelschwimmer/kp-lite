<template>
	<v-btn
		:loading="loading"
    :disabled="disabled"
		
		color="primary"
		prepend-icon="mdi-check"
		@click="$emit('click:submit')">
		{{ $t("actions.submit") }}
	</v-btn>
</template>

<script lang="ts" setup>
	defineProps<{
	loading?: boolean;
	disabled?: boolean;
	}>();

defineEmits(["click:submit"]);
</script>

<style></style>
