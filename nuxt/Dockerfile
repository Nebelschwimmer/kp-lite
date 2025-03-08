# Build Stage 1
FROM node:22-alpine AS build
WORKDIR /app

# Включаем Corepack
RUN corepack enable

# Копируем package.json и pnpm-lock.yaml
COPY package.json pnpm-lock.yaml ./

# Устанавливаем зависимости
RUN pnpm i

# Копируем весь проект
COPY . ./

# Собираем проект
RUN pnpm run build

# Build Stage 2
FROM node:22-alpine
WORKDIR /app

# Копируем только .output из build stage
COPY --from=build /app/.output ./

# Копируем .env файл
COPY --from=build /app/.env ./

RUN echo "BASE_URL=${BASE_URL}"
RUN echo "API_BASE_URL=${API_BASE_URL}"
RUN echo "APP_NAME=${APP_NAME}"

# Открываем порт 80
EXPOSE 80

# Запускаем сервер
CMD ["node", "/app/server/index.mjs"]