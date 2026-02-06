import { test as base, Page } from '@playwright/test';

export type EnvFixtures = {
  isMockedApi: boolean;
};

export const test = base.extend<EnvFixtures, Page>({
  isMockedApi: async ({}, use) => {
    await use(process.env.MOCK_API === 'true');
  },
  page: async ({ page }, use) => {
    page.on('console', msg => {
      switch(msg.type()) {
        case 'info':
        case 'log':
        case 'error':
          console.log(`[page console.log] ${msg.type()} ${msg.text()}`); 
      }
      
    });

    await use(page);
  },
});
