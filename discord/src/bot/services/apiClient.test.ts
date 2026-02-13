import fetch from 'node-fetch';
import { generateNickname } from './apiClient';
import { NickRequestContext } from '../model/NickRequestContext';

const fetchMock = fetch as jest.MockedFunction<typeof fetch>;

describe('generateNickname', () => {
  beforeEach(() => {
    jest.clearAllMocks();
  });

  it('returns the nick from API response', async () => {
    const ctx: NickRequestContext = { guildId: '1', userId: '2', offense: 5, gender: 'm' };

    const mockResponse = {
      words: [
        { label: 'Super' },
        { label: 'Rico' }
      ]
    };

    fetchMock.mockResolvedValue({
      ok: true,
      json: async () => mockResponse
    } as any);

    const result = await generateNickname(ctx);
    expect(result).toBe('Super Rico');

    // check fetch has been called
    expect(fetchMock).toHaveBeenCalledWith(
      expect.any(URL),
      expect.objectContaining({
        method: 'GET',
        headers: expect.objectContaining({
          'Content-Type': 'application/json'
        })
      })
    );
  });

  it('throws error if API response is not ok', async () => {
    const ctx: NickRequestContext = { guildId: '1', userId: '2', offense: 3, gender: 'f' };

    fetchMock.mockResolvedValue({
      ok: false,
      status: 401,
      statusText: 'Unauthorized',
      json: async () => ({})
    } as any);

    await expect(generateNickname(ctx)).rejects.toThrow('Erreur API: 401 Unauthorized');
  });

  it('throws error if API returns empty words', async () => {
    const ctx: NickRequestContext = { guildId: '1', userId: '2', offense: 2, gender: 'm' };

    fetchMock.mockResolvedValue({
      ok: true,
      json: async () => ({ words: [] })
    } as any);

    await expect(generateNickname(ctx)).rejects.toThrow('Invalid API response');
  });
});
