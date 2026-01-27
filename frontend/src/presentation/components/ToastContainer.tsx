// src/presentation/components/ToastContainer.tsx
import { useToastStore } from "../stores/toast.store";

export function ToastContainer() {
  const toasts = useToastStore((s) => s.toasts);

  return (
    toasts.length > 0 && <div style={{ position: "fixed", bottom: 60, left: 0, right: 0, padding: "10px", textAlign: 'center', zIndex: 1000, backgroundColor: 'var(--mantine-color-body)' }}>
      {toasts.map((toast) => (
        <div
          key={toast.id}
          className={`${
            toast.type === "success"
              ? "success"
              : toast.type === "error"
              ? "error"
              : ""
          }`}
        >
          {toast.message}
        </div>
      ))}
    </div>
  );
}
