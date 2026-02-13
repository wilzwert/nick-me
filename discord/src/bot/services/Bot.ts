import { Client } from 'discord.js';
import { Database } from './Database';
import { InteractionHandler } from '../handlers/InteractionHandler';
import { CreateGuildHandler } from '../handlers/CreateGuildHandler';
import { CommandDeployment } from './CommandDeployment';
import { logger } from './logger';

export class Bot {
  constructor(
    private readonly client: Client,
    private readonly db: Database,
    private readonly interactionHandler: InteractionHandler,
    private readonly createGuildHandler: CreateGuildHandler, 
    private readonly commandDeployment: CommandDeployment
  ) {}

  async start(token: string) {
    await this.db.init();

    await this.commandDeployment.deployGlobal();

    this.client.once('clientReady', () => {
      logger.info(`Connected as ${this.client.user?.tag}`);
    });
    
    this.client.on('guildCreate', guild => this.createGuildHandler.handle(guild));

    this.client.on('interactionCreate', i => this.interactionHandler.handle(i));

    await this.client.login(token);
  }
}
