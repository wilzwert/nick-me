import { useAltchaStore } from "../presentation/stores/altcha.store";

export function useExecuteWithAltcha() {
    const setCallback = useAltchaStore(s => s.setCallback);

  return (callback: (payload: string) => void) => {
    setCallback(callback);
  };
};