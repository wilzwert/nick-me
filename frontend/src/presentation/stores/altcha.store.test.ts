import { describe, it, expect, vi, beforeEach } from 'vitest';
import { useAltchaStore } from './altcha.store';

describe('useAltchaStore', () => {
  beforeEach(() => {
    // reset store before each test
    useAltchaStore.setState({ token: null, callback: null, pending: false });
  });

  it('should set token and call pending callback when setPayload is called', () => {
    const mockCallback = vi.fn();
    useAltchaStore.setState({ callback: mockCallback, pending: true });

    useAltchaStore.getState().setPayload('my-token');

    const { token, pending, callback } = useAltchaStore.getState();

    expect(token).not.toBeNull();
    expect(token!.payload).toBe('my-token');
    expect(token!.expiresAt).toBeGreaterThan(Date.now());
    expect(pending).toBe(false);
    expect(callback).toBeDefined();
    expect(mockCallback).toHaveBeenCalledWith('my-token');
  });

  it('should execute callback immediately if token is valid when setCallback is called', () => {
    const mockCallback = vi.fn();
    // valid token
    const expiresAt = Date.now() + 1000;
    useAltchaStore.setState({ token: { payload: 'abc', expiresAt }, pending: false });

    useAltchaStore.getState().setCallback(mockCallback);

    const state = useAltchaStore.getState();
    expect(state.pending).toBe(false);
    expect(state.callback).toBeNull();
    expect(mockCallback).toHaveBeenCalled();
  });

  it('should set pending when no valid token', () => {
    const mockCallback = vi.fn();
    useAltchaStore.setState({ token: null, pending: false });

    useAltchaStore.getState().setCallback(mockCallback);

    const state = useAltchaStore.getState();
    expect(state.pending).toBe(true);
    expect(state.callback).toBe(mockCallback);
  });
});
