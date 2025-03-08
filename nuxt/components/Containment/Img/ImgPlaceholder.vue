<template>
	<v-sheet
		height="100%"
		width="100%"
		min-height="100%"
		image="https://cdn.vuetifyjs.com/docs/images/parallax/stars.jpg"
		class="d-flex dark-glass align-center justify-center flex-column w-100">
		<template v-if="displayIcon">
			<div
				v-if="!loading"
				class="d-flex flex-column align-center justify-center ga-3">
				<v-list-item
					class="text-caption text-center"
					lines="one">
					<div class="d-flex flex-column align-center justify-center ga-1">
						<v-icon
							icon="mdi-image-off"
							size="x-small"/>
					</div>
				</v-list-item>
			</div>
		</template>

		<div
			v-else
			class="d-flex flex-column align-center justify-center fill-height">
			<v-progress-circular indeterminate/></div>
	</v-sheet>
</template>

<script lang="ts" setup>
	defineProps<{
		loading?: boolean;
		displayIcon?: boolean;
	}>();
</script>

<style lang="scss"></style>
