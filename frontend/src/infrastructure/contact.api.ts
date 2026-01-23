import { useAltchaStore } from '../presentation/stores/altcha.store';

const API_BASE = import.meta.env.VITE_API_URL ?? '';

/**
 * Sends a contact message
 */
export async function sendContactMessage(params: {
  senderEmail: string;
  content: string;
}): Promise<void> {

  
  const altchaToken = useAltchaStore.getState().token;

  if (!altchaToken) {
    throw new Error('Altcha token is required to replace word');
  }

  const res = await fetch(`${API_BASE}/api/contact`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-Altcha-Payload': altchaToken.payload },
    body: JSON.stringify(params)
  });

  if (!res.ok) {
    throw new Error('Failed to send contact message');
  }

  return res.json();
}