<template>
    <div class="d-flex flex-column h-100">
      <Header />
      <div class="d-flex flex-grow-1">
        <SecretarySideBar class="flex-shrink-0 bg-light border-right" />
        <div class="content p-4 flex-grow-1">
          <router-view />
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import Header from '../Header.vue';
  import SecretarySideBar from './SecretarySideBar.vue';
  
  export default {
    name: 'SecretaryLayout',
    components: {
      Header,
      SecretarySideBar,
    },
  };
  </script>
  