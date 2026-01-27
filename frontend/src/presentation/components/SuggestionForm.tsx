import { useExecuteWithAltcha } from '../../infrastructure/altcha.service';
import { Box, Button, Card, LoadingOverlay, Stack, TextInput } from '@mantine/core';
import { useState } from 'react';
import { useCreateSuggestion } from '../../application/createSuggestion';
import { useForm } from '@mantine/form';

export function SuggestionForm({ onClose }: { onClose?: () => void }) {
  const form = useForm({
    mode: 'uncontrolled',
    initialValues: { label: '', senderEmail: '' },

    // functions will be used to validate values at corresponding key
    validate: {
      label: (value) => (value.length < 2 ? 'Le mot doit comporter au moins 2 caractères' : null),
      senderEmail: (value) => (null === value || /^\S+@\S+$/.test(value) ? null : 'Email invalide'),
    },
  });
  
  const [isSubmitted, setSubmitted] = useState(false);
  const executeWithAltcha = useExecuteWithAltcha();
  const { mutate: createSuggestion, isPending } = useCreateSuggestion();
  
  return (
  
    <Card>
    <Box pos="relative">
      <LoadingOverlay visible={isPending || isSubmitted} zIndex={1000} color='pink' overlayProps={{ radius: "sm", blur: 2, opacity: 0.5 }} />
      <form onSubmit={form.onSubmit((values) => {
          setSubmitted(true);
          executeWithAltcha(() => {
            createSuggestion(values, {
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
          label="Votre email (optionnel)"
          name="senderEmail"
          placeholder="Votre email"
          {...form.getInputProps('senderEmail')}
        />

        <TextInput required
          label="Votre mot"
          name="label"
          placeholder="Votre mot"
          {...form.getInputProps('label')}
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
