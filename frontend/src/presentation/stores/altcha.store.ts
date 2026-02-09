import { create } from 'zustand';

const ALTCHA_ENABLED = import.meta.env.VITE_ALTCHA_ENABLED === 'true';
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
  setCallback: (callback: (() => void) | null) => void;
}

export const useAltchaStore = create<AltchaStore>((set, get) => ({
  token: ALTCHA_ENABLED ? null : {payload: '__altcha_disabled__', expiresAt: 0},
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
      callback(payload);
    } 
  },
  setCallback: (callback: (() => void) | null) => {
    if (!callback) {
      set({ callback: null });
      return;
    }

    if(!ALTCHA_ENABLED) {
      set({ callback: null });
      callback();
      return;
    }

    const { token } = get();
    if (token && token.payload && Date.now() < token.expiresAt) {
      set({ callback: null });
      callback();
      return;
    }
    set({ token: null, callback, pending: true });
  }
}));