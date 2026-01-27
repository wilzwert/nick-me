export class ApiError extends Error {
  status: number;
  code: string;
  message: string;


  constructor(params: { status: number; error: string; message: string }) {
    super(params.message ?? 'API Error');
    this.status = params.status;
    this.code = params.error;
    this.message = params.message;
  }
}