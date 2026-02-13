import { Low } from 'lowdb';
import { JSONFile } from 'lowdb/node';
import { Database } from './Database';

export interface Data {
  guilds: Record<string, string>;
  meta: {
    lastGlobalDeploy?: number;
  }
}

export class LowDatabase implements Database {
  private db: Low<Data>;

  constructor(filePath = 'data/guildKeys.json') {
    const adapter = new JSONFile<Data>(filePath);
    this.db = new Low<Data>(adapter, { guilds: {}, meta: {} });
  }

  async init() {
    await this.db.read();
    this.db.data ||= { guilds: {}, meta: {} };
    this.db.data.guilds ||= {};
    this.db.data.meta ||= {lastGlobalDeploy: 0};
    await this.db.write();
  }

  getGuildKey(guildId: string): string | undefined {
    return this.db.data?.guilds[guildId];
  }

  async setGuildKey(guildId: string, key: string) {
    this.db.data!.guilds[guildId] = key;
    await this.db.write();
  }

  getLastGlobalDeploy(): number  {
    return this.db.data?.meta?.lastGlobalDeploy ?? 0;
  }

  async setLastGlobalDeploy(lastGlobalDeploy: number)  {
    this.db.data.meta.lastGlobalDeploy  = lastGlobalDeploy;
    await this.db.write();
  }
}
