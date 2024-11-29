<template>
    <div class="d-flex flex-column h-100">
      <Header />
      <div class="d-flex flex-grow-1">
        <UserSidebar class="flex-shrink-0 bg-light border-right" />
        <div class="content p-4 flex-grow-1">
          <router-view />
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import Header from '../Header.vue';
  import UserSidebar from './UserSidebar.vue';
  
  export default {
    name: 'UserLayout',
    components: {
      Header,
      UserSidebar,
    },
  };
  </script>
  