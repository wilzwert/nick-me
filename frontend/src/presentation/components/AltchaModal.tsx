import { useAltchaStore } from "../stores/altcha.store";
import 'altcha'

const ALTCHA_CHALLENGE_URL = import.meta.env.VITE_ALTCHA_CHALLENGE_URL ?? '';
const ALTCHA_TOKEN_EXPIRY_MS = parseInt(import.meta.env.VITE_ALTCHA_TOKEN_EXPIRY_MS);

export function AltchaModal() {
  const setPayload = useAltchaStore(s => s.setPayload);
  const pending = useAltchaStore(s => s.pending);
  
  const handleStateChange = (ev: CustomEvent) => {
    if (ev.detail.state === 'verified') {
        const payload = ev.detail.payload;
        setPayload(payload);
    }
  };

  if (!pending) {
    return null;
  }

  return <altcha-widget auto="onload" expire={ALTCHA_TOKEN_EXPIRY_MS} onstatechange={handleStateChange} challengeurl={ALTCHA_CHALLENGE_URL} credentials='same-origin'></altcha-widget>;
}
