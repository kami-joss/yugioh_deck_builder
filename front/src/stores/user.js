import { defineStore } from "pinia";
import { api } from "src/boot/axios";

export const useUserStore = defineStore("user", {
  state: () => ({
    user: null,
  }),
  getters: {
    getUser() {
      return this.user;
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
          localStorage.setItem("token", JSON.stringify(response.data.token));

          this.user = {
            user: response.data.user,
            token: response.data.token,
          };

          api.defaults.headers.common[
            "Authorization"
          ] = `Bearer ${response.data.token}`;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async logout() {
      api.defaults.headers.common["Authorization"] = ``;
      localStorage.removeItem("user");
      window.location.reload;
      this.setUser(null);
      api.post("/sanctum/logout").then((response) => {});
    },
    async setUser(user) {
      this.user = user;
    },
    async setFavorites(favorites) {
      this.user.favorites = favorites;
    },
  },
});
