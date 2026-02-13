import { GenerateNicknameHandler } from './GenerateNicknameHandler';
import { ApiClient } from '../services/ApiClient';
import { NickRequestContext } from '../model/NickRequestContext';

const apiClient = {
  generateNickname: jest.fn()
} as unknown as ApiClient;



describe('GenerateNicknameHandler', () => {
  it('delegates nickname generation to ApiClient', async () => {
    apiClient.generateNickname = jest.fn().mockResolvedValue('Cool Nick');

    const handler = new GenerateNicknameHandler(apiClient);

    const ctx = {
      userId: 'user1',
      guildId: 'guild1',
      offense: 10,
      gender: 'auto'
    } as unknown as NickRequestContext;

    const result = await handler.handle(ctx);

    expect(apiClient.generateNickname).toHaveBeenCalledWith(ctx);
    expect(result).toBe('Cool Nick');
  });
});

