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
      },
      {
        path: "/decks/edit/:id",
        component: () => import("pages/decks/DeckEditPage.vue"),
      },
      {
        path: "/decks",
        component: () => import("pages/decks/DecksIndexPage.vue"),
      },
      {
        path: "/decks/:id",
        component: () => import("pages/decks/DeckViewPage.vue"),
      },
    ],
  },
  {
    path: "/login",
    component: () => import("layouts/LoginLayout.vue"),
    children: [
      { path: "", component: () => import("pages/auth/LoginPage.vue") },
    ],
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
