import { ApiError } from '../domain/model/ApiError';
import { useAltchaStore } from '../presentation/stores/altcha.store';

const API_BASE = import.meta.env.VITE_API_BASE_URL ?? '';

/**
 * Sends a contact message
 */
export async function createReport(params: {
  senderEmail: string|null;
  reason: string;
  nickId: number;
}): Promise<void> {
  
  const altchaToken = useAltchaStore.getState().token;

  if (!altchaToken) {
    throw new Error('Altcha token is required to create suggestion');
  }

  const res = await fetch(`${API_BASE}/api/report`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-Altcha-Payload': altchaToken.payload },
    body: JSON.stringify(params)
  });

  const body = await res.json();
  if (!res.ok) {
    throw new ApiError({ status: res.status, error: body.error, message: body.message });
  }

  return body;
}