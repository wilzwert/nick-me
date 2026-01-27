export type ToastIdGenerator = () => string;

let currentGenerator: ToastIdGenerator = () => crypto.randomUUID();

export function setToastIdGenerator(generator: ToastIdGenerator) {
  currentGenerator = generator;
}

export function generateToastId(): string {
  return currentGenerator();
}
