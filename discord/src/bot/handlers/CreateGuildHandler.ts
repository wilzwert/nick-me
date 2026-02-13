import { Guild } from 'discord.js';
import { CommandDeployment } from '../services/CommandDeployment';

export class CreateGuildHandler {
  constructor(
    private readonly commandDeployment: CommandDeployment,
    private readonly logger: {
      info: (message: any) => void;
      error: (message: any) => void;
    }
  ) {}

  async handle(guild: Guild): Promise<void> {
    this.logger.info(`I was invited on : ${guild.name} (${guild.id})`);

    try {
      await this.commandDeployment.deployToGuild(guild.id);
      this.logger.info(`Commands deployed for ${guild.name}`);
    } catch (err) {
      this.logger.error({ event: 'guild_commands_deploy', err });
    }
  }
}