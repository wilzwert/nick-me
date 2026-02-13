import { nickCommand } from '../commands/nickCommand';
import { generateNickname } from '../services/apiClient';
import { logger } from '../services/logger';

jest.mock('../services/apiClient');
jest.mock('../services/logger');

const generateNicknameMock = generateNickname as jest.MockedFunction<typeof generateNickname>;

describe('nickCommand', () => {
  let interaction: any;
  let member: any;

  beforeEach(() => {
    jest.clearAllMocks();

    member = {
      setNickname: jest.fn().mockResolvedValue(undefined),
    };

    interaction = {
      user: { id: 'user1', tag: 'User#1234' },
      guildId: 'guild1',
      guild: {
        members: {
          cache: new Map([['user1', member]])
        }
      },
      options: {
        getString: jest.fn().mockReturnValue('auto'),
        getInteger: jest.fn().mockReturnValue(10),
        getUser: jest.fn().mockReturnValue(null)
      },
      reply: jest.fn().mockResolvedValue(undefined)
    };
  });

  it('changes nickname successfully for the user', async () => {
    generateNicknameMock.mockResolvedValue('Super Copain');

    await nickCommand.execute(interaction);

    expect(generateNicknameMock).toHaveBeenCalledWith({
      userId: 'user1',
      guildId: 'guild1',
      offense: 10,
      gender: 'auto'
    });

    expect(member.setNickname).toHaveBeenCalledWith('Super Copain', 'Ordonné par User#1234');

    expect(interaction.reply).toHaveBeenCalledWith({
      content: 'Le pseudo de **User#1234** a été changé en **Super Copain**',
      ephemeral: false
    });
  });

  it('replies with message if member not found', async () => {
    interaction.guild.members.cache.clear(); // no member found
    generateNicknameMock.mockResolvedValue('Super Copain');

    await nickCommand.execute(interaction);

    expect(interaction.reply).toHaveBeenCalledWith({
      content: 'Impossible de trouver cet utilisateur sur ce serveur, mais "Super Copain" aurait été un pseudo parfait.',
      ephemeral: true
    });
  });

  it('handles nickname change failure gracefully', async () => {
    generateNicknameMock.mockResolvedValue('Super Copain');
    member.setNickname.mockRejectedValue(new Error('fail'));

    await nickCommand.execute(interaction);

    expect(logger.info).toHaveBeenCalledWith({ event: 'nick_change', message: 'Unable to change nick' });
    expect(interaction.reply).toHaveBeenCalledWith(
      'Je ne peux pas directement changer ton pseudo mais je recommande **Super Copain** qui ma paraît approprié.'
    );
  });

  it('handles generateNickname error', async () => {
    generateNicknameMock.mockRejectedValue(new Error('API fail'));

    await nickCommand.execute(interaction);

    expect(logger.error).toHaveBeenCalledWith({
      event: 'nick_generation',
      err: expect.any(Error)
    });

    expect(interaction.reply).toHaveBeenCalledWith({
      content: 'Erreur lors de la génération du pseudo',
      ephemeral: true
    });
  });
});
