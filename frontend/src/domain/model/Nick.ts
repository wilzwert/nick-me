import type { Word } from './Word';

export interface Nick {
  gender: 'M' | 'F';
  offenseLevel: number;
  words: Word[];
}