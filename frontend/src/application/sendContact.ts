// useSendContactMessage.ts
import { useMutation } from "@tanstack/react-query";
import { sendContactMessage } from "../infrastructure/contact.api";
import { useToastStore } from "../presentation/stores/toast.store";

interface ContactMessageParams {
  senderEmail: string;
  content: string;
}

export function useSendContactMessage() {
  const addToast = useToastStore(s => s.addToast);

  return useMutation<void, Error, ContactMessageParams>({
    mutationFn: (params) => sendContactMessage(params),
    onSuccess: () => {
      addToast({ type: "success", message: "Message envoyé !" });
    },
    onError: (err) => {
      addToast({ type: "error", message: err.message || "Erreur lors de l'envoi" });
    }
  });
}
