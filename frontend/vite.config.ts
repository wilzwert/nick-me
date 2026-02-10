import { configDefaults, defineConfig } from 'vitest/config'
import react from '@vitejs/plugin-react'

export default defineConfig({
  base: '/',
  build: {
    rollupOptions: {
      output: {
        manualChunks(id) {
          if (id.includes("node_modules")) {
            // une seule règle simple : tout ce qui est node_modules → vendor-[nom-lib]
            const dirs = id.split("node_modules/")[1].split("/");
            return dirs[0]; // ex: react, react-dom, @mantine/core, use-latest...
          }
        },
      },
    },
    outDir: 'dist',
    sourcemap: false,
  },
  plugins: [
    react()
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