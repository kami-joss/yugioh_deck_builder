import { defineStore } from "pinia";

export const useCardStore = defineStore("card", {
  state: () => ({
    card: null,
  }),
  getters: {
    getCard() {
      return this.card;
    },
  },
  actions: {
    async setCard(card) {
      this.card = card;
    },
  },
});
