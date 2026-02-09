import { type Nick } from '../../domain/model/Nick';
import { useNickHistoryStore } from "../stores/nick-history.store";
import { Button, Group, Text } from '@mantine/core';
import { IconX } from '@tabler/icons-react';

interface Props {
  nick: Nick;
  onClick: () => void
}

export function RemoveNickFromHistoryButton({ nick, onClick }: Props) {
  const removeFromHistory = useNickHistoryStore(s => s.removeNick);


  return (
    <Button fullWidth variant="subtle" size="xs" onClick={() => {
        removeFromHistory(nick);
        onClick()
      }}>
          <Group gap="xs"><IconX aria-label="Supprimer"/> <Text>Supprimer</Text></Group>
    </Button>
  )
}
