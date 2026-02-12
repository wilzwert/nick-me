import { Client, GatewayIntentBits, Collection, ChatInputCommandInteraction, SlashCommandOptionsOnlyBuilder } from 'discord.js';
import { DISCORD_TOKEN } from './config/dotenv';
import { nickCommand } from './commands/nickCommand';
import { deployGuildCommands } from './utils/deployGuildCommands';
import { logger } from './utils/logger';

const client = new Client({ intents: [GatewayIntentBits.Guilds] });

// Commands
interface BotCommand {
    data: SlashCommandOptionsOnlyBuilder;
    execute: (interaction: ChatInputCommandInteraction) => Promise<void>;
}
const commands = new Collection<string, BotCommand>();
commands.set(nickCommand.data.name, nickCommand);

client.once('clientReady', () => {
    logger.info({event: 'client_connected', message: `Connected as ${client.user?.tag}`});
});

client.on('guildCreate', async (guild) => {
    logger.info(`I was invited on : ${guild.name} (${guild.id})`);
    try {
        await deployGuildCommands(guild.id);
        logger.info(`Commands deployed for ${guild.name}`);
    } catch (err) {
        logger.error({event: 'guild_commands_deploy', err});
    }
});


client.on('interactionCreate', async interaction => {
    if (!interaction.isChatInputCommand()) return;

    const command = commands.get(interaction.commandName);
    if (!command) return;

    await command.execute(interaction);
});

client.login(DISCORD_TOKEN);
