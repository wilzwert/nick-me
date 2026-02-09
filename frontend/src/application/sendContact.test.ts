import { describe, it, expect, vi, beforeEach, type MockedFunction } from 'vitest';
import { renderHook, act } from '@testing-library/react';
import { useToastStore } from '../presentation/stores/toast.store';
import { sendContactMessage } from '../infrastructure/contact.api';
import { useSendContactMessage } from './sendContact';
import { createTestWrapper } from '../../test-utils/createTestWrapper';

describe('useSendContact', () => {
  beforeEach(() => {
    vi.clearAllMocks();
    useToastStore.setState({ toasts: [] });
  });

  it('updates stores on success', async () => {
    vi.mock('../infrastructure/contact.api');
    const mockedSendContactMessage = sendContactMessage as unknown as MockedFunction<typeof sendContactMessage>;

    mockedSendContactMessage.mockResolvedValue(void 0);

    const { result } = renderHook(() => useSendContactMessage(), { wrapper: createTestWrapper() });

    await act(async () => {
      // explicitly trigger the mutation
      result.current.mutate({ content: 'Word', senderEmail: 'test@example.com' });
    });


    expect(useToastStore.getState().toasts).toHaveLength(1);
    expect(useToastStore.getState().toasts[0].type).toBe('success');
    expect(useToastStore.getState().toasts[0].message).toBe("Message envoyé !");
  });
});
