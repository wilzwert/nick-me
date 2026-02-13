import type { Config } from 'jest';

const config: Config = {
  preset: 'ts-jest',
  testEnvironment: 'node',

  moduleNameMapper: {
    '^(\\.{1,2}/.*)\\.js$': '$1'
  },

  testMatch: ['**/?(*.)+(spec|test).ts'],

  clearMocks: true,
  restoreMocks: true,
  
  transformIgnorePatterns: [
    '/node_modules/',
  ],
};

export default config;
