import { test } from './fixtures/apiError';
import { expect } from '@playwright/test';

test('nick is generated and displayed', async ({ page, autoApiMock }) => {
    await page.goto('/')
    await page.getByRole('radio', { name: 'Neutre' }).check();
    const slider = page.getByRole('slider');
    await slider.focus();
    // set offense level to 10
    await page.keyboard.press('ArrowRight');

    // generate nick
    await page.getByRole('button', { name: 'Go' }).click();

    const nickDisplay = page.locator('.nick-display');
    await expect(nickDisplay).toBeVisible({timeout: 20000});
    await expect(
        nickDisplay.getByRole('heading', { name: 'Ton pseudo' })
    ).toBeVisible();

    await expect(
        nickDisplay.getByText('Mots du pseudo')
    ).toBeDefined();

    const nickWords = nickDisplay.locator('.nick-word');
    await expect(nickWords).toHaveCount(2);
    // Récupérer le texte
    const words = await nickWords.allTextContents();

    const replaceButtons = nickDisplay.getByRole('button', { name: /^Remplacer le mot/ });
    await expect(replaceButtons).toHaveCount(2);

    const copyButton = nickDisplay.getByRole('button', { name: 'Copier le pseudo' });
    await expect(copyButton).toBeDefined();

    const reportButton = nickDisplay.getByRole('button', { name: /^Signaler / });
    await expect(reportButton).toBeDefined();

    // history should be displayed with current nick
    const historyContainer = page.locator('.nick-history-display');
    await expect(historyContainer).toBeVisible();
    
    const historyHeading = historyContainer.getByRole('heading', { name: 'Historique' });
    await expect(historyHeading).toBeVisible();

    // check nick words are in history
    for (const word of words) {
        await expect(historyContainer.getByText(word)).toBeVisible();
    }

    const copyHistoryButton = historyContainer.getByRole('button', { name: 'Copier le pseudo' });
    await expect(copyHistoryButton).toBeDefined();
    await expect(historyContainer.getByRole('button', { name: "Supprimer de l'historique" })).toBeDefined();
    await expect(historyContainer.getByRole('button', { name: "Signaler" })).toBeDefined();
});