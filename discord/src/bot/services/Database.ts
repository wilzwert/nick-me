export interface Database {
  init(): Promise<void>;
  getGuildKey(guildId: string): string | undefined;
  setGuildKey(guildId: string, key: string): Promise<void>;
  getLastGlobalDeploy(): number;
  setLastGlobalDeploy(lastGlobalDeploy: number): Promise<void>;
}