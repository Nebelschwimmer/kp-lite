// vuetify.config.ts
import { defineVuetifyConfiguration } from "vuetify-nuxt-module/custom-configuration";

export default defineVuetifyConfiguration({
  labComponents: true,
  defaults: {
    VTextField: {
      variant: "filled",
    },
    VSelect: {
      variant: "filled",
    },
    VCombobox: {
      variant: "filled",
    },
    VSheet: {
      class: "glassed",
      border: true,
    },
    VExpansionPanels: {
      class: "glassed",
      border: true,
    },
    VList: {
      class: "glassed",
    },
  },
  theme: {
    defaultTheme: "dark",
    themes: {
      dark: {
        dark: true,
        colors: {
          background: "rgb(12, 21, 28)",
          surface: "rgb(24, 34, 41)",
          primary: "rgb(255, 174, 0)",
        },
      },
    },
  },
});
