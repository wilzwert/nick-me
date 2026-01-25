import { type Nick } from '../../domain/model/Nick';
import { ActionIcon, CopyButton, Tooltip } from '@mantine/core';
import { IconCheck, IconCopy } from '@tabler/icons-react';


interface Props {
  nick: Nick;
  className?: string;
}

export function CopyNickButton({ nick }: Props) {
  return (
    <CopyButton value={nick.words.map(w => w.label).join(' ')} timeout={2000}>
      {({ copied, copy }) => (
      <Tooltip label={copied ? 'Copié' : 'Copier'} withArrow position="bottom">
          <ActionIcon color={copied ? 'teal' : 'gray'} variant="subtle" onClick={copy}>
            {copied ? <IconCheck size={16} /> : <IconCopy size={16} />}
          </ActionIcon>
        </Tooltip>
      )}
    </CopyButton>
  );
}
