import { create } from "zustand";
import type { Criteria } from "./model/Criteria";

interface CriteriaStore {
    criteria: Criteria;
    setCriteria: (criteria: Criteria) => void;
}

export const DEFAULT_CRITERIA: Criteria = {
  gender: 'NEUTRAL',
  offenseLevel: 5,
  // ajoute ici d’autres champs si nécessaire
};

export const useCriteriaStore = create<CriteriaStore>(set => ({
  criteria: DEFAULT_CRITERIA,

  setCriteria: (criteria: Criteria) => set({ criteria })
}));