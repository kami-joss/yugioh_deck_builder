import { defineStore } from "pinia";

export const useDeckStore = defineStore("deck", {
  state: () => ({
    deck: null,
  }),
  getters: {
    getDeck() {
      return this.deck;
    },
  },
  actions: {
    async setDeck(deck) {
      this.deck = deck;
    },
  },
});
