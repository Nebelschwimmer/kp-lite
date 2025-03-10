<template>
  <div>
    <Head>
      <Title>{{ definePageTitle($t("pages.about.title")) }}</Title>
    </Head>
    <v-card max-width="860" class="mx-auto">
      <v-toolbar>
        <template #prepend>
          <BackBtn />
        </template>
        <v-toolbar-title>
          {{ $t("pages.about.title") }}
        </v-toolbar-title>
      </v-toolbar>
      <v-card
        :title="$t('pages.about.stack')"
        prepend-icon="mdi-hammer-wrench"
        class="my-2"
        variant="tonal"
        color="blue"
      >
        <v-table hover>
          <thead>
            <tr>
              <th />
              <th class="text-center">
                {{ $t("pages.about.table_headers.language") }}
              </th>
              <th class="text-center">
                {{ $t("pages.about.table_headers.main_framework") }}
              </th>
              <th class="text-center">
                {{ $t("pages.about.table_headers.additional_framework") }} / DB
              </th>
            </tr>
          </thead>
          <tbody class="text-center">
            <tr>
              <td>{{ $t("pages.about.table_headers.frontend") }}</td>
              <td>
                <v-tooltip>
                  <template #activator="{ props }">
                    <v-icon v-bind="props" size="x-large" color="blue"
                      >mdi-language-typescript</v-icon
                    >
                  </template>
                  Typescript
                </v-tooltip>
              </td>
              <td>
                <v-tooltip>
                  <template #activator="{ props }"
                    ><v-icon
                      v-bind="props"
                      size="x-large"
                      color="green-accent-3"
                      >mdi-nuxt</v-icon
                    ></template
                  >
                  Nuxt 3
                </v-tooltip>
              </td>
              <td>
                <v-tooltip>
                  <template #activator="{ props }"
                    ><v-icon
                      v-bind="props"
                      size="x-large"
                      color="light-blue-accent-4"
                      >mdi-vuetify</v-icon
                    ></template
                  >
                  Vuetify 3
                </v-tooltip>
              </td>
            </tr>
            <tr>
              <td>{{ $t("pages.about.table_headers.backend") }}</td>
              <td>
                <v-tooltip>
                  <template #activator="{ props }">
                    <v-icon
                      v-bind="props"
                      size="x-large"
                      color="deep-purple-lighten-3"
                      >mdi-language-php
                    </v-icon>
                  </template>
                  Php 8.3
                </v-tooltip>
              </td>
              <td>
                <v-tooltip>
                  <template #activator="{ props }"
                    ><v-icon v-bind="props" size="x-large" color="purple"
                      >mdi-symfony</v-icon
                    ></template
                  >
                  Symfony 7
                </v-tooltip>
              </td>
              <td>
                <v-tooltip location="bottom">
                  <template #activator="{ props }">
                    <v-img
                      v-bind="props"
                      height="32"
                      src="/img/psql.svg"
                    />
                  </template>
                  Postresql 16
                </v-tooltip>
              </td>
            </tr>
          </tbody>
        </v-table>
      </v-card>
      <v-card
        :title="$t('pages.about.dev_prod.title')"
        prepend-icon="mdi-code-tags"
        class="my-2"
        variant="tonal"
        color="secondary"
      >
        <v-table hover>
          <thead>
            <tr>
              <th class="text-center">
                {{ $t("pages.about.dev_prod.table_headers.pipeline") }}
              </th>
              <th class="text-center">
                {{ $t("pages.about.dev_prod.table_headers.containerisation") }}
              </th>
            </tr>
          </thead>
          <tbody class="text-center">
            <tr>
              <td>
                <v-tooltip>
                  <template #activator="{ props }">
                    <v-icon v-bind="props" size="x-large"
                      >mdi-github</v-icon
                    ></template
                  >
                  GitHub Actions: CI & CD
                </v-tooltip>
              </td>
              <td>
                <v-tooltip>
                  <template #activator="{ props }">
                    <v-icon v-bind="props" size="x-large" color="blue"
                      >mdi-docker</v-icon
                    ></template
                  >
                  Docker + Docker Compose
                </v-tooltip>
              </td>
            </tr>
          </tbody>
        </v-table>
      </v-card>
      <v-card
        :title="$t('pages.about.author.title')"
        class="my-2"
        variant="tonal"
      >
        <v-card-text>
          <v-list rounded="lg">
            <v-list-item
              href="https://github.com/Nebelschwimmer"
              target="_blank"
              lines="two"
            >
              <template #prepend>
                <v-avatar size="64">
                  <v-img
                    src="/img/nebelschwimmer_avatar.jpg"
                    alt="fd"
                    height="164"
                    width="164"
                  />
                </v-avatar>
              </template>
              <v-list-item-title class="text-lg-h5">{{
                $t("pages.about.author.name")
              }}</v-list-item-title>
              <v-list-item-subtitle
                >JS, TS, PHP: React, Vue 3, Nuxt 3, Node JS (Express), Symfony.
                DB: Mongo, Postgres</v-list-item-subtitle
              >
              <template #append>
                <v-icon size="x-large">mdi-github</v-icon></template
              >
            </v-list-item>
          </v-list>
        </v-card-text>
      </v-card>
    </v-card>
  </div>
</template>

<script lang="ts" setup>
import BackBtn from "~/components/Containment/Btns/BackBtn.vue";
import definePageTitle from "~/utils/definePageTitle";
</script>

<style></style>
