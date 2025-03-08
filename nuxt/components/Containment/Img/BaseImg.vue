<template>
	<v-hover :disabled="!imgSrc || !imgOptions.removable">
		<v-img
			:src="imgSrc || ''"
			rounded="lg"
			width="100%"
			v-bind="{
				...imgOptions,
			}"
			:gradient="imgOptions.shaded ? gradientStr : undefined"
			:cover="imgOptions.cover"
			:class="[
				'position-relative',
				imgOptions.class,
				{ 'cursor-pointer': imgOptions.clickable || imgOptions.removable },
			]"
			:aspect-ratio="imgOptions.aspectRatio"
			@click="$emit('click')">
			<template #placeholder>
				<ImgPlaceholder v-bind="imgOptions.placeholderOptions" />
			</template>
			<template #error>
				<v-sheet
					class="d-flex flex-column align-center justify-center fill-height">
					<v-icon
						icon="mdi-image-off"
						color="error"
						size="small"/>
				</v-sheet>
			</template>
		</v-img>
	</v-hover>
</template>

<script lang="ts" setup>
	import ImgPlaceholder from "./ImgPlaceholder.vue";
	defineEmits(["remove", "click", "uploader:open"]);
	defineProps<{
		imgSrc: string;
		imgOptions: ImgOptions;
	}>();

	const FIRST_COLOR = "rgba(0, 0, 0, 0.4)";
	const SECOND_COLOR = "rgb(45, 45, 45, 0.5)";
	const gradientStr = `to bottom, ${FIRST_COLOR}, ${SECOND_COLOR}`;
</script>

<style lang="scss" scoped>
	$firstColor: rgba(148, 148, 148, 0.2);
	$secondColor: rgba(165, 165, 165, 0.1);
</style>
