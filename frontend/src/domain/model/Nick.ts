import type { Gender } from './Gender';
import type { OffenseLevel } from './OffenseLevel';
import type { Word } from './Word';

export interface Nick {
  gender: Gender;
  offenseLevel: OffenseLevel;
  words: Word[];
}