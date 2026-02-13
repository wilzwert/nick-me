import { deployGuildCommands } from '../services/deployGuildCommands';
jest.mock('../services/logger');
import { logger } from '../services/logger';
import { commands } from '../commands/commands';

// Mock REST
const putMock = jest.fn();
jest.mock('discord.js', () => {
  const original = jest.requireActual('discord.js');
  return {
    ...original,
    REST: jest.fn().mockImplementation(() => ({
      setToken: jest.fn().mockReturnThis(),
      put: putMock
    })),
    Routes: original.Routes
  };
});

describe('deployGuildCommands', () => {
  const guildId = '1234';

  beforeEach(() => {
    jest.clearAllMocks();
  });

  it('calls REST.put with the correct parameters and logs info', async () => {
    putMock.mockResolvedValue(undefined);

    await deployGuildCommands(guildId);

    // check puts was called with body
    expect(putMock).toHaveBeenCalledWith(
      expect.any(String),
      { body: commands }
    );

    // check logger.info has been called
    expect(logger.info).toHaveBeenCalledWith({
      event: `Test guild commands deployment successful for ${guildId}`
    });
  });

  it('propagate error if REST.put rejects', async () => {
    const error = new Error('Discord API failure');
    putMock.mockRejectedValue(error);

    await expect(deployGuildCommands(guildId)).rejects.toThrow('Discord API failure');
  });
});
