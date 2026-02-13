import { ChatInputCommandInteraction, Collection, SlashCommandOptionsOnlyBuilder } from "discord.js";
import { nickCommand } from "./nickCommand";

export interface BotCommand {
    data: SlashCommandOptionsOnlyBuilder;
    execute: (interaction: ChatInputCommandInteraction) => Promise<void>;
}
export const commands = new Collection<string, BotCommand>();
commands.set(nickCommand.data.name, nickCommand);