export default defineNuxtRouteMiddleware((to) => {

  if (to.path !== "/auth/sign-in" && to.path !== "/auth/sign-up") {
    return navigateTo("/auth/sign-in");
  }
})
