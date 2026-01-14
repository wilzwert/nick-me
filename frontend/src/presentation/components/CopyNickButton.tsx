import { useState } from 'react';
import { type Nick } from '../../domain/model/Nick';


interface Props {
  nick: Nick;
  className?: string;
}

export function CopyNickButton({ nick, className }: Props) {
  const [copied, setCopied] = useState(false);

  async function handleCopy() {
    const text = nick.words.map(w => w.label).join(' ');
    await navigator.clipboard.writeText(text);

    setCopied(true);
    setTimeout(() => setCopied(false), 1500);
  }

  return (
    <button
      type="button"
      onClick={handleCopy}
      className={className}
      title="Copier le pseudo"
    >
      {copied ? '✔ Copié !' : '📋 Copier'}
    </button>
  );
}
