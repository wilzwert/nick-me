import { ActionIcon, Card, Group, List, Popover, Stack, Text, Tooltip } from "@mantine/core";
import { useNickHistoryStore } from "../stores/nick-history.store";
import { NickHistoryElement } from "./NickHistoryElement";
import { IconInfoCircle, IconInfoSmall, IconInfoSquareRoundedFilled, IconReload } from "@tabler/icons-react";

export function NickHistory() {
  const history = useNickHistoryStore(s => s.history);
  
  if (!history.length) return null;

  return (
    <Card className="nick-history-display">
    <Stack gap={10}>
      <Group justify="center">
        <h3>Historique</h3>
        <Popover
          width={220}
          position="bottom"
          withArrow
          trapFocus
        >
          <Popover.Target>
            <ActionIcon size="xs"
              variant="subtle"
              color="gray"
              aria-label="20 éléments maximum dans l'historique, stockés dans votre navigateur"
            >
              <IconInfoCircle/>
            </ActionIcon>
          </Popover.Target>
          <Popover.Dropdown>
            <Text size="sm">
              20 éléments maximum dans l'historique, stockés dans votre navigateur
            </Text>

          </Popover.Dropdown>
        </Popover>
      </Group>

      <List
        listStyleType="none"
        spacing="xs"
        size="sm"
    >
      {history.map((nick, i) => (
          <List.Item key={i}>
            <NickHistoryElement nick={nick} />
          </List.Item>
        ))}
      </List>
    </Stack>
    </Card>
  );
}
