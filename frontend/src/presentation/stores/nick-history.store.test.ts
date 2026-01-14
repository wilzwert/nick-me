// domain/nick.store.test.ts
import { describe, it, expect, beforeEach } from 'vitest';
import type { Nick } from '../../domain/model/Nick';
import { useNickHistoryStore } from './nick-history.store';

describe('NickStoreHistory', () => {
  beforeEach(() => {
    // reset store before each test
    useNickHistoryStore.setState({ history: [] });
  });

  it('pushes nick to history', () => {
    const nick: Nick = { id: 12, gender: 'M', offenseLevel: 5, words: [] };

    useNickHistoryStore.getState().addNick(nick);

    expect(useNickHistoryStore.getState().history).toHaveLength(1);
    expect(useNickHistoryStore.getState().history[0]).toEqual(nick);
  });

  it('removes nick from history', () => {
    const nick: Nick = {id: 12, gender: 'M', offenseLevel: 5, words: []};
    useNickHistoryStore.setState({ history: [nick] });
    useNickHistoryStore.getState().removeNick(nick);
    expect(useNickHistoryStore.getState().history).toHaveLength(0);
  });

  it('does nothing when removing unknown nick from history', () => {
    const nick: Nick = {id: 12, gender: 'M', offenseLevel: 5, words: []};
    useNickHistoryStore.setState({ history: [nick] });
    useNickHistoryStore.getState().removeNick({id: 45, gender: 'M', offenseLevel: 5, words: []} as Nick);
    expect(useNickHistoryStore.getState().history).toHaveLength(1);
  });
});
