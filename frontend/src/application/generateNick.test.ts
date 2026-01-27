import { describe, it, expect, vi, beforeEach, type MockedFunction } from 'vitest';
import { renderHook, act } from '@testing-library/react';
import { useGenerateNick } from './generateNick';
import { generateNick } from '../infrastructure/nick.api';
import { useNickStore } from '../presentation/stores/nick.store';
import { useNickHistoryStore } from '../presentation/stores/nick-history.store';
import { useCriteriaStore } from '../presentation/stores/criteria.store';
import type { Nick } from '../domain/model/Nick';
import { createTestWrapper } from '../test/createTestWrapper';

describe('useGenerateNick', () => {
  beforeEach(() => {
    vi.clearAllMocks();
    useNickStore.setState({ nick: null });
    useNickHistoryStore.setState({ history: [] });
  });

  it('updates stores on success', async () => {
    const mockNick: Nick = {
      id: 1,
      gender: 'M',
      offenseLevel: 10,
      words: []
    };

    vi.mock('../infrastructure/nick.api');
    const mockedGenerateNick = generateNick as unknown as MockedFunction<typeof generateNick>;

    mockedGenerateNick.mockResolvedValue(mockNick);

    const { result } = renderHook(() => useGenerateNick(), { wrapper: createTestWrapper() });

    await act(async () => {
      // explicitly trigger the mutation
      result.current.mutate({ gender: 'M', offenseLevel: 10 });
    });

    expect(useNickStore.getState().nick).toEqual(mockNick);
    expect(useNickHistoryStore.getState().history[0]).toEqual(mockNick);
    expect(useCriteriaStore.getState().criteria).toEqual({
      gender: 'M',
      offenseLevel: 10
    });
  });
});
