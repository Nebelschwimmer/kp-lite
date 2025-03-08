<template>
	<v-dialog
		:model-value="modelValue"
		max-width="400">
		<v-card>
			<v-card-text v-if="text">
				<v-alert
					type="warning"
					variant="outlined"
					>{{ text }}</v-alert
				>
			</v-card-text>
			<v-card-actions>
				<v-btn prepend-icon="mdi-close" color="error" @click="$emit('cancel')">{{ $t("actions.cancel") }}</v-btn>
				<v-btn
					prepend-icon="mdi-check"
					@click="$emit('confirm')"
					>{{ $t("actions.confirm") }}</v-btn
				>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script lang="ts" setup>
	defineEmits(["confirm", "cancel"]);
	defineProps<{
		modelValue: boolean;
		text?: string;
	}>();
</script>

<style></style>
