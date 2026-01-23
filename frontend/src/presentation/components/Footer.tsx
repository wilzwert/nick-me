import { ActionIcon, Group, Modal } from "@mantine/core";
import { useState } from "react";
import { IconInfoCircle, IconMail } from "@tabler/icons-react";
import { About } from "./About";
import { ContactForm } from "./ContactForm";

export function Footer() {
    const [aboutOpen, setAboutOpen] = useState(false);
    const [contactOpen, setContactOpen] = useState(false);

    const items = [
    { icon: <IconInfoCircle size={20} />, onClick: setAboutOpen},
    { icon: <IconMail size={20} />, onClick: setContactOpen},
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
    </>
  );
}