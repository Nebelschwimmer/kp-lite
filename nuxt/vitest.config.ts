import { defineConfig } from 'vitest/config'

import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  test: {
    workspace: [
      'packages/*',
      {
        extends: true,
        test: {
          include: ['tests/**/*.test.{ts,js}', 'tests/**/*.{browser}.test.{ts,js}',
            'stores/**/*spec.{ts,js}', 'components/***/**/*.test.{ts,js}',
          ],
          name: 'happy-dom',
          environment: 'happy-dom',
        }
      },
      {
        test: {
          include: ['tests/**/*.{node}.test.{ts,js}'],
          name: 'node',
          environment: 'node',
        }
      }

    ]
  },
});