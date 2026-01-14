// domain/nick.store.test.ts
import { describe, it, expect, beforeEach } from 'vitest';
import { useNickStore } from './nick.store';
import type { Nick } from '../../domain/model/Nick';

describe('NickStore', () => {
  beforeEach(() => {
    // reset store before each test
    useNickStore.setState({ nick: null });
  });

  it('sets current nick', () => {
    const nick: Nick = { id: 12, gender: 'M', offenseLevel: 5, words: [] };

    useNickStore.getState().setNick(nick);

    expect(useNickStore.getState().nick).toEqual(nick);
  });

  it('pushes nick to history', () => {
    const nick: Nick = { id: 12, gender: 'M' , offenseLevel: 5, words: [] };

    useNickStore.getState().setNick(nick);

    expect(useNickStore.getState().nick).toEqual(nick);
  });
});
