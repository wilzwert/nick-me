import { useExecuteWithAltcha } from '../../infrastructure/altcha.service';
import { Box, Button, Card, LoadingOverlay, Stack, Textarea, TextInput } from '@mantine/core';
import { useState } from 'react';
import { useForm } from '@mantine/form';
import { useCreateReport } from '../../application/createReport';


export function ReportForm({ nickId, onClose }: { nickId: number; onClose?: () => void }) {
  const form = useForm({
    mode: 'uncontrolled',
    initialValues: { senderEmail: '', reason: '', nickId: nickId },

    // functions will be used to validate values at corresponding key
    validate: {
      reason: (value) => (value.length < 10 ? 'Le message doit comporter au moins 10 caractères' : null),
      senderEmail: (value) => (null === value || /^\S+@\S+$/.test(value) ? null : 'Email invalide'),
      nickId: (value) => (null !== value ? null : 'Pseudo introuvable'),
    },
  });
  
  const [isSubmitted, setSubmitted] = useState(false);
  const executeWithAltcha = useExecuteWithAltcha();
  const { mutate: sendReport, isPending } = useCreateReport();

  return (
  
    <Card>
    <Box pos="relative">
      <LoadingOverlay visible={isPending || isSubmitted} zIndex={1000} color='pink' overlayProps={{ radius: "sm", blur: 2, opacity: 0.5 }} />
      <form onSubmit={form.onSubmit((values) => {
          setSubmitted(true);
          executeWithAltcha(() => {
            sendReport(values, {
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
          label="Raison du signalement"
          required
          name="reason"
          placeholder="Votre message"
          {...form.getInputProps('reason')}
        />

        <input type="hidden" name="nickId" value={nickId} />
          
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
