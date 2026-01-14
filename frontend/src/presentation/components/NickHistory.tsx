import { useCriteriaStore } from "../stores/criteria.store";
import { useNickHistoryStore } from "../stores/nick-history.store";
import { useNickStore } from "../stores/nick.store";
import { CopyNickButton } from "./CopyNickButton";

export function NickHistory() {
  const history = useNickHistoryStore(s => s.history);
  const removeFromHistory = useNickHistoryStore(s => s.removeNick);
  const setNick = useNickStore(s => s.setNick);
  const setCriteria = useCriteriaStore(s => s.setCriteria);

  if (!history.length) return null;

  return (
    <div className="nick-history">
      <h3>Historique</h3>
      <ul>
        {history.map((nick, i) => (
          <li key={i}>
            <button onClick={
                () => {
                    setCriteria({gender: nick.gender, offenseLevel: nick.offenseLevel});
                    setNick(nick);
                }
            }>
              {nick.words.map(w => w.label).join(' ')}
            </button>
            <CopyNickButton nick={nick} />
            <button onClick={() => {
              removeFromHistory(nick);
            }}>❌</button>
          </li>
        ))}
      </ul>
    </div>
  );
}
