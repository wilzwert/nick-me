import type { OffenseLevel } from '../domain/model/OffenseLevel';
import { OFFENSE_LEVEL_VALUES } from '../domain/model/OffenseLevel';
import { OFFENSE_LEVEL_LABELS } from '../domain/labels/offenseLevel.labels';

interface Props {
  value: OffenseLevel;
  onChange: (value: OffenseLevel) => void;
}

export function OffenseLevelGauge({ value, onChange }: Props) {
  const min = OFFENSE_LEVEL_VALUES[0];
  const max = OFFENSE_LEVEL_VALUES[OFFENSE_LEVEL_VALUES.length - 1];

  // Snap automatique sur les valeurs du backend
  function snap(value: number): OffenseLevel {
    return OFFENSE_LEVEL_VALUES.reduce(
      (prev, curr) =>
        Math.abs(curr - value) < Math.abs(prev - value) ? curr : prev
    );
  }

  return (
    <div>
      <input
        type="range"
        min={min}
        max={max}
        step={1}
        value={value}
        onChange={e => onChange(snap(Number(e.target.value)))}
        style={{ width: '100%' }}
      />

      {/* Display current selected label */}
      <div style={{ textAlign: 'center', marginTop: '0.5rem', fontWeight: 'bold' }}>
        {OFFENSE_LEVEL_LABELS[value]}
      </div>
    </div>
  );
}
