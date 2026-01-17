import { AppShell, Container, Stack, Group, Box, Text } from '@mantine/core';
import { useMantineColorScheme } from '@mantine/core';
import { FooterIcons } from './components/FooterIcons';

export function AppLayout({ children }: { children: React.ReactNode }) {
  const { colorScheme } = useMantineColorScheme();

  return (
    <AppShell
      padding="md"
      footer={{ height: 60 }}
    >
        <AppShell.Footer>
        <Box py="md">
          <FooterIcons />
        </Box>
        </AppShell.Footer>

        <AppShell.Main>
        <Container size="sm">
            <Stack gap={20}>{children}</Stack>
        </Container>
      </AppShell.Main>
    </AppShell>
  );
}
