Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'nova-page',
      path: '/nova-page/:id?',
      component: require('./views/Page').default,
    },
  ]);
});
