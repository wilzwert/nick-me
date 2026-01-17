import { Group, ActionIcon } from '@mantine/core';
import { IconInfoCircle, IconMail } from '@tabler/icons-react';

export function FooterIcons() {
  const items = [
    { icon: <IconInfoCircle size={20} />, href: '/about' },
    { icon: <IconMail size={20} />, href: '/contact' },
  ];

  return (
    <Group justify="center" align="end" gap="xl">
      {items.map((item, i) => (
        <ActionIcon key={i} variant="subtle" size="lg" component="a" href={item.href}>
          {item.icon}
        </ActionIcon>
      ))}
    </Group>
  );
}
