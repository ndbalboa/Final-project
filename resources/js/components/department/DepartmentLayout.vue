<template>
    <div class="d-flex flex-column h-100">
      <Header />
      <div class="d-flex flex-grow-1">
        <DepartmentSidebar class="flex-shrink-0 bg-light border-right" />
        <div class="content p-4 flex-grow-1">
          <router-view />
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import Header from '../Header.vue';
  import DepartmentSidebar from './DepartmentSidebar.vue';
  
  export default {
    name: 'DepartmentLayout',
    components: {
      Header,
      DepartmentSidebar,
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
  