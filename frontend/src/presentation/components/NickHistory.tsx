import { Button, Card, Group, List, Paper, Stack, Text, ThemeIcon, Tooltip } from "@mantine/core";
import { useNickHistoryStore } from "../stores/nick-history.store";
import { useNickStore } from "../stores/nick.store";
import { CopyNickButton } from "./CopyNickButton";
import { IconHistory, IconX } from "@tabler/icons-react";
import { ReportNickButton } from "./ReportNickButton";

export function NickHistory() {
  const history = useNickHistoryStore(s => s.history);
  const removeFromHistory = useNickHistoryStore(s => s.removeNick);
  const setNick = useNickStore(s => s.setNick);

  if (!history.length) return null;

  return (
    <Card className="nick-history-display">
    <Stack gap={10}>
      <h3>Historique</h3>
      <List
      spacing="xs"
      size="sm"
      center
      icon={
        <ThemeIcon size={24} radius="xl">
          <IconHistory size={16} />
        </ThemeIcon>
      }
    >
      {history.map((nick, i) => (
          <List.Item key={i}>
            <Group>
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
                }}
              >
            <Text>
              {nick.words.map(w => w.label).join(' ')}
            </Text>
            </Paper>
            <CopyNickButton nick={nick} />
                <Button variant="subtle" size="xs" onClick={() => {
                  removeFromHistory(nick);
                }}>
                  <Tooltip label="Supprimer de l'historique" withArrow position="bottom">
                    <IconX aria-label="Supprimer"/>
                  </Tooltip>
                </Button>
            <ReportNickButton nick={nick} />
            </Group>
          </List.Item>
        ))}
      </List>
    </Stack>
    </Card>
  );
}
