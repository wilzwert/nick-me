import { NickRequestContext } from "../model/NickRequestContext";
import { ApiClient } from "../services/ApiClient";

export class GenerateNicknameHandler {
  constructor(private readonly apiClient: ApiClient) {}

  async handle(ctx: NickRequestContext): Promise<string> {
    return this.apiClient.generateNickname(ctx);
  }
}
