type PendingAction = (payload: string) => void;
let _executeWithAltcha: ((callback: PendingAction) => void) | null = null;

export function setAltchaExecutor(fn: (callback: PendingAction) => void) {
  _executeWithAltcha = fn;
}

export function executeWithAltcha(callback: PendingAction) {
  if (!_executeWithAltcha) throw new Error('AltchaModal not mounted');
  _executeWithAltcha(callback);
}
