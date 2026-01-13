import { create } from 'zustand';

interface AltchaStore {
  payload: string | null;
  setPayload: (payload: string) => void;
}

export const useAltchaStore = create<AltchaStore>(set => ({
  payload: null,
  setPayload: payload => set({ payload })
}));
