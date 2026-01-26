import { describe, it, expect, vi, beforeEach, type MockedFunction } from 'vitest';
import { renderHook, act } from '@testing-library/react';
import { useToastStore } from '../presentation/stores/toast.store';
import { createSuggestion } from '../infrastructure/suggestion.api';
import { useCreateSuggestion } from './createSuggestion';
import { createTestWrapper } from '../test/createTestWrapper';

describe('useCreateSuggestion', () => {
  beforeEach(() => {
    vi.clearAllMocks();
    useToastStore.setState({ toasts: [] });
  });

  it('updates stores on success', async () => {
    vi.mock('../infrastructure/suggestion.api');
    const mockedCreateSuggestion = createSuggestion as unknown as MockedFunction<typeof createSuggestion>;

    mockedCreateSuggestion.mockResolvedValue(void 0);

    const { result } = renderHook(() => useCreateSuggestion(), { wrapper: createTestWrapper() });

    await act(async () => {
      // explicitly trigger the mutation
      result.current.mutate({ label: 'Word', senderEmail: 'test@example.com' });
    });


    expect(useToastStore.getState().toasts).toHaveLength(1);
    expect(useToastStore.getState().toasts[0].type).toBe('success');
    expect(useToastStore.getState().toasts[0].message).toBe('Suggestion enregistrée !');
  });
});
