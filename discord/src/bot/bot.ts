import { Client, GatewayIntentBits, Interaction, Guild } from 'discord.js';
import { DISCORD_TOKEN } from './config/dotenv';
import { logger } from './services/logger';
import { commands } from './commands/commands';
import { handleInteraction } from './handlers/handleInteraction';
import { guildCreate } from './handlers/guildCreate';

const client = new Client({ intents: [GatewayIntentBits.Guilds] });

client.once('clientReady', () => {
    logger.info({event: 'client_connected', message: `Connected as ${client.user?.tag}`});
});

client.on('guildCreate', async (guild: Guild) => {
    guildCreate(guild);
});

client.on('interactionCreate', async (interaction: Interaction) => {
    handleInteraction(interaction, commands);
});

client.login(DISCORD_TOKEN);
