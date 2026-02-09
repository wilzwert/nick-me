import { describe, it, expect, vi } from 'vitest';
import { render, screen, fireEvent, waitFor } from '../../../test-utils/index'
import { CopyNickButton } from './CopyNickButton';
import { type Nick } from '../../domain/model/Nick';

describe('CopyNickButton', () => {
  const mockNick: Nick = {
    id: 999,
    gender: 'AUTO',
    offenseLevel: 5,
    words: [
      { label: 'Super', id: 123, role: 'qualifier' },
      { label: 'Nick', id: 456, role: 'subject' },
    ],
  };
  
  it('displays text', () => {
    render(
        <CopyNickButton nick={mockNick} />
    );

    expect(screen.getByText('Copier')).toBeInTheDocument();
  });

  it('call onClick callback if given', async () => {
    const onClick = vi.fn();
    render(<CopyNickButton nick={mockNick} onClick={onClick} />);

    const button = screen.getByRole('button');
    fireEvent.click(button);

    expect(onClick).toHaveBeenCalled();
  });
  
  it('changes text after click', async () => {
    render(<CopyNickButton nick={mockNick} />);

    const button = screen.getByRole('button');
    fireEvent.click(button);

    // text should update
    await waitFor(() => {
        expect(screen.getByText('Copié')).toBeInTheDocument();
    });
  });

  it('copies the nick', async () => {
    // spy writeText method
    const writeTextSpy = vi.spyOn(navigator.clipboard, 'writeText')
      .mockImplementation(() => Promise.resolve());

    render(<CopyNickButton nick={mockNick} />);
    const button = screen.getByRole('button');
    fireEvent.click(button);

    expect(writeTextSpy).toHaveBeenCalledWith('Super Nick');
    expect(await screen.findByText('Copié')).toBeInTheDocument();

    // restore
    writeTextSpy.mockRestore();
  });
});
