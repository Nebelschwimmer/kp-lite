export default defineNuxtRouteMiddleware((to) => {
	const userStore = useAuthStore();

	if (!userStore.isAuthenticated && to.path !== "/auth/sign-in") {
		return navigateTo("/auth/sign-in");
	}
});
