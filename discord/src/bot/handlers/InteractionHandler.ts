import { Collection, Interaction } from 'discord.js';
import { BotCommand } from '../model/BotCommand';

export class InteractionHandler {
  constructor(
    private readonly commands: Collection<string, BotCommand>
  ) {}

  async handle(interaction: Interaction): Promise<void> {
    if (!interaction.isChatInputCommand()) return;

    const command = this.commands.get(interaction.commandName);
    if (!command) return;

    await command.execute(interaction);
  }
}
