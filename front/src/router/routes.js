const routes = [
  {
    path: "/",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      { path: "", component: () => import("pages/IndexPage.vue") },
      {
        path: "/card/:id",
        component: () => import("pages/cards/ShowCard.vue"),
      },
      {
        path: "/decks/create",
        component: () => import("pages/decks/DeckCreatePage.vue"),
        name: "deck-create",
      },
      {
        path: "/decks/edit/:id",
        component: () => import("pages/decks/DeckEditPage.vue"),
        name: "deck-edit",
      },
      {
        path: "/decks",
        component: () => import("pages/decks/DecksIndexPage.vue"),
      },
      {
        path: "/decks/:id",
        component: () => import("pages/decks/DeckViewPage.vue"),
      },
      {
        path: "/user/:id",
        component: () => import("pages/users/UserEditPage.vue"),
        name: "user",
      },
      {
        path: "/user/:id/favorites",
        component: () => import("pages/users/UserFavoritesPage.vue"),
        name: "user-favorites",
      },
      {
        path: "/user/:id/decks",
        component: () => import("pages/users/UserDecksPage.vue"),
        name: "user-decks",
      },
      {
        path: "/redirect",
        component: () => import("pages/auth/RedirectPage.vue"),
      },
    ],
  },
  {
    path: "/login",
    component: () => import("layouts/LoginLayout.vue"),
    children: [
      { path: "", component: () => import("pages/auth/LoginPage.vue") },
    ],
    name: "login",
  },
  {
    path: "/register",
    component: () => import("layouts/LoginLayout.vue"),
    children: [
      { path: "", component: () => import("pages/auth/RegisterPage.vue") },
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: "/:catchAll(.*)*",
    component: () => import("pages/ErrorNotFound.vue"),
  },
];

export default routes;
