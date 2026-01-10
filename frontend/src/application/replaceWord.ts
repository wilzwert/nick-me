import { useMutation } from "@tanstack/react-query";
import { replaceWord } from "../infrastructure/nick.api";
import { useNickStore } from "../domain/nick.store";
import type { Nick } from "../domain/model/Nick";
import type { Gender } from "../domain/model/Gender";
import type { OffenseLevel } from "../domain/model/OffenseLevel";
import type { Word, WordRole } from "../domain/model/Word";
import { useNickHistoryStore } from "../domain/nick-history.store";

interface ReplaceWordParams {
  role: WordRole;
  gender: Gender;
  offenseLevel: OffenseLevel;
  previousId: number;
}

export function useReplaceWord() {
  const nick: Nick|null = useNickStore(s => s.nick);
  const setNick = useNickStore(s => s.setNick);
  const addNickToHistory = useNickHistoryStore(s => s.addNick);


  return useMutation<Word, Error, ReplaceWordParams>({
    mutationFn: params => replaceWord(params),
    onSuccess: (newWord: Word, params: ReplaceWordParams) => {
      if (!nick) return;
      const newNick: Nick = {
        ...nick,
        words: nick.words.map(w => (
          w.id === params.previousId ? newWord : w
        ))
      };
      
      setNick(newNick);
      addNickToHistory(newNick);
    }
  });
}