import { GENDER_LABELS } from '../../domain/labels/gender.labels';
import { GENDER_ORDER } from '../../domain/model/Gender';
import { useGenerateNick } from '../../application/generateNick';
import { OffenseLevelGauge } from './OffenseLevelJauge';
import { useCriteriaStore } from '../stores/criteria.store';
import { useExecuteWithAltcha } from '../../infrastructure/altcha.service';
import { Box, Button, Card, Group, LoadingOverlay, Radio, Stack } from '@mantine/core';
import { useState } from 'react';


export function NickForm() {
  const [isSubmitted, setSubmitted] = useState(false);
  const criteria = useCriteriaStore(s => s.criteria);
  const setCriteria = useCriteriaStore(s => s.setCriteria);
  const executeWithAltcha = useExecuteWithAltcha();

  const { mutate: generateNick, isPending } = useGenerateNick();

  return (
  
    <Card>
    <Box pos="relative">
      <LoadingOverlay visible={isPending || isSubmitted} zIndex={1000} color='pink' overlayProps={{ radius: "sm", blur: 2, opacity: 0.5 }} />
      <form 
       onSubmit={e => {
         e.preventDefault();
         setSubmitted(true);
         executeWithAltcha(() => {
          setSubmitted(false);
           generateNick({ gender: criteria.gender, offenseLevel: criteria.offenseLevel });
         });
       }}>
       <Stack gap={20}>
          <Radio.Group
            name="gender"
            label="Choisissez un genre"
            value={criteria.gender}
          >
            <Group mt="xs" justify='center'>
              {GENDER_ORDER.map(g => (
                <Radio key={g} value={g} checked={criteria.gender === g} label={GENDER_LABELS[g]} onChange={() => { setCriteria({gender: g, offenseLevel: criteria.offenseLevel}); }}/>
              ))}
            </Group>
          </Radio.Group>

          <OffenseLevelGauge value={criteria.offenseLevel} onChange={(offenseLevel) => {setCriteria({gender: criteria.gender, offenseLevel}); }} />
        <Box>
        <Button 
          type="submit" 
          disabled={isPending || isSubmitted}
        >Go</Button>
        </Box>
      </Stack>
      </form>
    </Box>
    </Card>
  );
}
