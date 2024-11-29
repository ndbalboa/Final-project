<template>
    <div class="d-flex flex-column h-100">
      <Header />
      <div class="d-flex flex-grow-1">
        <AdminSidebar class="flex-shrink-0 bg-light border-right" />
        <div class="content p-4 flex-grow-1">
          <router-view />
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import Header from '../Header.vue';
  import AdminSidebar from './AdminSidebar.vue';
  
  export default {
    name: 'AdminLayout',
    components: {
      Header,
      AdminSidebar,
    },
  };
  </script>
  <style scoped>
  .d-flex {
    display: flex;
  }
  .content {
    margin-left: 1px; /* Adjust this width according to your sidebar width */

  }
  </style>
  