import { useState } from "react";
import type { Nick } from "../../domain/model/Nick";
import { Button, Group, Menu, Paper, Stack, Text } from "@mantine/core";
import { useNickStore } from "../stores/nick.store";
import { IconDotsVertical } from "@tabler/icons-react";
import { CopyNickButton } from "./CopyNickButton";
import { RemoveNickFromHistoryButton } from "./RemoveNickFromHistoryButton";
import { ReportNickButton } from "./ReportNickButton";
import styles from './NickHistoryElement.module.css';

interface Props {
  nick: Nick;
}


export function NickHistoryElement({ nick }: Props) {
    const [menuOpened, setMenuOpened] = useState(false);
    const setNick = useNickStore(s => s.setNick);

    const closeMenu =(timeout: number|null) => {
        if (null === timeout) {
            setMenuOpened(false);
        }
        else {
            setTimeout(() => setMenuOpened(false), timeout);
        }
    };

    return (
      <Group wrap="nowrap" className={menuOpened ? styles.selected : ''}>
        <Paper
          component="button"
          type="button"
          onClick={
          () => {
              setNick(nick);
          }
        }
          radius="sm"
          p="xs"
          shadow="xs"
          style={{
            display: 'flex',
            alignItems: 'center',
            gap: 8,
            cursor: 'pointer', 
            border: 'none',
            whiteSpace: 'normal'
          }}
        >
        <Text>
          {nick.words.map(w => w.label).join(' ')}
        </Text>
      </Paper>
              
      <Menu 
          shadow="md" 
          width={220} 
          position="bottom-end" 
          opened={menuOpened} 
          onChange={setMenuOpened}
          menuItemTabIndex={0}
          id={'nick'+nick.id}
          withinPortal={import.meta.env.MODE === 'test' ? false  : undefined}
      >
        <Menu.Target>
          <Button variant="subtle" aria-label="Actions sur le pseudo" style={{flexShrink: 0}}>
            <IconDotsVertical size={16}/>
          </Button>
        </Menu.Target>

        <Menu.Dropdown>
          <Stack gap="xs">
            <CopyNickButton onClick={() => closeMenu(1000)} nick={nick} />
            <RemoveNickFromHistoryButton onClick={() => closeMenu(null)} nick={nick} />
            <ReportNickButton onClick={() => closeMenu(null)} nick={nick} />
            </Stack>
        </Menu.Dropdown>
      </Menu>
    </Group>
  )


}