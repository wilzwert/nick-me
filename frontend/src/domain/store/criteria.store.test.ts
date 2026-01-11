// domain/criteria.store.test.ts
import { describe, expect, it, beforeEach } from 'vitest';
import { useCriteriaStore, DEFAULT_CRITERIA } from './criteria.store';
import type { Criteria } from '../model/Criteria';

describe('CriteriaStore', () => {
  beforeEach(() => {
    // reset store before each test
    useCriteriaStore.setState({ criteria: DEFAULT_CRITERIA });
  });

  it('has default criteria', () => {
    const state = useCriteriaStore.getState();
    expect(state.criteria).toEqual(DEFAULT_CRITERIA);
  });

  it('updates criteria', () => {
    const newCriteria: Criteria = { gender: 'F', offenseLevel: 15 };

    useCriteriaStore.getState().setCriteria(newCriteria);

    const state = useCriteriaStore.getState();
    expect(state.criteria).toEqual(newCriteria);
  });
});
