import { useNickStore } from '../stores/nick.store';
import { type Word } from '../../domain/model/Word';
import { useReplaceWord } from '../../application/replaceWord';
import { CopyNickButton } from './CopyNickButton';
import { useExecuteWithAltcha } from '../../infrastructure/altcha.service';
import { ActionIcon, Box, Card, Group, LoadingOverlay, Paper, Text } from '@mantine/core';
import { IconReload } from '@tabler/icons-react';
import { useState } from 'react';
import { ReportNickButton } from './ReportNickButton';
import styles from './Nick.module.css';

export function Nick() {
  const nick = useNickStore(s => s.nick);
  const setNick = useNickStore(s => s.setNick);
  const executeWithAltcha = useExecuteWithAltcha();
  const [isReloading, setIsReloading] = useState(false); 
  const { mutate: reloadWord, isPending: reloadingWord } = useReplaceWord();

  if (!nick) {
    return null;
  }

  const handleReloadWord = (word: Word) => {
    reloadWord(
      {
        role: word.role,
        previousId: nick.id,
        gender: nick.gender,
        offenseLevel: nick.offenseLevel
      },
      {
        onSuccess: (newNick) => {
          setIsReloading(false);
          setNick(newNick);
        }
      }
    );
  };
  return (
    <Card className='nick-display'>
      <LoadingOverlay visible={isReloading || reloadingWord} zIndex={1000} color='pink' overlayProps={{ radius: "sm", blur: 2, opacity: 0.5 }} />
      <Box ta="center">
      <h2>Ton pseudo</h2>
      { /* 
      <p>
        { GENDER_LABELS[nick.gender] } / 
        { OFFENSE_LEVEL_LABELS[nick.offenseLevel] }
      </p>
      /*}
      {/* For screen readers */}
      <p className="sr-only" aria-live="polite" aria-atomic="true">
        Pseudo généré : {nick.words.map(w => w.label).join(' ')}
      </p>

      
      <Group aria-label="Mots du pseudo" gap={30} align="center" justify="center">
        <Paper p={16} className={styles.nickWords}>
        {nick.words.map(word => (
          <span key={word.label} className={styles.nickWord}>
            <Text component='span' className='nick-word'>{word.label}</Text>

            <ActionIcon size="xs"
              onClick={() => {
                setIsReloading(true);
                executeWithAltcha(() => {
                  handleReloadWord(word);
                })
              }}
              variant="subtle"
              disabled={reloadingWord}
              aria-label={`Remplacer le mot ${word.label}`}
              aria-disabled={reloadingWord}
              aria-busy={reloadingWord}
              className={styles.reloadWordAction}
            >
              <IconReload/>
            </ActionIcon>
          </span>
      ))}
      </Paper>
    
    {reloadingWord && (
        <p role="status" aria-live="polite" className="sr-only">
          Remplacement du mot en cours
        </p>
      )}


      <div className="nick-actions">
        <CopyNickButton nick={nick} />
        <ReportNickButton nick={nick} />
      </div>
      
      </Group>
      </Box>
    </Card>
  );
}
