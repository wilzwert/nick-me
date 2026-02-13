import { Collection, REST, Routes } from "discord.js";
import { logger } from "./logger";
import { BotCommand } from "../model/BotCommand";
import { Database } from "./Database";

export class CommandDeployment {
  constructor(
    private readonly rest: REST,
    private readonly clientId: string,
    private readonly commands: Collection<string, BotCommand>,
    private readonly db: Database
  ) {}

  async deployToGuild(guildId: string): Promise<void> {
    await this.rest.put(
      Routes.applicationGuildCommands(this.clientId, guildId),
      { body: Array.from(this.commands.values()).map(cmd => cmd.data.toJSON()) }
    );
    logger.info(`Commands deployed for guild ${guildId}`);
  }

  async deployGlobal(): Promise<void> {
    const lastDeploy = await this.db.getLastGlobalDeploy();
    const now = Math.floor(Date.now() / 1000);
    logger.debug('Last deploy occurred at '+lastDeploy);

    if (now - lastDeploy < 900) {
      logger.info('Global commands deploy skipped to avoid rate limit');
      return;
    }

    await this.rest.put(
      Routes.applicationCommands(this.clientId),
      { body: Array.from(this.commands.values()).map(cmd => cmd.data.toJSON()) }
    );
    logger.info('Global commands deployed');
    this.db.setLastGlobalDeploy(now);
  }
}
