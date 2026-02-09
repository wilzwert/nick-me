import { type Nick } from '../../domain/model/Nick';
import { Button, CopyButton, Group, Text } from '@mantine/core';
import { IconCheck, IconCopy } from '@tabler/icons-react';


interface Props {
  nick: Nick;
  onClick?: () => void
}

export function CopyNickButton({ nick, onClick }: Props) {
  return (
    <CopyButton value={nick.words.map(w => w.label).join(' ')} timeout={2000}>
      {({ copied, copy }) => (
          <Button fullWidth variant="subtle" onClick={() => {copy(); if(onClick) onClick();}}>
            <Group gap="xs">
            {copied ? <IconCheck size={16} /> : <IconCopy size={16} />} 
            { copied ? <Text>Copié</Text> : <Text>Copier</Text> }
            </Group>
          </Button>
      )}
    </CopyButton>
  );
}
