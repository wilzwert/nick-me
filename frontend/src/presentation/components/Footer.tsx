import { ActionIcon, Group, Modal, Tooltip } from "@mantine/core";
import { useState } from "react";
import { IconBrandGithub, IconBulb, IconInfoCircle, IconMail } from "@tabler/icons-react";
import { About } from "./About";
import { ContactForm } from "./ContactForm";
import { SuggestionForm } from "./SuggestionForm";

export function Footer() {
    const [aboutOpen, setAboutOpen] = useState(false);
    const [contactOpen, setContactOpen] = useState(false);
    const [suggestionOpen, setSuggestionOpen] = useState(false);

    const items = [
    { label: "À propos", icon: <IconInfoCircle size={20} title="À propos"/>, onClick: setAboutOpen},
    { label: "Suggérer un mot", icon: <IconBulb size={20} title="Suggérer un mot"/>, onClick: setSuggestionOpen},
    { label: "Contact", icon: <IconMail size={20} title="Contact"/>, onClick: setContactOpen},
  ];

    

  return (
    <>
      <Group justify="center" align="end" gap="xl">
          {items.map((item, i) => (
            <Tooltip key={i} label={item.label} position="top" withArrow>
              <ActionIcon key={i} variant="subtle" size="lg"  onClick={() => item.onClick(true)} aria-label={item.label}>
                  {item.icon}
              </ActionIcon>
            </Tooltip>
          ))}
          <Tooltip key={4} label="Github" position="top" withArrow>
              <ActionIcon 
                key={4} 
                variant="subtle" 
                size="lg"  
                aria-label="Project Github" 
                component="a" 
                href="https://github.com/wilzwert/nick-me"
                target="_blank"
                rel="noopener noreferrer">
                  <IconBrandGithub size={20} title="Github"/>
              </ActionIcon>
            </Tooltip>
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