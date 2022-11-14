import { defineStore } from "pinia";

export const useTabStore = defineStore("tab", {
  state: () => ({
    tab: null,
  }),
  getters: {
    getTab() {
      return this.tab;
    },
  },
  actions: {
    async setTab(tab) {
      this.tab = tab;
    },
  },
});
