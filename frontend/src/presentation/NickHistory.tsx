import { useCriteriaStore } from "../domain/criteria.store";
import { useNickHistoryStore } from "../domain/nick-history.store";
import { useNickStore } from "../domain/nick.store";

export function NickHistory() {
  const history = useNickHistoryStore(s => s.history);
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
          </li>
        ))}
      </ul>
    </div>
  );
}
