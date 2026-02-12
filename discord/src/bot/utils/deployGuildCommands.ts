const DISCORD_TOKEN = process.env.DISCORD_TOKEN!;
const CLIENT_ID = process.env.CLIENT_ID!; // bot ID
import { REST, Routes } from "discord.js";
import { commands } from "../commands/commands";
import { logger } from "./logger";

export async function deployGuildCommands(guildId: string): Promise<void>
{
    const rest = new REST({ version: '10' }).setToken(DISCORD_TOKEN);
    await rest.put(
        Routes.applicationGuildCommands(CLIENT_ID, guildId),
        { body: commands }
    );
    logger.info({event: `Test guild commands deployment successful for ${guildId}`});
}