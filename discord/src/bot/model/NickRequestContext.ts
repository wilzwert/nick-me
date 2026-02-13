import { Gender } from "./Gender";

export interface NickRequestContext {
    guildId: string,
    userId: string,
    offense: number;
    gender: Gender;
    apiKey: string;
}