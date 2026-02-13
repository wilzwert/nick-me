import { describe, it, expect, jest, beforeEach } from '@jest/globals';
import { guildCreate } from './guildCreate';
jest.mock('../services/deployGuildCommands');
import { deployGuildCommands } from '../services/deployGuildCommands';
jest.mock('../services/logger', () => ({
  logger: {
    info: jest.fn(),
    error: jest.fn(),
  },
}));
import { logger } from '../services/logger';
import { Guild } from 'discord.js';

// Mock des modules


describe('guildCreate', () => {
  let guild: Partial<Guild>;

  beforeEach(() => {
    jest.clearAllMocks();

    guild = {
      id: '123',
      name: 'TestGuild'
    } as Guild;
  });

  it('logs info and calls deployGuildCommands', async () => {
    // @ts-ignore mock
    deployGuildCommands.mockResolvedValue(undefined);

    await guildCreate(guild as Guild);

    expect(logger.info).toHaveBeenCalledWith('I was invited on : TestGuild (123)');
    expect(deployGuildCommands).toHaveBeenCalledWith('123');
    expect(logger.info).toHaveBeenCalledWith('Commands deployed for TestGuild');
  });

  it('logs error if deployGuildCommands throws', async () => {
    const error = new Error('fail');
    // @ts-ignore mock
    deployGuildCommands.mockRejectedValue(error);

    await guildCreate(guild as Guild);

    expect(logger.info).toHaveBeenCalledWith('I was invited on : TestGuild (123)');
    expect(logger.error).toHaveBeenCalledWith({ event: 'guild_commands_deploy', err: error });
  });
});
