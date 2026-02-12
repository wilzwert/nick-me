import { describe, it, expect, vi, beforeEach, type MockedFunction } from 'vitest';
import { renderHook, act, waitFor } from '@testing-library/react';
import { useToastStore } from '../presentation/stores/toast.store';
import { createReport } from '../infrastructure/report.api';
import { useCreateReport } from './createReport';
import { createTestWrapper } from '../../test-utils/createTestWrapper';

describe('useCreateReport', () => {
  beforeEach(() => {
    vi.clearAllMocks();
    useToastStore.setState({ toasts: [] });
  });

  it('updates stores on success', async () => {
    vi.mock('../infrastructure/report.api');
    const mockedCreateReport = createReport as unknown as MockedFunction<typeof createReport>;

    mockedCreateReport.mockResolvedValue(void 0);

    const { result } = renderHook(() => useCreateReport(), { wrapper: createTestWrapper() });

    await act(async () => {
      // explicitly trigger the mutation
      await result.current.mutateAsync({ nickId: 1, senderEmail: 'test@example.com', reason: 'Inappropriate' });
    });


    expect(useToastStore.getState().toasts).toHaveLength(1);
    expect(useToastStore.getState().toasts[0].type).toBe('success');
    expect(useToastStore.getState().toasts[0].message).toBe("Signalement enregistré !");
  });

  it('updates stores on error', async () => {
    vi.mock('../infrastructure/report.api');
    const mockedCreateReport = createReport as unknown as MockedFunction<typeof createReport>;

    mockedCreateReport.mockRejectedValue(new Error());

    const { result } = renderHook(() => useCreateReport(), { wrapper: createTestWrapper() });

    act(() => {
      // explicitly trigger the mutation
      result.current.mutate({ nickId: 1, senderEmail: 'test@example.com', reason: 'Inappropriate' });
    });

    await waitFor(() => {
      expect(useToastStore.getState().toasts).toHaveLength(1);
      expect(useToastStore.getState().toasts[0].type).toBe('error');
      expect(useToastStore.getState().toasts[0].message).toBe("Erreur lors de l'envoi");
    });
  });

  it('updates stores on error with api error message', async () => {
    vi.mock('../infrastructure/report.api');
    const mockedCreateReport = createReport as unknown as MockedFunction<typeof createReport>;

    mockedCreateReport.mockRejectedValue(new Error("API error"));

    const { result } = renderHook(() => useCreateReport(), { wrapper: createTestWrapper() });

    act(() => {
      // explicitly trigger the mutation
      result.current.mutate({ nickId: 1, senderEmail: 'test@example.com', reason: 'Inappropriate' });
    });
    
    await waitFor(() => {
      expect(useToastStore.getState().toasts).toHaveLength(1);
      expect(useToastStore.getState().toasts[0].type).toBe('error');
      expect(useToastStore.getState().toasts[0].message).toBe("API error");
    })
  });
});
