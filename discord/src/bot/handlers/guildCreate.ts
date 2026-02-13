import { Guild } from "discord.js";
import { deployGuildCommands } from "../services/deployGuildCommands";
import { logger } from "../services/logger";

export async function guildCreate(guild: Guild) {
    logger.info(`I was invited on : ${guild.name} (${guild.id})`);
    try {
        await deployGuildCommands(guild.id);
        logger.info(`Commands deployed for ${guild.name}`);
    } catch (err) {
        logger.error({event: 'guild_commands_deploy', err});
    }
}