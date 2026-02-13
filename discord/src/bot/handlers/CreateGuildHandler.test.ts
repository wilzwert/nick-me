import { Guild } from "discord.js";
import { CommandDeployment } from "../services/CommandDeployment";
import { CreateGuildHandler } from './CreateGuildHandler';
import { logger } from "../services/logger";
jest.mock('../services/logger');

const commandDeployment = {
  deployToGuild: jest.fn()
} as unknown as CommandDeployment;

const guild = {
  id: 'guild-123',
  name: 'Test Guild'
} as unknown as Guild;


describe('CreateGuildHandler', () => {
  it('deploys commands when joining a guild', async () => {
    commandDeployment.deployToGuild = jest.fn().mockResolvedValue(undefined);

    const handler = new CreateGuildHandler(commandDeployment, logger);
    await handler.handle(guild);

    expect(commandDeployment.deployToGuild).toHaveBeenCalledWith('guild-123');
    expect(logger.info).toHaveBeenCalled();
  });

  it('logs error if command deployment fails', async () => {
    commandDeployment.deployToGuild = jest.fn().mockRejectedValue(new Error('boom'));

    const handler = new CreateGuildHandler(commandDeployment, logger);

    await expect(handler.handle(guild)).resolves.not.toThrow();

    expect(logger.error).toHaveBeenCalledWith(
        expect.objectContaining({
        event: 'guild_commands_deploy'
        })
    );
    });

});
