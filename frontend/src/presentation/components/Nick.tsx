import { useNickStore } from '../stores/nick.store';
import { type Word } from '../../domain/model/Word';
import { useReplaceWord } from '../../application/replaceWord';
import { OFFENSE_LEVEL_LABELS } from '../../domain/labels/offenseLevel.labels';
import { GENDER_LABELS } from '../../domain/labels/gender.labels';
import { CopyNickButton } from './CopyNickButton';
import { useExecuteWithAltcha } from '../../infrastructure/altcha.service';
import styles from './Nick.module.css';
import { Button } from '@mantine/core';
import { IconReload } from '@tabler/icons-react';

export function Nick() {
  const nick = useNickStore(s => s.nick);
  const setNick = useNickStore(s => s.setNick);
  const executeWithAltcha = useExecuteWithAltcha();

  const { mutate: reloadWord, isPending: reloadingWord } = useReplaceWord();

  if (!nick) return null;

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
          setNick(newNick);
        }
      }
    );
  };

  return (
    <div className={styles.nickDisplay}>
      <h2>Ton pseudo</h2>
      <p>
        { GENDER_LABELS[nick.gender] } / 
        { OFFENSE_LEVEL_LABELS[nick.offenseLevel] }
      </p>

      {/* Annonce du nick complet pour lecteurs d’écran */}
      <p className="sr-only" aria-live="polite" aria-atomic="true">
        Pseudo généré : {nick.words.map(w => w.label).join(' ')}
      </p>

      <ul className={styles.nickWords} aria-label="Mots du pseudo">
      {nick.words.map(word => (
        <li key={word.id} className={styles.nickWord}>
          <span>{word.label}</span>

          <Button size="xs"
            onClick={() =>
              executeWithAltcha(() => {
                handleReloadWord(word);
              })
            }
            disabled={reloadingWord}
            aria-label={`Remplacer le mot ${word.label}`}
            aria-disabled={reloadingWord}
            aria-busy={reloadingWord}
          >
            <span aria-hidden="true"><IconReload /></span>
          </Button>
        </li>
      ))}
    </ul>

    {reloadingWord && (
        <p role="status" aria-live="polite" className="sr-only">
          Remplacement du mot en cours
        </p>
      )}


      <div className="nick-actions">
        <CopyNickButton nick={nick} />
      </div>
    </div>
  );
}
