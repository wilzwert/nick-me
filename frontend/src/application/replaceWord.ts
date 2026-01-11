import { useMutation } from "@tanstack/react-query";
import { replaceWord } from "../infrastructure/nick.api";
import { useNickStore } from "../domain/store/nick.store";
import type { Nick } from "../domain/model/Nick";
import type { Gender } from "../domain/model/Gender";
import type { OffenseLevel } from "../domain/model/OffenseLevel";
import type { WordRole } from "../domain/model/Word";
import { useNickHistoryStore } from "../domain/store/nick-history.store";

interface ReplaceWordParams {
  role: WordRole;
  gender: Gender;
  offenseLevel: OffenseLevel;
  previousId: number;
}

export function useReplaceWord() {
  const setNick = useNickStore(s => s.setNick);
  const addNickToHistory = useNickHistoryStore(s => s.addNick);


  return useMutation<Nick, Error, ReplaceWordParams>({
    mutationFn: params => replaceWord(params),
    onSuccess: (newNick: Nick) => {
      setNick(newNick);
      addNickToHistory(newNick);
    }
  });
}