import fetch from 'node-fetch';
import { ApiClient } from './ApiClient';
import { Database } from './Database';

const dbMock = {
  getGuildKey: jest.fn(),
  setGuildKey: jest.fn()
} as unknown as jest.Mocked<Database>;

const mockedFetch = fetch as jest.MockedFunction<typeof fetch>;

describe('ApiClient', () => {
    it('creates an api key when none exists', async () => {
        dbMock.getGuildKey.mockReturnValue(undefined);

        mockedFetch
            // requestApiKey
            .mockResolvedValueOnce({
            ok: true,
            status: 200,
            json: async () => ({ key: 'new-key' })
            } as any)
            // generateNickname
            .mockResolvedValueOnce({
            ok: true,
            status: 200,
            json: async () => ({
                words: [{ label: 'Bougresse' }, { label: 'Incandescente' }]
            })
            } as any);

        const client = new ApiClient(
            'http://api',
            'internal-key',
            dbMock
        );

        const result = await client.generateNickname({
            guildId: 'guild-1',
            userId: 'user-1',
            offense: 10,
            gender: 'auto'
        });

        expect(result).toBe('Bougresse Incandescente');
        expect(dbMock.setGuildKey).toHaveBeenCalledWith('guild-1', 'new-key');
    });

    it('refreshes api key on 401', async () => {
        dbMock.getGuildKey.mockReturnValue('old-key');

        mockedFetch
            // first nick call → 401
            .mockResolvedValueOnce({ ok: false, status: 401 } as any)
            // requestApiKey
            .mockResolvedValueOnce({
                ok: true,
                status: 200,
                json: async () => ({ key: 'new-key' })
            } as any)
            // retry nick
            .mockResolvedValueOnce({
                ok: true,
                status: 200,
                json: async () => ({
                    words: [{ label: 'Test' }]
                })
            } as any);

        const client = new ApiClient('http://api', 'internal', dbMock);

        const nick = await client.generateNickname({
            guildId: 'guild',
            userId: 'user',
            offense: 5,
            gender: 'auto'
        });

        expect(nick).toBe('Test');
        expect(dbMock.setGuildKey).toHaveBeenCalledWith('guild', 'new-key');
    });
});
