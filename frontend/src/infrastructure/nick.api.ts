import type { Gender } from '../domain/model/Gender';
import type { Nick } from '../domain/model/Nick';
import type { OffenseLevel } from '../domain/model/OffenseLevel';
import type { Word, WordRole } from '../domain/model/Word';

const API_BASE = import.meta.env.VITE_API_URL ?? '';

/**
 * Generates a new complete Nick
 */
export async function generateNick(params: {
  gender: Gender;
  offenseLevel: OffenseLevel;
}): Promise<Nick> {

  const res = await fetch(`${API_BASE}/api/nick`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(params)
  });

  if (!res.ok) {
    throw new Error('Failed to generate nick');
  }

  return res.json();
}

/**
 * Replaces a Word in a Nick
 */
export async function replaceWord(params: {
  role: WordRole;
  gender: 'M' | 'F';
  previousId: number;
  offenseLevel: number;
}): Promise<Word> {

  const query = new URLSearchParams({
    role: params.role,
    gender: params.gender,
    previousId: String(params.previousId),
    offenseLevel: String(params.offenseLevel)
  });

  const res = await fetch(`${API_BASE}/api/word?${query.toString()}`);

  if (!res.ok) {
    throw new Error('Failed to replace word');
  }

  return res.json();
}
