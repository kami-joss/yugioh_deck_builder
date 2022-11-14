import { defineStore } from "pinia";

export const useCardStore = defineStore("card", {
  state: () => ({
    card: null,
    cardSelected: false,
  }),
  getters: {
    getCard() {
      return this.card;
    },
    getCardSelected() {
      return this.cardSelected;
    },
  },
  actions: {
    async setCard(card) {
      this.card = card;
    },
    async setCardSelected(card) {
      this.cardSelected = card;
    },
  },
});
