import { Page, Route } from '@playwright/test'
import { nickMocks } from './nick.mock';


type MockHandler = (route: Route) => Promise<void> | void;

export async function mockApi(page: Page) {
  await page.route('**/api/**', async route => {
    const url = route.request().url()
    const method = route.request().method()
   
    if (url.includes('/nick?') && method === 'GET') {
      return route.fulfill(nickMocks.response());
    }

    if(url.includes('/altcha') && method === 'GET') {
        return route.fulfill({
        status: 200,
        contentType: 'application/json',
        body: JSON.stringify({}),
      })
    }

    // TODO : improve mocks setup 

    if (url.endsWith('/suggestions') && route.request().method() === 'POST') {
      return route.fulfill({
        status: 201,
        contentType: 'application/json',
        body: JSON.stringify({}),
      })
    }
    if (url.endsWith('/contact') && route.request().method() === 'POST') {
      return route.fulfill({
        status: 201,
        contentType: 'application/json',
        body: JSON.stringify({}),
      })
    }
    if (url.endsWith('/report') && route.request().method() === 'POST') {
      return route.fulfill({
        status: 201,
        contentType: 'application/json',
        body: JSON.stringify({}),
      })
    }

    // fallback
    return route.continue();
  })
}
