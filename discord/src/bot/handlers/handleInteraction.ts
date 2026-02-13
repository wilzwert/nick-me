import { Collection, ChatInputCommandInteraction, Interaction } from 'discord.js';
import { BotCommand } from '../commands/commands';

export async function handleInteraction(
  interaction: Interaction,
  commands: Collection<string, BotCommand>
) {
  if (!interaction.isChatInputCommand()) return;

  const command = commands.get(interaction.commandName);
  if (!command) return;

  await command.execute(interaction);
}
