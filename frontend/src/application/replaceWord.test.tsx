import { describe, it, expect, vi, beforeEach, type MockedFunction } from 'vitest';
import { renderHook, act } from '@testing-library/react';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { useNickStore } from '../domain/store/nick.store';
import { useNickHistoryStore } from '../domain/store/nick-history.store';
import type { ReactNode } from 'react';
import type { Nick } from '../domain/model/Nick';
import type { Word } from '../domain/model/Word';
import { replaceWord } from '../infrastructure/nick.api';
import { useReplaceWord } from './replaceWord';

const wrapper = ({ children }:  { children: ReactNode }) => {
  const client = new QueryClient();
  return <QueryClientProvider client={client}>{children}</QueryClientProvider>;
};

describe('useReplaceWord', () => {
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
      words: [
        { id: 1, label: 'Subject', role: 'subject' },  
        { id: 2, label: 'Qualifier', role: 'qualifier' }
      ]
    };

    useNickStore.setState({ nick: mockNick });

    const mockNewNick: Nick = {
      id: 1,
      gender: 'M',
      offenseLevel: 10,
      words: [
        { id: 1, label: 'Subject', role: 'subject' },  
        {id: 3, label: 'Word', role: 'qualifier' }
      ]
    };

    vi.mock('../infrastructure/nick.api');
    const mockedReplaceWord = replaceWord as unknown as MockedFunction<typeof replaceWord>;

    mockedReplaceWord.mockResolvedValue(mockNewNick);

    const { result } = renderHook(() => useReplaceWord(), { wrapper });


    await act(async () => {
      // explicitly trigger the mutation
      result.current.mutate({ role: 'qualifier', gender: 'F', offenseLevel: 10, previousId: 2 });
    });
    
    expect(useNickStore.getState().nick).toEqual(mockNewNick);
    expect(useNickHistoryStore.getState().history[0]).toEqual(mockNewNick);
  });
});
