import { useMutation } from "@tanstack/react-query";
import { useToastStore } from "../presentation/stores/toast.store";
import { createSuggestion } from "../infrastructure/suggestion.api";

interface SuggestionParams {
  senderEmail: string|null;
  label: string;
}

export function useCreateSuggestion() {
  const addToast = useToastStore(s => s.addToast);

  return useMutation<void, Error, SuggestionParams>({
    mutationFn: (params) => createSuggestion(params),
    onSuccess: () => {
      addToast({ type: "success", message: "Suggestion enregistrée !" });
    },
    onError: (err) => {
      addToast({ type: "error", message: err.message || "Erreur lors de l'envoi" });
    }
  });
}
