import { Bot } from './Bot';
import { Client } from 'discord.js';
import { Database } from './Database';
import { InteractionHandler } from '../handlers/InteractionHandler';
import { CreateGuildHandler } from '../handlers/CreateGuildHandler';
import { CommandDeployment } from './CommandDeployment';

jest.mock('./logger'); // si tu veux éviter les logs réels

describe('Bot', () => {
  let clientMock: Partial<Client>;
  let dbMock: Partial<Database>;
  let interactionHandlerMock: Partial<InteractionHandler>;
  let createGuildHandlerMock: Partial<CreateGuildHandler>;
  let commandDeploymentMock: Partial<CommandDeployment>;
  let bot: Bot;

  beforeEach(() => {
    clientMock = {
      once: jest.fn(),
      on: jest.fn(),
      login: jest.fn().mockResolvedValue('token')
    };

    dbMock = {
      init: jest.fn().mockResolvedValue(undefined)
    };

    interactionHandlerMock = {
      handle: jest.fn()
    };

    createGuildHandlerMock = {
      handle: jest.fn()
    };

    commandDeploymentMock = {
      deployGlobal: jest.fn().mockResolvedValue(undefined)
    };

    bot = new Bot(
      clientMock as any,
      dbMock as any,
      interactionHandlerMock as any,
      createGuildHandlerMock as any,
      commandDeploymentMock as any
    );
  });

  it('should initialize db and deploy global commands on start', async () => {
    await bot.start('fake-token');

    expect(dbMock.init).toHaveBeenCalled();
    expect(commandDeploymentMock.deployGlobal).toHaveBeenCalled();
    expect(clientMock.once).toHaveBeenCalledWith(
      'clientReady',
      expect.any(Function)
    );
    expect(clientMock.on).toHaveBeenCalledWith(
      'guildCreate',
      expect.any(Function)
    );
    expect(clientMock.on).toHaveBeenCalledWith(
      'interactionCreate',
      expect.any(Function)
    );
    expect(clientMock.login).toHaveBeenCalledWith('fake-token');
  });
});
