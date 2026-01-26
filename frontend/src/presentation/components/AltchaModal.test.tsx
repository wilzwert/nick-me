import { beforeEach, describe, expect, it, vi } from 'vitest';
import { render } from '@testing-library/react';
import { useAltchaStore } from '../stores/altcha.store';
import { AltchaModal } from './AltchaModal';
import { act } from 'react';

describe('AltchaModal', () => {
  beforeEach(() => {
    // Reset store avant chaque test
    useAltchaStore.setState({
      token: null,
      pending: false,
      callback: null,
      setPayload: useAltchaStore.getState().setPayload,
      setCallback: useAltchaStore.getState().setCallback
    });
  });

  it('should render nothing when there is no pending action', () => {
    render(<AltchaModal />);
    const { container } = render(<AltchaModal />);
    expect(container.querySelector('altcha-widget')).not.toBeInTheDocument();
  });

  it('should render the widget when there is a pending action', () => {
    useAltchaStore.setState({ pending: true });
    const { container } = render(<AltchaModal />);
    expect(container.querySelector('altcha-widget')).toBeInTheDocument();
  });

  it('should update the store when the widget dispatches verified state', () => {
    useAltchaStore.setState({ pending: true });
    const setPayloadSpy = vi.spyOn(useAltchaStore.getState(), 'setPayload');

    render(<AltchaModal />);
    const { container } = render(<AltchaModal />);
    expect(container.querySelector('altcha-widget')).toBeInTheDocument();
    const widget = container.querySelector('altcha-widget');
    expect(widget).not.toBeNull();

    // simulate statechange sent by the widget
    const event = new CustomEvent('statechange', {
      detail: { state: 'verified', payload: 'TOKEN_123' },
    });
    act(() =>
      widget!.dispatchEvent(event)
    );

    // check setPayload has been called with the correct token
    expect(setPayloadSpy).toHaveBeenCalledWith('TOKEN_123');

    // pending must be false now
    expect(useAltchaStore.getState().pending).toBe(false);
  });
});