import { type Nick } from '../../domain/model/Nick';
import { Button, Group, Text } from '@mantine/core';
import { IconReport } from '@tabler/icons-react';
import { useReportNickStore } from '../stores/report-nick.store';


interface Props {
  nick: Nick;
  onClick?: () => void
}

export function ReportNickButton({ nick, onClick }: Props) {
  const setNick = useReportNickStore(s => s.setNick);

  return (
    <>
    <Button fullWidth variant="subtle" size="xs" onClick={() => {setNick(nick); if (onClick) onClick();}}>
        <Group gap="xs"><IconReport size={16} /> <Text>Signaler</Text></Group>
    </Button>
    </>
  );
}
