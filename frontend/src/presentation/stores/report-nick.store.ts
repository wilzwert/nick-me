import { create } from "zustand";
import type { Nick } from "../../domain/model/Nick";

interface ReportNickStore {
    nick: Nick | null;
    setNick: (nick: Nick | null) => void;
}

export const useReportNickStore = create<ReportNickStore>(set => ({
  nick: null as Nick | null,

  setNick: (nick: Nick | null) => set({ nick })
}));