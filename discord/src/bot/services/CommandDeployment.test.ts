import { Collection, REST, Routes } from "discord.js";
import { BotCommand } from "../model/BotCommand";
import { Database } from "./Database";
import { CommandDeployment } from "./CommandDeployment";

const commandMock = {
  data: {
    toJSON: jest.fn().mockReturnValue({ name: 'nickme' })
  }
} as unknown as BotCommand;

const commands = new Collection<string, BotCommand>();
commands.set('nickme', commandMock);

const restMock = {
  put: jest.fn().mockResolvedValue(undefined)
} as unknown as REST;

const dbMock = {
  getLastGlobalDeploy: jest.fn(),
  setLastGlobalDeploy: jest.fn()
} as unknown as jest.Mocked<Database>;

const deployment = new CommandDeployment(
  restMock,
  'CLIENT_ID',
  commands,
  dbMock
);

describe('CommandDeployment', () => {
    afterEach(() => {
        jest.restoreAllMocks();
    });

    it('deploys commands to a guild', async () => {
        await deployment.deployToGuild('guild-123');

        expect(restMock.put).toHaveBeenCalledWith(
            Routes.applicationGuildCommands('CLIENT_ID', 'guild-123'),
            {
            body: [{ name: 'nickme' }]
            }
        );

        expect(commandMock.data.toJSON).toHaveBeenCalled();
    });

    it('skips global deploy if last deploy is too recent', async () => {
        const now = Math.floor(Date.now() / 1000);
        dbMock.getLastGlobalDeploy.mockReturnValue(now - 100); // < 900s

        await deployment.deployGlobal();

        expect(restMock.put).not.toHaveBeenCalled();
        expect(dbMock.setLastGlobalDeploy).not.toHaveBeenCalled();
    });

    it('deploys global commands if last deploy is old enough', async () => {
        const now = Math.floor(Date.now() / 1000);
        dbMock.getLastGlobalDeploy.mockReturnValue(now - 2000);

        jest.spyOn(Date, 'now').mockReturnValue(now * 1000);

        await deployment.deployGlobal();

        expect(restMock.put).toHaveBeenCalledWith(
            Routes.applicationCommands('CLIENT_ID'),
            {
            body: [{ name: 'nickme' }]
            }
        );

        expect(dbMock.setLastGlobalDeploy).toHaveBeenCalledWith(now);
    });



});



