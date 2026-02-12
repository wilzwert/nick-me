export const OFFENSE_LEVEL_VALUES = [1, 5, 10, 15, 20] as const;

export type OffenseLevel = typeof OFFENSE_LEVEL_VALUES[number];