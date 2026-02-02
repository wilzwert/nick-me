import { type Nick } from '../../domain/model/Nick';
import { Button, Modal, Tooltip } from '@mantine/core';
import { IconReport } from '@tabler/icons-react';
import { useState } from 'react';
import { ReportForm } from './ReportForm';


interface Props {
  nick: Nick;
}

export function ReportNickButton({ nick }: Props) {
  const [reporting, setReporting] = useState(false);

  return (
    <>
    <Button variant="subtle" size="xs" onClick={() => setReporting(true)}>
      <Tooltip label='Signaler' withArrow position="bottom">
          <IconReport size={16} />
      </Tooltip>
    </Button>
    <Modal opened={reporting} onClose={() => setReporting(false)} title={`Signaler "${nick.words.map(w => w.label).join(' ')}"`}>
      <ReportForm onClose={() => setReporting(false)} nickId={nick.id}/>
    </Modal>
    </>
  );
}
