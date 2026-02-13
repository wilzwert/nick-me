import { requestApiKey } from './apiClient';
import db from './db';
import { logger } from './logger';

/**
 * Retrieves or create an api key
 * @param {string} guildId 
 * @returns {string} apiKey
 */
export async function retrieveOrCreateKey(guildId: string): Promise<string> {
  await db.read();

  if (db.data.guilds[guildId]) {
    return db.data.guilds[guildId];
  }

  // get a new API Key
  const apiKey = await requestApiKey();
  logger.info({event: 'key_created', message: 'Got Key '+apiKey});
  db.data.guilds[guildId] = apiKey;
  await db.write();

  return apiKey;
}
