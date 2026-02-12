import { useExecuteWithAltcha } from '../../infrastructure/altcha.service';
import { Box, Button, Card, LoadingOverlay, Stack, Textarea, TextInput } from '@mantine/core';
import { useState } from 'react';
import { useSendContactMessage } from '../../application/sendContact';
import { useForm } from '@mantine/form';


export function ContactForm({ onClose }: { onClose?: () => void }) {
  const form = useForm({
    mode: 'uncontrolled',
    initialValues: { senderEmail: '', content: '' },

    // functions will be used to validate values at corresponding key
    validate: {
      content: (value) => (value.length < 10 ? 'Le message doit comporter au moins 10 caractères' : null),
      senderEmail: (value) => (null === value || /^\S+@\S+$/.test(value) ? null : 'Email invalide'),
    },
  });
  
  const [isSubmitted, setSubmitted] = useState(false);
  const executeWithAltcha = useExecuteWithAltcha();
  const { mutate: sendContact, isPending } = useSendContactMessage();

  return (
  
    <Card>
    <Box pos="relative">
      <LoadingOverlay visible={isPending || isSubmitted} zIndex={1000} color='pink' overlayProps={{ radius: "sm", blur: 2, opacity: 0.5 }} />
      <p>Une suggestion ? Une question ? N'hésitez pas à me contacter !</p>
      <form onSubmit={form.onSubmit((values) => {
          setSubmitted(true);
          executeWithAltcha(() => {
            sendContact(values, {
              onSuccess: () => {
                form.reset();
                if (onClose) onClose();
              }
            });
            setSubmitted(false);
          })
        })}>
       <Stack gap={20}>

        <TextInput
          label="Votre email"
          required
          name="senderEmail"
          placeholder="Votre email"
          {...form.getInputProps('senderEmail')}
        />

        <Textarea
          label="Votre message"
          required
          name="content"
          placeholder="Votre message"
          {...form.getInputProps('content')}
        />
          
        <Box>
        <Button 
          type="submit" 
          disabled={isPending || isSubmitted}
        >Go</Button>
        </Box>
      </Stack>
      </form>
    </Box>
    </Card>
  );
}
