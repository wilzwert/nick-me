import { GENDER_LABELS } from '../../domain/labels/gender.labels';
import { GENDER_ORDER } from '../../domain/model/Gender';
import { useGenerateNick } from '../../application/generateNick';
import { OffenseLevelGauge } from './OffenseLevelJauge';
import { useCriteriaStore } from '../stores/criteria.store';
import { useExecuteWithAltcha } from '../../infrastructure/altcha.service';


export function NickForm() {
  const criteria = useCriteriaStore(s => s.criteria);
  const setCriteria = useCriteriaStore(s => s.setCriteria);
  const executeWithAltcha = useExecuteWithAltcha();

  const { mutate: generateNick, isPending } = useGenerateNick();

  return (
    <form
      onSubmit={e => {
        e.preventDefault();
        executeWithAltcha(() => {
          generateNick({ gender: criteria.gender, offenseLevel: criteria.offenseLevel });
        });
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
              checked={criteria.gender === g}
              onChange={() => { setCriteria({gender: g, offenseLevel: criteria.offenseLevel}); }}
            />
            {GENDER_LABELS[g]}
          </label>
        ))}
      </fieldset>

      {/* OffenseLevel Gauge */}
      <fieldset>
        <legend>Niveau d'offense</legend>
        <OffenseLevelGauge value={criteria.offenseLevel} onChange={(offenseLevel) => {setCriteria({gender: criteria.gender, offenseLevel}); }} />
        
      </fieldset>

      <button disabled={isPending}>Go</button>
    </form>
  );
}
