import { useNickStore } from '../domain/nick.store';
import { type Word } from '../domain/model/Word';
import { useReplaceWord } from '../application/replaceWord';
import { OFFENSE_LEVEL_LABELS } from '../domain/labels/offenseLevel.labels';
import { GENDER_LABELS } from '../domain/labels/gender.labels';

export function Nick() {
  const nick = useNickStore(s => s.nick);
  const setNick = useNickStore(s => s.setNick);

  const { mutate: reloadWord, isPending: reloadingWord } = useReplaceWord();

  if (!nick) return null;

  const handleReloadWord = (word: Word) => {
    reloadWord(
      {
        role: word.role,
        previousId: word.id,
        gender: nick.gender,
        offenseLevel: nick.offenseLevel
      },
      {
        onSuccess: (newWord) => {
          setNick({
            ...nick,
            words: nick.words.map(w => (w.id === word.id ? newWord : w))
          });
        }
      }
    );
  };

  const handleCopy = async () => {
    const text = nick.words.map(w => w.label).join(' ');
    await navigator.clipboard.writeText(text);
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
              onClick={() => handleReloadWord(word)}
              disabled={reloadingWord}
            >
              🔄
            </button>
          </span>
        ))}
      </div>

      <div className="nick-actions">
        <button onClick={handleCopy}>📋 Copy</button>
      </div>
    </div>
  );
}
