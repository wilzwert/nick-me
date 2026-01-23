import { useExecuteWithAltcha } from '../../infrastructure/altcha.service';
import { Box, Button, Card, LoadingOverlay, Stack, Textarea, TextInput } from '@mantine/core';
import { useState } from 'react';
import { useSendContactMessage } from '../../application/sendContact';


interface ContactFormValues {
  senderEmail: string;
  content: string;
}


export function ContactForm({ onClose }: { onClose?: () => void }) {
  const [form, setForm] = useState<ContactFormValues>({
    senderEmail: "",
    content: "",
  });
  const [errors, setErrors] = useState<Partial<ContactFormValues>>({});
  const [isSubmitted, setSubmitted] = useState(false);

  const executeWithAltcha = useExecuteWithAltcha();
  const { mutate: sendContact, isPending } = useSendContactMessage();

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    setForm((prev) => ({ ...prev, [e.target.name]: e.target.value }));
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();

    const newErrors: Partial<ContactFormValues> = {};
      if (!form.senderEmail) newErrors.senderEmail = "Email requis";
      if (!form.content) newErrors.content = "Message requis";
      setErrors(newErrors);

      if (Object.keys(newErrors).length === 0) {
         executeWithAltcha(() => {
            sendContact(form, {
              onSuccess: () => {
                setForm({ senderEmail: "", content: "" });
                if (onClose) onClose();
              }
            });
            setSubmitted(false);
         });
      }
  };

  return (
  
    <Card>
    <Box pos="relative">
      <LoadingOverlay visible={isPending || isSubmitted} zIndex={1000} color='pink' overlayProps={{ radius: "sm", blur: 2, opacity: 0.5 }} />
      <form onSubmit={handleSubmit}>
       <Stack gap={20}>

        <TextInput
          type="email"
          name="senderEmail"
          value={form.senderEmail}
          onChange={handleChange}
          placeholder="Votre email"
        />
        {errors.senderEmail && <span className="error">{errors.senderEmail}</span>}

        <Textarea
          name="content"
          value={form.content}
          onChange={handleChange}
          placeholder="Votre message"
        />
        {errors.content && <span className="error">{errors.content}</span>}
          
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
