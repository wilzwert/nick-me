import { jest } from '@jest/globals';
console.log('mocking fetch');
const fetch = jest.fn();

// On exporte fetch par défaut
export default fetch;

// Si tu veux utiliser Response dans certains cas
export class Response {
  constructor(public body: any) {}
  json() {
    return Promise.resolve(this.body);
  }
}