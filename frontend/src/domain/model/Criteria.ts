import type { Gender } from './Gender';
import type { OffenseLevel } from './OffenseLevel';

export interface Criteria {
  gender: Gender;
  offenseLevel: OffenseLevel
}