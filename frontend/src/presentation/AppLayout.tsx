import { AppShell, Container, Stack, Box } from '@mantine/core';
import { useNickStore } from './stores/nick.store';
import { useEffect, useRef, useState } from 'react';
import './AppLayout.css';
import { Footer } from './components/Footer';

const BACKGROUND_COUNT: number=3;

export function AppLayout({ children }: { children: React.ReactNode }) {
    const nick = useNickStore(s => s.nick);
    const [bgIndex, setBgIndex] = useState(0);
    const prevNickRef = useRef<number | null>(null);
    
    useEffect(() => {
        if (nick && nick.id !== prevNickRef.current) {
            setBgIndex((i) => (i + 1) % BACKGROUND_COUNT);
            prevNickRef.current = nick!.id;
        }
    }, [nick]); 
    
  return (
    <AppShell
      padding="md"
      footer={{ height: 60 }}
    >
        <AppShell.Footer>
          <Box py="md">
            <Footer />
          </Box>
        </AppShell.Footer>
        <AppShell.Main
            className={`main main-${bgIndex}`}
        >
            <Container size="sm">
                <Stack gap={20}>{children}</Stack>
            </Container>
      </AppShell.Main>
      
    </AppShell>
  );
}
