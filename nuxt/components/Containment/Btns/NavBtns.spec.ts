// import { mount } from "@vue/test-utils";
// import { createTestingPinia } from "@pinia/testing";
// import { createMemoryHistory, createRouter } from "vue-router";
// import { vi } from "vitest";
// import NavBtns from "@/components/NavBtns.vue";

// // Моки для маршрутов
// const routes = [
//   { path: "/", name: "home" },
//   { path: "/films", name: "films" },
//   { path: "/films/:id", name: "filmDetails" },
//   { path: "/persons", name: "persons" },
//   { path: "/films/new", name: "newFilm" },
// ];

// const router = createRouter({
//   history: createMemoryHistory(),
//   routes,
// });

// // Фабрика для создания компонента с разными маршрутами
// const factory = async (routeName: string, isAuthenticated = false) => {
//   router.push({ name: routeName });
//   await router.isReady();

//   return mount(NavBtns, {
//     global: {
//       plugins: [router, createTestingPinia({ createSpy: vi.fn })],
//       mocks: {
//         $t: (key: string) => key, // Мок для i18n
//       },
//     },
//   });
// };

// describe("NavBtns.vue", () => {
//   it("рендерит все кнопки", async () => {
//     const wrapper = await factory("home");
//     expect(wrapper.find("[key='home_nav']").exists()).toBe(true);
//     expect(wrapper.find("[prepend-icon='mdi-filmstrip']").exists()).toBe(true);
//     expect(wrapper.find("[prepend-icon='mdi-account']").exists()).toBe(true);
//   });

//   it("активирует кнопку фильмов на всех связанных маршрутах", async () => {
//     const wrapperFilms = await factory("films");
//     expect(wrapperFilms.find("[prepend-icon='mdi-filmstrip']").classes()).toContain("v-btn--active");

//     const wrapperFilmDetails = await factory("filmDetails");
//     expect(wrapperFilmDetails.find("[prepend-icon='mdi-filmstrip']").classes()).toContain("v-btn--active");
//   });

//   it("активирует кнопку персон", async () => {
//     const wrapper = await factory("persons");
//     expect(wrapper.find("[prepend-icon='mdi-account']").classes()).toContain("v-btn--active");
//   });

//   it("кнопка создания фильма отключена для неавторизованных пользователей", async () => {
//     const wrapper = await factory("films", false);
//     expect(wrapper.find("[to='/films/new']").attributes("disabled")).toBe("");
//   });
// });
