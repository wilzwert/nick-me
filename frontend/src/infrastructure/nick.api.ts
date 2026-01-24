import { useAltchaStore } from '../presentation/stores/altcha.store';
import type { Gender } from '../domain/model/Gender';
import type { Nick } from '../domain/model/Nick';
import type { OffenseLevel } from '../domain/model/OffenseLevel';
import type { WordRole } from '../domain/model/Word';
import { ApiError } from '../domain/model/ApiError';

const API_BASE = import.meta.env.VITE_API_URL ?? '';

/**
 * Generates a new complete Nick
 */
export async function generateNick(params: {
  gender: Gender;
  offenseLevel: OffenseLevel;
}): Promise<Nick> {

  
  const altchaToken = useAltchaStore.getState().token;

  if (!altchaToken) {
    throw new Error('Altcha token is required to replace word');
  }

  const query = new URLSearchParams({
    gender: params.gender,
    offenseLevel: String(params.offenseLevel)
  });

  const res = await fetch(`${API_BASE}/api/nick?${query.toString()}`, {
    method: 'GET',
    headers: { 'Content-Type': 'application/json', 'X-Altcha-Payload': altchaToken.payload }
  });
  const body = await res.json();
  if (!res.ok) {
    throw new ApiError({ status: res.status, error: body.error, message: body.message });
  }

  return res.json();
}

/**
 * Replaces a Word in a Nick
 */
export async function replaceWord(params: {
  role: WordRole;
  gender: Gender;
  previousId: number;
  offenseLevel: OffenseLevel;
}): Promise<Nick> {

  const altchaToken = useAltchaStore.getState().token;

  if (!altchaToken) {
    throw new Error('Altcha token is required to replace word');
  }

  const query = new URLSearchParams({
    replaceRole: params.role,
    gender: params.gender,
    previousId: String(params.previousId),
    offenseLevel: String(params.offenseLevel)
  });

  const res = await fetch(`${API_BASE}/api/nick?${query.toString()}`, {
    method: 'GET',
    headers: { 'Content-Type': 'application/json', 'X-Altcha-Payload': altchaToken.payload }
  });

  if (!res.ok) {
    throw new Error('Failed to replace word');
  }

  return res.json();
}
