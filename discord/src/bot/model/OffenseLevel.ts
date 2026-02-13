export const OFFENSE_LEVEL_VALUES = [1, 5, 10, 15, 20] as const;

export type OffenseLevel = typeof OFFENSE_LEVEL_VALUES[number];

export const OFFENSE_LEVEL_LABELS = {
  1: 'Doux',
  5: 'Normal',
  10: 'Relevé',
  15: 'Épicé',
  20: 'Maximum',
} as const;

export type OffenseLevelLabel = typeof OFFENSE_LEVEL_LABELS[OffenseLevel];

export const OFFENSE_LEVEL_CHOICES = OFFENSE_LEVEL_VALUES.map(value => ({
  name: OFFENSE_LEVEL_LABELS[value],
  value,
}));

