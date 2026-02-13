import { Client, Collection, GatewayIntentBits, REST } from "discord.js";
import { API_BASE_URL, CLIENT_ID, DISCORD_TOKEN, INTERNAL_APP_KEY } from "./config/dotenv";
import { LowDatabase } from "./services/LowDatabase";
import { ApiClient } from "./services/ApiClient";
import { Bot } from "./services/Bot";
import { InteractionHandler } from "./handlers/InteractionHandler";
import { CreateGuildHandler } from "./handlers/CreateGuildHandler";
import { logger } from "./services/logger";
import { CommandDeployment } from "./services/CommandDeployment";
import { BotCommand } from "./model/BotCommand";
import { nickCommand } from "./commands/nickCommand";
import { GenerateNicknameHandler } from "./handlers/GenerateNicknameHandler";

const client = new Client({ intents: [GatewayIntentBits.Guilds] });
const db = new LowDatabase();
const apiClient = new ApiClient(API_BASE_URL, INTERNAL_APP_KEY, db);

// commands
const nick = nickCommand(new GenerateNicknameHandler(apiClient))
const commands: Collection<string, BotCommand> = new Collection([
    [nick.data.name, nick]
]);
// command interaction handler
const interactionHandler = new InteractionHandler(commands);

// command deployment service
const rest = new REST({ version: '10' }).setToken(DISCORD_TOKEN);
const commandDeployment = new CommandDeployment(rest, CLIENT_ID, commands, db);

const createGuildHandler = new CreateGuildHandler(commandDeployment, logger);

const bot = new Bot(
  client,
  db,
  interactionHandler,
  createGuildHandler,
  commandDeployment
);

bot.start(DISCORD_TOKEN);