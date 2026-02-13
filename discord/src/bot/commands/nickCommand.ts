import { ChatInputCommandInteraction, GuildMember, SlashCommandBuilder } from 'discord.js';
import { generateNickname } from '../services/apiClient';
import { NickRequestContext } from '../model/NickRequestContext';
import { allowedGenders, Gender } from '../model/Gender';
import { logger } from '../services/logger';

export const nickCommand = {
    data: new SlashCommandBuilder()
        .setName('nickme')
        .setDescription('Renomme un utilisateur avec un pseudo généré')
        .addUserOption(option =>
            option.setName('cible')
                  .setDescription('Utilisateur à renommer')
                  .setRequired(false)
        )
        .addIntegerOption(option =>
            option.setName('offense')
                  .setDescription('Degré d\'offense')
                  .setRequired(false)
                  .addChoices(
                      { name: '1', value: 1 },
                      { name: '5', value: 5 },
                      { name: '10', value: 10 },
                      { name: '15', value: 15 },
                      { name: '20', value: 20 }
                  ))
        .addStringOption(option =>
            option.setName('genre')
                  .setDescription('Genre du pseudo')
                  .setRequired(false)
                  .addChoices(
                      { name: 'auto', value: 'auto' },
                      { name: 'f', value: 'f' },
                      { name: 'm', value: 'm' },
                      { name: 'neutral', value: 'neutral' }
                  )),
    
    async execute(interaction: ChatInputCommandInteraction) {
        try {
            const rawGender = interaction.options.getString('genre');
            const gender: Gender = allowedGenders.includes(rawGender as Gender) ? (rawGender as Gender) : 'auto';


            const ctx: NickRequestContext = {
                userId: interaction.user.id,
                guildId: interaction.guildId!,
                offense: interaction.options.getInteger('offense') ?? 10,
                gender
            };

            const nickname = await generateNickname(ctx);

            // target user
            const targetUserIsCurrentUser = interaction.options.getUser('cible') === null;
            const targetUser = interaction.options.getUser('cible') ?? interaction.user;

            // Récupère le GuildMember pour pouvoir changer le pseudo
            const member = interaction.guild?.members.cache.get(targetUser.id) as GuildMember;
            if (!member) {
                await interaction.reply({
                    content: `Impossible de trouver cet utilisateur sur ce serveur, mais "${nickname}" aurait été un pseudo parfait.`,
                    ephemeral: true
                });
                return;
            }

            try {
                // attempt at changing nick
                await member.setNickname(nickname, `Ordonné par ${interaction.user.tag}`);
                await interaction.reply({
                    content: `Le pseudo de **${targetUser.tag}** a été changé en **${nickname}**`,
                    ephemeral: false
                });
            } catch (err) {
                logger.info({event: 'nick_change', message: 'Unable to change nick' })
                // Si on n’a pas pu changer le pseudo, on envoie un message sur le channel
                await interaction.reply(
                    `Je ne peux pas directement changer `+(targetUserIsCurrentUser ? 'ton pseudo': `le pseudo de **${targetUser.tag}**`)+` mais je recommande **${nickname}** qui ma paraît approprié.`
                );
            }
        } catch (err) {
            logger.error({event: 'nick_generation', err});
            await interaction.reply({
                content: `Erreur lors de la génération du pseudo`,
                ephemeral: true
            });
        }
    }
};
