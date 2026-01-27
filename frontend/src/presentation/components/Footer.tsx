import { ActionIcon, Group, Modal, Tooltip } from "@mantine/core";
import { useState } from "react";
import { IconBulb, IconInfoCircle, IconMail } from "@tabler/icons-react";
import { About } from "./About";
import { ContactForm } from "./ContactForm";
import { SuggestionForm } from "./SuggestionForm";

export function Footer() {
    const [aboutOpen, setAboutOpen] = useState(false);
    const [contactOpen, setContactOpen] = useState(false);
    const [suggestionOpen, setSuggestionOpen] = useState(false);

    const items = [
    { icon: <Tooltip label="À propos"><IconInfoCircle size={20} title="À propos"/></Tooltip>, onClick: setAboutOpen},
    { icon: <Tooltip label="Suggérer un mot"><IconBulb size={20} title="Suggérer un mot"/></Tooltip>, onClick: setSuggestionOpen},
    { icon: <Tooltip label="Contact"><IconMail size={20} title="Contact"/></Tooltip>, onClick: setContactOpen},
  ];

    

  return (
    <>
      <Group justify="center" align="end" gap="xl">
          {items.map((item, i) => (
          <ActionIcon key={i} variant="subtle" size="lg" component="a" onClick={() => item.onClick(true)}>
              {item.icon}
          </ActionIcon>
          ))}
      </Group>

      <Modal opened={aboutOpen} onClose={() => setAboutOpen(false)} title="À propos">
        <About />
      </Modal>

      <Modal opened={contactOpen} onClose={() => setContactOpen(false)} title="Contact">
        <ContactForm onClose={() => setContactOpen(false)} />
      </Modal>

      <Modal opened={suggestionOpen} onClose={() => setSuggestionOpen(false)} title="Suggérer un mot">
        <SuggestionForm onClose={() => setSuggestionOpen(false)} />
      </Modal>
    </>
  );
}