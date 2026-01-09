import { useState } from 'react';
import { GENDER_LABELS } from '../domain/labels/gender.labels';
import { type Gender, GENDER_ORDER } from '../domain/model/Gender';
import { useGenerateNick } from '../application/generateNick';
import { OffenseLevelGauge } from './OffenseLevelJauge';
import type { OffenseLevel } from '../domain/model/OffenseLevel';

export function NickForm() {
  const { mutate: generateNick, isPending } = useGenerateNick();

  const [gender, setGender] = useState<Gender>('NEUTRAL');
  const [offenseLevel, setOffenseLevel] = useState<OffenseLevel>(5);


  return (
    <form
      onSubmit={e => {
        e.preventDefault();
        generateNick({ gender, offenseLevel });
      }}
    >
      {/* Gender */}
      <fieldset>
        <legend>Genre</legend>
        {GENDER_ORDER.map(g => (
          <label key={g}>
            <input
              type="radio"
              name="gender"
              checked={gender === g}
              onChange={() => setGender(g)}
            />
            {GENDER_LABELS[g]}
          </label>
        ))}
      </fieldset>

      {/* OffenseLevel Gauge */}
      <fieldset>
        <legend>Niveau d'offense</legend>
        <OffenseLevelGauge value={offenseLevel} onChange={setOffenseLevel} />
        
      </fieldset>

      <button disabled={isPending}>Go</button>
    </form>
  );
}
