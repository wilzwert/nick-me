import { REST, Routes } from 'discord.js';
import { config } from 'dotenv';
import { commands } from './commands/commands';
import { deployGuildCommands } from './utils/deployGuildCommands';
import { logger } from './utils/logger';

config(); // charge .env

const DISCORD_TOKEN = process.env.DISCORD_TOKEN!;
const CLIENT_ID = process.env.CLIENT_ID!; // bot ID

const TEST_GUILDS = process.env.TEST_GUILD_IDS?.split(',') || [];
const DEPLOY_GLOBAL = process.env.DEPLOY_GLOBAL === 'true';

async function deployCommands() {
    try {
        const rest = new REST({ version: '10' }).setToken(DISCORD_TOKEN);

        //  Commands deployment on test guilds
        for (const guildId of TEST_GUILDS) {
            deployGuildCommands(guildId)
        }

        // Global deployment
        if (DEPLOY_GLOBAL) {
            await rest.put(
                Routes.applicationCommands(CLIENT_ID),
                { body: commands }
            );
            logger.info('Global deployment successful');
        }    

    } catch (error) {
        logger.error({event: 'global_commands_deploy', error});
    }
}

deployCommands();
