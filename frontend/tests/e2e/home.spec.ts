import { expect } from '@playwright/test';
import { test } from './fixtures/apiError';

test('homepage loads', async ({ page }) => {
  await page.goto('/')
  // Verify that the main elements are visible
  await expect(
    page.getByRole('heading', { name: 'NickMe' })
  ).toBeVisible()

  // check nick generation form
  // gender group
  await expect(
    page.getByText('Genre')
  ).toBeVisible();
  const neutralRadio = page.getByRole('radio', { name: 'Neutre' });
  await expect(neutralRadio).toBeVisible();
  await expect(neutralRadio).toHaveAttribute('checked');
  const femaleRadio = page.getByRole('radio', { name: 'Féminin' });
  await expect(femaleRadio).toBeVisible();
  const maleRadio = page.getByRole('radio', { name: 'Masculin' });
  await expect(maleRadio).toBeVisible();  
  const autoRadio = page.getByRole('radio', { name: 'Peu importe' });
  await expect(autoRadio).toBeVisible();  


  // offenseLevel slider
  await expect(
    page.getByText('Niveau d\'offense')
  ).toBeVisible();
  const slider = page.getByRole('slider');
  await expect(slider).toBeVisible();
  await expect(slider).toHaveAttribute('aria-valuemin', '1');
  await expect(slider).toHaveAttribute('aria-valuemax', '20');
  await expect(slider).toHaveAttribute('aria-valuenow', '5');
  await slider.focus();
  await page.keyboard.press('ArrowRight');
  await page.keyboard.press('ArrowRight');
  await expect(slider).toHaveAttribute('aria-valuenow', '15');


  await expect(
    page.getByRole('button', { name: 'Go' })
  ).toBeVisible();

  // check footer content
  await expect(
    page.getByRole('button', { name: 'À propos' })
  ).toBeVisible()

  await expect(
    page.getByRole('button', { name: 'Suggérer un mot' })
  ).toBeVisible()

  await expect(
    page.getByRole('button', { name: 'Contact' })
  ).toBeVisible()
})
