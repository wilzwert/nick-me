import { describe, it, expect, jest } from '@jest/globals';
import { ChatInputCommandInteraction, Collection, Interaction, SlashCommandOptionsOnlyBuilder } from 'discord.js';
import { handleInteraction } from './handleInteraction';
import { BotCommand } from '../commands/commands';

describe('handleInteraction', () => {
  it('does nothing if interaction is not a chat input command', async () => {
    const mockInteraction = {
      isChatInputCommand: () => false
    } as unknown as Interaction;

    const commands = new Collection<string, BotCommand>();

    await handleInteraction(mockInteraction, commands);
  });

  it('executes the command if interaction is a known chat input command', async () => {
    const executeMock: jest.MockedFunction<(interaction: ChatInputCommandInteraction) => Promise<void>> = jest.fn();
    const commandName = 'testCommand';

    const mockInteraction = {
      isChatInputCommand: () => true,
      commandName
    } as unknown as ChatInputCommandInteraction;

    const commands = new Collection<string, BotCommand>();
    commands.set(commandName, { data: {} as SlashCommandOptionsOnlyBuilder, execute: executeMock });

    await handleInteraction(mockInteraction, commands);

    expect(executeMock).toHaveBeenCalledWith(mockInteraction);
  });

  it('does nothing if command is unknown', async () => {
    const mockInteraction = {
      isChatInputCommand: () => true,
      commandName: 'unknown'
    } as unknown as Interaction;

    const commands = new Collection<string, BotCommand>();
    const executeMock: jest.MockedFunction<(interaction: ChatInputCommandInteraction) => Promise<void>> = jest.fn();
    commands.set('testCommand', { data: {} as SlashCommandOptionsOnlyBuilder, execute: executeMock });

    await handleInteraction(mockInteraction, commands);

    expect(executeMock).not.toHaveBeenCalled();
  });
});
