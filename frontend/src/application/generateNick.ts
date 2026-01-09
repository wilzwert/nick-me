import { useMutation } from "@tanstack/react-query";
import { generateNick } from "../infrastructure/nick.api";
import { useNickStore } from "../domain/nick.store";
import type { Nick } from "../domain/model/Nick";
import type { Gender } from "../domain/model/Gender";
import type { OffenseLevel } from "../domain/model/OffenseLevel";

interface GenerateNickParams {
  gender: Gender;
  offenseLevel: OffenseLevel;
}

export function useGenerateNick() {
  const setNick = useNickStore(s => s.setNick);

  return useMutation<Nick, Error, GenerateNickParams>({
    mutationFn: params => generateNick(params),
    onSuccess: nick => setNick(nick)
  });
}