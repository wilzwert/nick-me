import { ChatInputCommandInteraction } from "discord.js";
import { GenerateNicknameHandler } from "../handlers/GenerateNicknameHandler";

const nicknameHandler = {
  handle: jest.fn()
} as unknown as GenerateNicknameHandler;

function createInteractionMock(overrides: Partial<ChatInputCommandInteraction> = {}) {
  return {
    user: { id: 'user1', tag: 'User#0001' },
    guildId: 'guild1',
    options: {
      getString: jest.fn(),
      getInteger: jest.fn(),
      getUser: jest.fn()
    },
    reply: jest.fn(),
    guild: {
      members: {
        cache: new Map()
      }
    },
    ...overrides
  } as unknown as ChatInputCommandInteraction;
}


import { nickCommand } from './nickCommand';

describe('nickCommand', () => {
  it('changes nickname when member exists', async () => {
    nicknameHandler.handle = jest.fn().mockResolvedValue('Super Nick');

    const setNickname = jest.fn().mockResolvedValue(undefined);
    const member = { setNickname } as any;

    const interaction = createInteractionMock();
    interaction.options.getUser = jest.fn().mockReturnValue(null);
    interaction.guild!.members.cache.set('user1', member);

    const command = nickCommand(nicknameHandler);
    await command.execute(interaction);

    expect(nicknameHandler.handle).toHaveBeenCalledWith(
      expect.objectContaining({
        userId: 'user1',
        guildId: 'guild1'
      })
    );

    expect(setNickname).toHaveBeenCalledWith(
      'Super Nick',
      expect.stringContaining('Ordonné par')
    );

    expect(interaction.reply).toHaveBeenCalledWith(
      expect.objectContaining({
        content: expect.stringContaining('Super Nick')
      })
    );
  });

  it('replies with ephemeral message if member not found', async () => {
    nicknameHandler.handle = jest.fn().mockResolvedValue('Nick');

    const interaction = createInteractionMock();
    interaction.options.getUser = jest.fn().mockReturnValue(null);

    const command = nickCommand(nicknameHandler);
    await command.execute(interaction);

    expect(interaction.reply).toHaveBeenCalledWith(
      expect.objectContaining({
        flags: expect.any(Number)
      })
    );
  });

  it('replies with recommendation when nickname change fails', async () => {
    nicknameHandler.handle = jest.fn().mockResolvedValue('Nick');

    const setNickname = jest.fn().mockRejectedValue(new Error('no perms'));
    const member = { setNickname } as any;

    const interaction = createInteractionMock();
    interaction.options.getUser = jest.fn().mockReturnValue(null);
    interaction.guild!.members.cache.set('user1', member);

    const command = nickCommand(nicknameHandler);
    await command.execute(interaction);

    expect(interaction.reply).toHaveBeenCalledWith(
      expect.stringContaining('je recommande')
    );
  });

  it('replies with error when nickname generation fails', async () => {
    nicknameHandler.handle = jest.fn().mockRejectedValue(new Error('API down'));

    const interaction = createInteractionMock();

    const command = nickCommand(nicknameHandler);
    await command.execute(interaction);

    expect(interaction.reply).toHaveBeenCalledWith(
      expect.objectContaining({
        flags: expect.any(Number)
      })
    );
  });

});