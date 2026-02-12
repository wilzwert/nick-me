import pino from "pino";

export const logger = pino({
    timestamp: pino.stdTimeFunctions.isoTime,
    base: { service: 'discord-bot' },
});
