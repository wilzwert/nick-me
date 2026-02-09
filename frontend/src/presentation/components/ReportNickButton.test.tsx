import { render, screen, fireEvent } from '../../../test-utils/index';
import { describe, it, expect, vi } from 'vitest';
import { ReportNickButton } from './ReportNickButton';
import type { Nick } from '../../domain/model/Nick';

//mock the store
const setNickMock = vi.fn();

vi.mock('../stores/report-nick.store', () => ({
  useReportNickStore: (selector: any) => selector({ setNick: setNickMock }),
}));

describe('ReportNickButton', () => {
  const mockNick: Nick = {
    id: '1',
    words: [{ label: 'Super' }, { label: 'Nick' }],
  } as any;

  it('renders text and icon', () => {
    render(<ReportNickButton nick={mockNick} />);

    expect(screen.getByText('Signaler')).toBeInTheDocument();
    expect(screen.getByRole('button')).toBeInTheDocument();
  });

  it('calls setNick when clicked', () => {
    render(<ReportNickButton nick={mockNick} />);

    const button = screen.getByRole('button');
    fireEvent.click(button);

    expect(setNickMock).toHaveBeenCalledWith(mockNick);
  });

  it('calls onClick if provided', () => {
    const onClickMock = vi.fn();
    render(<ReportNickButton nick={mockNick} onClick={onClickMock} />);

    const button = screen.getByRole('button');
    fireEvent.click(button);

    expect(setNickMock).toHaveBeenCalledWith(mockNick);
    expect(onClickMock).toHaveBeenCalled();
  });

  it('does not throw if onClick is not provided', () => {
    render(<ReportNickButton nick={mockNick} />);

    const button = screen.getByRole('button');
    expect(() => fireEvent.click(button)).not.toThrow();
  });
});
