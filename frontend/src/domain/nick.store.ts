import { create } from "zustand";
import type { Nick } from "./model/Nick";
// import type { Word } from "./model/Word";

interface NickStore {
    nick: Nick | null;
    setNick: (nick: Nick) => void;
    // replaceWord: (update: Word) => void
}

export const useNickStore = create<NickStore>(set => ({
  nick: null as Nick | null,

  setNick: (nick: Nick) => set({ nick }),
/*
  replaceWord: (updated: Word) =>
    set(state => ({
      nick: state.nick && {
        ...state.nick,
        words: state.nick.words.map(w =>
          w.id === updated.id ? updated : w
        )
      }
    }))*/
}));