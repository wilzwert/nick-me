// domain/nick.store.test.ts
import { describe, it, expect, beforeEach, afterEach } from 'vitest';
import { useToastStore, type Toast } from './toast.store';
import { setToastIdGenerator } from './toast-id-generator';

function createQueuedIdGenerator(ids: string[]) {
  let i = 0;
  return () => ids[i++];
}

describe('ToastStore', () => {
  beforeEach(() => {
    // reset store before each test
    useToastStore.setState({ toasts: [] });
  });

  afterEach(() => {
    // reset to default id generator
    setToastIdGenerator(() => crypto.randomUUID());
  });

  it('adds toast', () => {
    const toast: Toast = { id: 'abc-123', type: 'success', message: 'Test toast', duration: 3000 };
    setToastIdGenerator(createQueuedIdGenerator(['abc-123']));
    useToastStore.getState().addToast(toast);

    expect(useToastStore.getState().toasts).toHaveLength(1);
    expect(useToastStore.getState().toasts[0]).toEqual(toast);
  });

  it('adds second toast', () => {
    useToastStore.setState({ toasts: [{ id: 'abc-123', type: 'success', message: 'Test toast', duration: 3000 }] });
    setToastIdGenerator(createQueuedIdGenerator(['abc-456']));
    const toast: Toast = { id: 'abc-456', type: 'error', message: 'Error toast', duration: 1000 };
    useToastStore.getState().addToast(toast);

    expect(useToastStore.getState().toasts).toHaveLength(2);
    expect(useToastStore.getState().toasts[1]).toEqual(toast);
  });

  it('removes toast', () => {
    const toast1: Toast = { id: 'abc-123', type: 'success', message: 'Test toast', duration: 3000 };
    const toast2: Toast = { id: 'abc-456', type: 'error', message: 'Error toast', duration: 1000 };
    useToastStore.setState({ toasts: [toast1, toast2] });

    useToastStore.getState().removeToast('abc-123');
    expect(useToastStore.getState().toasts).toHaveLength(1);
    expect(useToastStore.getState().toasts[0]).toEqual(toast2);
  });

  it('removes non-existing toast gracefully', () => {
    const toast1: Toast = { id: 'abc-123', type: 'success', message: 'Test toast', duration: 3000 };
    useToastStore.setState({ toasts: [toast1] });
    useToastStore.getState().removeToast('non-existing-id');

    expect(useToastStore.getState().toasts).toHaveLength(1);
    expect(useToastStore.getState().toasts[0]).toEqual(toast1);
  });
  
});
