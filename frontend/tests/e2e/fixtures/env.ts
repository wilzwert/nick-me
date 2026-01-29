import { test as base } from '@playwright/test';

export type EnvFixtures = {
  isMockedApi: boolean;
};

export const test = base.extend<EnvFixtures>({
  isMockedApi: async ({}, use) => {
    await use(process.env.MOCK_API === 'true');
  },
});
