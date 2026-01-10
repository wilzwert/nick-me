import { create } from 'zustand';
import { type Nick } from './model/Nick';

const STORAGE_KEY = 'nick-history';

function loadHistory(): Nick[] {
  try {
    return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
  } catch {
    return [];
  }
}

function saveHistory(history: Nick[]) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(history));
}

interface NickHistoryState {
  history: Nick[];
  addNick: (nick: Nick) => void;
  setFromHistory: () => void;
}

export const useNickHistoryStore = create<NickHistoryState>((set, get) => ({
  history: loadHistory(),

  addNick: (nick: Nick) =>
    set(state => {
      const updated = [nick, ...state.history].slice(0, 20);
      saveHistory(updated);
      return { history: updated };
    }),

  setFromHistory: () => {
    set(() => ({ history: get().history }));
  }
}));
