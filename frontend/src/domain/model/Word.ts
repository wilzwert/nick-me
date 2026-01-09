export type WordRole = 'subject' | 'qualifier';

export interface Word {
  id: number;
  label: string;
  role: WordRole;
}

