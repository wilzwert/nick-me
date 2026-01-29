import { test as base } from './apiMock';

type ApiErrorFixtures = {
  mockApiError: (config: {
    url: RegExp | string;
    status: number;
    body?: unknown;
  }) => Promise<void>;
};

export const test = base.extend<ApiErrorFixtures>({
  mockApiError: async ({ page }, use) => {
    await use(async ({ url, status, body }) => {
      await page.route(url, route =>
        route.fulfill({
          status,
          contentType: 'application/json',
          body: JSON.stringify(body ?? {}),
        })
      );
    });
  },
});
