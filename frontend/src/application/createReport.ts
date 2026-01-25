import { useMutation } from "@tanstack/react-query";
import { useToastStore } from "../presentation/stores/toast.store";
import { createReport } from "../infrastructure/report.api";

interface ReportParams {
  senderEmail: string|null;
  reason: string;
  nickId: number;
}

export function useCreateReport() {
  const addToast = useToastStore(s => s.addToast);

  return useMutation<void, Error, ReportParams>({
    mutationFn: (params) => createReport(params),
    onSuccess: () => {
      addToast({ type: "success", message: "Signalement enregistré !" });
    },
    onError: (err) => {
      addToast({ type: "error", message: err.message || "Erreur lors de l'envoi" });
    }
  });
}
