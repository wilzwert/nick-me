import { Collection, ChatInputCommandInteraction } from 'discord.js';
import { InteractionHandler } from './InteractionHandler';
import { BotCommand } from "../model/BotCommand";

describe('InteractionHandler', () => {

  it('does nothing if interaction is not a chat input command', async () => {
    const commands = new Collection<string, BotCommand>();
    const handler = new InteractionHandler(commands);

    const interaction = {
      isChatInputCommand: jest.fn().mockReturnValue(false)
    } as unknown as ChatInputCommandInteraction;

    await handler.handle(interaction);

    // no crash = success
  });

  it('does nothing if command does not exist', async () => {
    const commands = new Collection<string, BotCommand>();
    const handler = new InteractionHandler(commands);

    const interaction = {
      isChatInputCommand: jest.fn().mockReturnValue(true),
      commandName: 'unknown'
    } as unknown as ChatInputCommandInteraction;

    await handler.handle(interaction);
  });

  it('executes the command if it exists', async () => {
    const execute = jest.fn();
    const commands = new Collection<string, BotCommand>();
    commands.set('nickme', {data: {}, execute} as unknown as BotCommand);

    const handler = new InteractionHandler(commands);

    const interaction = {
      isChatInputCommand: jest.fn().mockReturnValue(true),
      commandName: 'nickme'
    } as unknown as ChatInputCommandInteraction;

    await handler.handle(interaction);

    expect(execute).toHaveBeenCalledWith(interaction);
  });
});


