import { defineStore } from "pinia";
import { api } from "src/boot/axios";

export const useUserStore = defineStore("user", {
  state: () => ({
    user: null,
    token: null,
  }),
  getters: {
    getUser() {
      return this.user;
    },
    getToken() {
      return this.token;
    },
  },
  actions: {
    async login(email, password) {
      api
        .post("/sanctum/token", {
          email: email,
          password: password,
        })
        .then((response) => {
          localStorage.setItem("user", JSON.stringify(response.data.user));
          localStorage.setItem("token", response.data.token);

          this.user = response.data.user;
          this.token = response.data.token;

          api.defaults.headers.common[
            "Authorization"
          ] = `Bearer ${response.data.token}`;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async logout() {
      api.post("/sanctum/logout").then((response) => {
        api.defaults.headers.common["Authorization"] = ``;
        localStorage.removeItem("user");
        localStorage.removeItem("token");
        this.user = null;
        this.token = null;
        window.location.reload();
      });
    },
    async setUser(user) {
      this.user = user;
    },
    async setFavorites(favorites) {
      this.user.favorites = favorites;
    },
  },
});
