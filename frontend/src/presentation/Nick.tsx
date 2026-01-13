import { useNickStore } from '../domain/store/nick.store';
import { type Word } from '../domain/model/Word';
import { useReplaceWord } from '../application/replaceWord';
import { OFFENSE_LEVEL_LABELS } from '../domain/labels/offenseLevel.labels';
import { GENDER_LABELS } from '../domain/labels/gender.labels';
import { CopyNickButton } from './CopyNickButton';
import { executeWithAltcha } from '../application/altcha.service';

export function Nick() {
  const nick = useNickStore(s => s.nick);
  const setNick = useNickStore(s => s.setNick);

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
    <div className="nick-display">
      <h2>Ton pseudo</h2>
      <p>
        { GENDER_LABELS[nick.gender] } / 
        { OFFENSE_LEVEL_LABELS[nick.offenseLevel] }
      </p>

      <div className="nick-words">
        {nick.words.map(word => (
          <span key={word.id} className="nick-word">
            {word.label}
            <button
              onClick={() => executeWithAltcha(() => {
                handleReloadWord(word)}
              )}
              disabled={reloadingWord}
            >
              🔄
            </button>
          </span>
        ))}
      </div>

      <div className="nick-actions">
        <CopyNickButton nick={nick} />
      </div>
    </div>
  );
}
