import { render, screen, within } from '../../../test-utils';
import userEvent from '@testing-library/user-event';
import { About } from './About';
import { describe, expect, it, vi } from 'vitest';

vi.mock('./ContactForm', () => ({
  ContactForm: ({ onClose }: { onClose: () => void }) => (
    <div>
      <p>Contact form</p>
      <button onClick={onClose}>Close contact</button>
    </div>
  ),
}));

vi.mock('./SuggestionForm', () => ({
  SuggestionForm: ({ onClose }: { onClose: () => void }) => (
    <div>
      <p>Suggestion form</p>
      <button onClick={onClose}>Close suggestion</button>
    </div>
  ),
}));

describe('About', () => {

  
  it('renders main descriptive content', () => {
    render(<About />);

    expect(
      screen.getByText(/NickMe est un générateur de pseudos/i)
    ).toBeInTheDocument();

    expect(
      screen.getByText(/Mentions légales & Politique de confidentialité/i)
    ).toBeInTheDocument();
  });

  it('opens contact modal when clicking contact icon', async () => {
    const user = userEvent.setup();
    render(<About />);

    const contactIcon = screen.getAllByRole('button', {name: /contact/i})[0];
    await user.click(contactIcon);

    expect(
      screen.getByRole('dialog', { name: 'Contact' })
    ).toBeInTheDocument();

    expect(screen.getByText('Contact form')).toBeInTheDocument();
  });

  
  it('closes contact modal when form triggers close', async () => {
    const user = userEvent.setup();
    render(<About />);

    const contactIcon = screen.getAllByRole('button', {name: /contact/i})[0];
    await user.click(contactIcon);

    await user.click(screen.getByText('Close contact'));

    expect(
      screen.queryByRole('dialog', { name: 'Contact' })
    ).not.toBeInTheDocument();
  });
  
  it('opens suggestion modal when clicking suggestion icon', async () => {
    const user = userEvent.setup();
    render(<About />);

    const suggestionIcon = screen.getByRole('button', {name: /suggérer/i});
    await user.click(suggestionIcon);

    expect(
      screen.getByRole('dialog', { name: 'Suggérer un mot' })
    ).toBeInTheDocument();

    expect(screen.getByText('Suggestion form')).toBeInTheDocument();
  });

  it('closes contact modal when user clicks close', async () => {
    const user = userEvent.setup();
    render(<About />);

    const suggestionIcon = screen.getAllByRole('button', {name: /contact/i})[0];
    await user.click(suggestionIcon);

    const dialog = screen.queryByRole('dialog', { name: 'Contact' });
    const closeButton = within(dialog!).queryAllByRole('button')[0];

    await user.click(closeButton);

    expect(
      screen.queryByRole('dialog', { name: 'Contact' })
    ).not.toBeInTheDocument();
  });

  it('closes suggestion modal when form triggers close', async () => {
    const user = userEvent.setup();
    render(<About />);

    const suggestionIcon = screen.getAllByRole('button', {name: /suggérer/i})[0];
    await user.click(suggestionIcon);

    await user.click(screen.getByText('Close suggestion'));

    expect(
      screen.queryByRole('dialog', { name: 'Suggérer un mot' })
    ).not.toBeInTheDocument();
  });

  it('closes suggestion modal when user clicks close', async () => {
    const user = userEvent.setup();
    render(<About />);

    const suggestionIcon = screen.getAllByRole('button', {name: /suggérer/i})[0];
    await user.click(suggestionIcon);

    const dialog = screen.queryByRole('dialog', { name: 'Suggérer un mot' });
    const closeButton = within(dialog!).queryAllByRole('button')[0];

    await user.click(closeButton);

    expect(
      screen.queryByRole('dialog', { name: 'Suggérer un mot' })
    ).not.toBeInTheDocument();
  });
});