import { create } from 'zustand';

const ALTCHA_TOKEN_EXPIRY_SECONDS = parseInt(import.meta.env.VITE_ALTCHA_TOKEN_EXPIRY_SECONDS);

type AltchaToken = {
  payload: string;
  expiresAt: number;
};

interface AltchaStore {
  token: AltchaToken | null;
  callback: ((payload: string) => void) | null;
  pending: boolean;
  setPayload: (payload: string) => void;
  setCallback: (callback: ((payload: string) => void) | null) => void;
}

export const useAltchaStore = create<AltchaStore>((set, get) => ({
  token: null,
  pending: false,
  callback: null,
  setPayload: payload => {
    const { callback } = get();
    set(
      { 
        token:  {
          expiresAt: Date.now() + ALTCHA_TOKEN_EXPIRY_SECONDS * 1000,
          payload: payload
        },
        pending: false
      }
    );

    if (callback) {
      console.log('Altcha token received, executing callback');
      callback(payload);
    } 
  },
  setCallback: (callback: ((payload: string) => void) | null) => {
    if (!callback) {
      set({ callback: null });
      return;
    }
    const { token } = get();
    if (token && token.payload && Date.now() < token.expiresAt) {
      set({ callback: null });
      console.log('Altcha token already available, executing callback immediately');
      callback(token.payload);
      return;
    }
    set({ token: null, callback, pending: true });
  }
}));