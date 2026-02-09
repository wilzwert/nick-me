import { Card, List, Stack } from "@mantine/core";
import { useNickHistoryStore } from "../stores/nick-history.store";
import { NickHistoryElement } from "./NickHistoryElement";

export function NickHistory() {
  const history = useNickHistoryStore(s => s.history);
  
  if (!history.length) return null;

  return (
    <Card className="nick-history-display">
    <Stack gap={10}>
      <h3>Historique</h3>
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
