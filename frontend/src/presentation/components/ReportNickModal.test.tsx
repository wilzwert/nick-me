import { render, screen, fireEvent } from '../../../test-utils/index';
import { describe, it, expect, vi, beforeEach } from 'vitest';
import { ReportNickModal } from './ReportNickModal';
import type { Nick } from '../../domain/model/Nick';

// mock the store
let nickMock: Nick | null = null;
const setNickMock = vi.fn();

vi.mock('../stores/report-nick.store', () => ({
  useReportNickStore: (selector: any) =>
    selector({
      nick: nickMock,
      setNick: setNickMock,
    }),
}));

describe('ReportNickModal', () => {
  beforeEach(() => {
    nickMock = null;
    setNickMock.mockClear();
  });

  const sampleNick: Nick = {
    id: '1',
    words: [{ label: 'Super' }, { label: 'Nick' }],
  } as any;
  /*
  it('does not render modal if nick is null', () => {
    render(<ReportNickModal />);
    expect(screen.queryByRole('dialog')).not.toBeInTheDocument();
  });
  */
  
  it('renders modal with correct title if nick is present', () => {
    nickMock = sampleNick;

    render(<ReportNickModal />);

    const modal = screen.getByRole('dialog');
    expect(modal).toBeInTheDocument();
    expect(screen.getByText('Signaler "Super Nick"')).toBeInTheDocument();
    expect(screen.getByText('Raison du signalement')).not.toBeNull;
  });
  
  it('calls setNick(null) when modal is closed', () => {
    nickMock = sampleNick;

    render(<ReportNickModal />);
    const modal = screen.getByRole('dialog');
    expect(modal).toBeInTheDocument();
    // first button in modal should be the closing one
    // FIXME : this seems not very reliable
    const closeButton = modal.querySelector('button');
    fireEvent.click(closeButton!);
    expect(setNickMock).toHaveBeenCalledWith(null);
  });
});
