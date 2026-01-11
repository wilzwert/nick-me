import { create } from "zustand";
import type { Nick } from "../model/Nick";

interface NickStore {
    nick: Nick | null;
    setNick: (nick: Nick) => void;
}

export const useNickStore = create<NickStore>(set => ({
  nick: null as Nick | null,

  setNick: (nick: Nick) => set({ nick })
}));