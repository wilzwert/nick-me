import { create } from "zustand";
import { generateToastId } from "./toast-id-generator";

export type ToastType = "success" | "error" | "info";

export interface Toast {
  id: string;
  type: ToastType;
  message: string;
  duration?: number; // en ms, optionnel
}

interface ToastStore {
  toasts: Toast[];
  addToast: (toast: Omit<Toast, "id">) => void;
  removeToast: (id: string) => void;
}

export const useToastStore = create<ToastStore>((set, get) => ({
  toasts: [],

  addToast: ({ type, message, duration = 2000 }) => {
    const id = generateToastId();
    set((state) => ({ toasts: [...state.toasts, { id, type, message, duration }] }));

    // auto-remove after given duration
    setTimeout(() => {
      get().removeToast(id);
    }, duration);
  },

  removeToast: (id) => {
    set((state) => ({ toasts: state.toasts.filter((t) => t.id !== id) }));
  },
}));
