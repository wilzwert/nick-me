import type { OffenseLevel } from '../../domain/model/OffenseLevel';
import { OFFENSE_LEVEL_VALUES } from '../../domain/model/OffenseLevel';
import { OFFENSE_LEVEL_LABELS } from '../../domain/labels/offenseLevel.labels';
import { Box, Group, Slider, Stack, Text } from '@mantine/core';

interface Props {
  value: OffenseLevel;
  onChange: (value: OffenseLevel) => void;
}


const marks = Object.entries(OFFENSE_LEVEL_LABELS).map(([value, label]) => ({
  value: Number(value),
  label
  }
));


export function OffenseLevelGauge({ value, onChange }: Props) {
  const min = OFFENSE_LEVEL_VALUES[0];
  const max = OFFENSE_LEVEL_VALUES[OFFENSE_LEVEL_VALUES.length - 1];
  
  return (
      <Stack gap="40">
        <label htmlFor="offense-level">
        <Text size="sm">
          Niveau d'offense
        </Text>
      </label>
      
      <Slider 
        id="offense-level"
        min={min}
        max={max}
        defaultValue={5} 
        value={value}
        marks={marks}
        labelAlwaysOn={true}
        restrictToMarks
        label={(value) => 
            OFFENSE_LEVEL_LABELS[Number(value) as OffenseLevel]
        }

        onChange={e => onChange(e as OffenseLevel)}
        styles={{
          track: {
            height: 6,
            background: 'linear-gradient(to right, var(--mantine-primary-color-filled), rgb(78, 15, 37))',
          },
           bar: {
            height: 6,
            background: 'rgba(0, 0, 0, 0)',
          },
          markLabel: { display: 'none' },
          thumb: { width: 20, height: 20 },
        }}
      />
      </Stack>
  );
}
