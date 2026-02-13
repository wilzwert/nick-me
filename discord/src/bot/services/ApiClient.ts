import fetch, { Response } from 'node-fetch';
import { Database } from './Database';
import { logger } from './logger';
import { NickRequestContext } from '../model/NickRequestContext';
import { Nick } from '../model/Nick';
import { ApiKey } from '../model/ApiKey';

export class ApiClient {
  constructor(
    private readonly baseUrl: string,
    private readonly internalKey: string,
    private readonly db: Database
  ) {}

  private async fetch(
    url: URL,
    method: string,
    headers: Record<string, string> = {}
  ): Promise<Response> {
    return fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
        'X-Internal-App-Key': this.internalKey,
        ...headers
      }
    });
  }

  private async fetchForGuild(
    url: URL,
    method: string,
    guildId: string
  ): Promise<Response> {
    let apiKey = this.db.getGuildKey(guildId);
    let freshKey = false;

    if(!apiKey) {
        freshKey = true;
        logger.info({ event: 'api_key_created', guildId });
        apiKey = await this.requestApiKey();
        this.db.setGuildKey(guildId, apiKey);
    }

    const res = await this.fetch(url, method, {
      'X-Api-Key': apiKey ?? ''
    });

    // 401 with an old key implies key was revoked => fatal error
    // other status and/or fresh keys implies there's something very wong => fatal error 
    if (res.status !== 401 || freshKey) {
      return res;
    }

    logger.info({ event: 'api_key_refresh', guildId });

    const newKey = await this.requestApiKey();
    await this.db.setGuildKey(guildId, newKey);

    return this.fetch(url, method, {
      'X-Api-Key': newKey
    });
  }

  async generateNickname(ctx: NickRequestContext): Promise<string> {
    const url = new URL(this.baseUrl + '/nick');
    url.searchParams.append('offenseLevel', ctx.offense.toString());
    url.searchParams.append('gender', ctx.gender);

    const res = await this.fetchForGuild(url, 'GET', ctx.guildId);
    if (!res.ok) {
      throw new Error(`API error ${res.status}`);
    }

    const nick = (await res.json()) as Nick;
    return nick.words.map(w => w.label).join(' ');
  }

  async requestApiKey(): Promise<string> {
    const url = new URL(this.baseUrl + '/key');
    const res = await this.fetch(url, 'POST');

    if (!res.ok) {
      throw new Error(`API error ${res.status}`);
    }

    const key = (await res.json()) as ApiKey;
    if (!key || !key.key) {
        throw new Error('Invalid API response');
    }
    return key.key;
  }
}
