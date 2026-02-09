import { render, screen, fireEvent } from '../../../test-utils/index';
import { describe, it, expect, vi } from 'vitest';
import { RemoveNickFromHistoryButton } from './RemoveNickFromHistoryButton';
import type { Nick } from '../../domain/model/Nick';

// Mock the store for removal
const removeNickMock = vi.fn();
vi.mock('../stores/nick-history.store', () => ({
  useNickHistoryStore: (selector: any) => selector({ removeNick: removeNickMock }),
}));

describe('RemoveNickFromHistoryButton', () => {
  const mockNick: Nick = {
    id: '1',
    words: [{ label: 'Super' }, { label: 'Nick' }],
  } as any;

  it('renders text and icon', () => {
    render(<RemoveNickFromHistoryButton nick={mockNick} onClick={vi.fn()} />);
    
    expect(screen.getByText('Supprimer')).toBeInTheDocument();
    expect(screen.getByLabelText('Supprimer')).toBeInTheDocument(); // icône
  });

  it('calls removeFromHistory and onClick when clicked', () => {
    const onClickMock = vi.fn();

    render(<RemoveNickFromHistoryButton nick={mockNick} onClick={onClickMock} />);

    const button = screen.getByRole('button');
    fireEvent.click(button);

    expect(removeNickMock).toHaveBeenCalledWith(mockNick);
    expect(onClickMock).toHaveBeenCalled();
  });
});
