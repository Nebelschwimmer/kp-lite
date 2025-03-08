<template>
	<v-btn flat :stacked="stacked || false" @click="toggleTheme">
		<v-icon icon="mdi-theme-light-dark"/>
	</v-btn>
</template>

<script lang="ts" setup>
defineProps<{ stacked?: boolean }>();
const theme = useTheme();
const toggleTheme = () => {
	theme.global.name.value = theme.global.current.value.dark
		? "light"
		: "dark";
};
</script>

<style></style>
