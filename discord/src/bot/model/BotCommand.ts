import { ChatInputCommandInteraction, SlashCommandOptionsOnlyBuilder } from "discord.js";

export interface BotCommand {
    data: SlashCommandOptionsOnlyBuilder;
    execute: (interaction: ChatInputCommandInteraction) => Promise<void>;
}