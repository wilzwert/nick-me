import { useState } from 'react';
import { useAltchaStore } from '../application/altcha.store';
import { setAltchaExecutor } from '../application/altcha.service';
import 'altcha'

const ALTCHA_CHALLENGE_URL = import.meta.env.VITE_ALTCHA_CHALLENGE_URL ?? '';

export function AltchaModal() {
  const [visible, setVisible] = useState(false);
  const [pending, setPending] = useState<((payload: string) => void) | null>(null);
  const setPayload = useAltchaStore(s => s.setPayload);

  const executeWithAltcha = (callback: (payload: string) => void) => {
    const payload = useAltchaStore.getState().payload;
    if (payload) callback(payload);
    else {
      setPending(() => callback);
      setVisible(true);
    }
  };

  // updates "global service"
  setAltchaExecutor(executeWithAltcha);

  const handleStateChange = (ev: CustomEvent) => {
    if (ev.detail.state === 'verified') {
        const payload = ev.detail.payload;
        setPayload(payload);
        setVisible(false);
        if (pending) {
        pending(payload);
        setPending(null);
        }
    }
  };

  if (!visible) return null;
  return <altcha-widget auto="onload" onstatechange={handleStateChange} challengeurl={ALTCHA_CHALLENGE_URL} credentials='same-origin'>debug</altcha-widget>;
}
