import { render, screen, fireEvent } from '../../../test-utils/index';
import { describe, it, expect, vi } from 'vitest';
import { NickHistoryElement } from './NickHistoryElement';
import { useNickStore } from '../stores/nick.store';

const setNickMock = vi.fn();
vi.mock('../stores/nick.store', () => ({
  useNickStore: (selector: any) =>
    selector({
      setNick: setNickMock,
    }),
}));

vi.mock('./CopyNickButton', () => ({
  CopyNickButton: ({ onClick }: any) => (
    <button onClick={onClick}>CopyNickButton</button>
  ),
}));

vi.mock('./RemoveNickFromHistoryButton', () => ({
  RemoveNickFromHistoryButton: ({ onClick }: any) => (
    <button onClick={onClick}>RemoveNickFromHistoryButton</button>
  ),
}));

vi.mock('./ReportNickButton', () => ({
  ReportNickButton: ({ onClick }: any) => (
    <button onClick={onClick}>ReportNickButton</button>
  ),
}));

describe('NickHistoryElement', () => {
  const mockNick = {
    id: '1',
    words: [{ label: 'Super' }, { label: 'Nick' }],
  };

  const renderComponent = () =>
    render(
        <NickHistoryElement nick={mockNick as any} />
    );
    
  it('displays the nick text', () => {
    renderComponent();

    expect(screen.getByText('Super Nick')).toBeInTheDocument();
  });

  it('sets the nick in store when clicking the paper', () => {
    renderComponent();

    const { setNick } = useNickStore((s: any) => s);
    fireEvent.click(screen.getByRole('button', {name: 'Super Nick'}));

    expect(setNick).toHaveBeenCalledWith(mockNick);
  });
  
  it('opens the menu and renders menu actions', () => {
    renderComponent();

    fireEvent.click(
      screen.getByLabelText('Actions sur le pseudo')
    );

    expect(screen.getByText('CopyNickButton')).toBeInTheDocument();
    expect(screen.getByText('RemoveNickFromHistoryButton')).toBeInTheDocument();
    expect(screen.getByText('ReportNickButton')).toBeInTheDocument();
  });
});
