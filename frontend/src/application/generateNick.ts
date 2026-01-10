import { useMutation } from "@tanstack/react-query";
import { generateNick } from "../infrastructure/nick.api";
import { useNickStore } from "../domain/nick.store";
import type { Nick } from "../domain/model/Nick";
import type { Gender } from "../domain/model/Gender";
import type { OffenseLevel } from "../domain/model/OffenseLevel";
import { useNickHistoryStore } from "../domain/nick-history.store";
import { useCriteriaStore } from "../domain/criteria.store";

interface GenerateNickParams {
  gender: Gender;
  offenseLevel: OffenseLevel;
}

export function useGenerateNick() {
  const setNick = useNickStore(s => s.setNick);
  const setCriteria = useCriteriaStore(s => s.setCriteria);
  const addNickToHistory = useNickHistoryStore(s => s.addNick);

  return useMutation<Nick, Error, GenerateNickParams>({
    mutationFn: params => generateNick(params),
    onSuccess: (nick: Nick) => {
      setNick(nick);
      addNickToHistory(nick);
      setCriteria({gender: nick.gender, offenseLevel: nick.offenseLevel});
    }
  });
}