import { configDefaults, defineConfig } from 'vitest/config'
import react from '@vitejs/plugin-react'

export default defineConfig({
  base: '/',
  build: {
    outDir: 'dist',
    sourcemap: false, // optionnel, en prod
  },
  plugins: [
    react(),
  ],
  test: {
    environment: 'jsdom',
    globals: true,
    setupFiles: './test-utils/setup.ts',
    exclude: [...configDefaults.exclude, "**/tests/e2e/**"],
    coverage: {
      provider: 'v8',
      reporter: ['html', 'lcov', 'json'],
      reportsDirectory: './coverage/unit',
      exclude: [
        '**/*.spec.ts',
        '**/*.test.ts',
        '**/*.d.ts',
        '**/main.tsx',
        '**/*.config.*',
        "**/tests/e2e/**",
        "**/test-utils/**"
      ],
    },
  },
})