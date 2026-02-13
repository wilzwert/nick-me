import { config } from 'dotenv';
config();

export const DISCORD_TOKEN = process.env.DISCORD_TOKEN!;
export const API_BASE_URL = process.env.API_BASE_URL!;
export const INTERNAL_APP_KEY = process.env.INTERNAL_APP_KEY!;
