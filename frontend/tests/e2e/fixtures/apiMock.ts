import { test as base } from './env';
import { mockApi } from '../mocks/api.mock';

export type AutoApiMockFixtures = {
  autoApiMock: void;
};

export const test = base.extend<AutoApiMockFixtures>({
  autoApiMock: async ({ page, isMockedApi }, use) => {
    if (isMockedApi) {
      await mockApi(page);
    }
    await use();
  },
});