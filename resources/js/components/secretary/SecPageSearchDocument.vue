<template>
  <div id="app">
    <header class="header">
      <h2>Search Document</h2>
    </header>
    <div class="main-content">
      <div class="container mt-4">
        <div class="row">
          <div class="col-md-8">
            <div class="row">
              <div class="col">
                <button class="btn btn-primary float-right mb-3" @click="showAdvancedSearch">Advanced Search</button>
              </div>
            </div>
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Search..." v-model="searchQuery">
              <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" @click="search">Search</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Advanced Search Modal -->
    <div class="modal fade" id="advancedSearchModal" tabindex="-1" role="dialog" aria-labelledby="advancedSearchModalLabel" aria-hidden="true" ref="advancedSearchModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="advancedSearchModalLabel">Advanced Search</h5>
            <button type="button" class="close" @click="hideAdvancedSearch" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- Advanced Search Form -->
            <form @submit.prevent="performAdvancedSearch">
              <div class="form-group">
                <label for="keyword">Document Type</label>
                <input type="text" class="form-control" id="keyword" v-model="advancedSearchQuery.keyword">
              </div>
              <div class="form-group">
                <label for="dateFrom">Date Issued</label>
                <input type="date" class="form-control" id="dateFrom" v-model="advancedSearchQuery.dateFrom">
              </div>
              <div class="form-group">
                <label for="documentNumber">Document Number</label>
                <input type="text" class="form-control" id="documentNumber" v-model="advancedSearchQuery.documentNumber">
              </div>
              <div class="form-group">
                <label for="employee">Employee</label>
                <input type="text" class="form-control" id="employee" v-model="advancedSearchQuery.employee">
              </div>
              <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" class="form-control" id="subject" v-model="advancedSearchQuery.subject">
              </div>
              <div class="form-group">
                <label for="from">From</label>
                <input type="text" class="form-control" id="from" v-model="advancedSearchQuery.from">
              </div>
              <div class="form-group">
                <label for="to">To</label>
                <input type="text" class="form-control" id="to" v-model="advancedSearchQuery.to">
              </div>
              <button type="submit" class="btn btn-primary">Search</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Display Search Results in Table -->
    <div v-if="searchResults.length" class="search-results mt-4">
      <h3>Search Results</h3>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Document No</th>
            <th>Subject</th>
            <th>Date Issued</th>
            <th>Employee(s)</th>
            <th>Type</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="document in searchResults" :key="document.id" @click="goToDocumentDetails(document.id, document.document_type)" style="cursor: pointer;">
            <td>{{ document.document_no }}</td>
            <td>{{ document.subject }}</td>
            <td>{{ document.date_issued }}</td>
            <td>
              <ul>
                <li v-for="employee in document.employee_names" :key="employee">{{ employee }}</li>
              </ul>
            </td>
            <td>{{ document && document.document_type ? document.document_type.document_type : 'Travel Order'  }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'DocumentSearch',
  data() {
    return {
      searchQuery: '',
      advancedSearchQuery: {
        keyword: '',
        dateFrom: '',
        documentNumber: '',
        employee: '',
        subject: '',
        from: '',
        to: ''
      },
      searchResults: []
    };
  },
  methods: {
    search() {
      axios.post('/api/admin/search', { searchQuery: this.searchQuery })
        .then(response => {
          this.searchResults = response.data;
        })
        .catch(error => {
          console.error('Error searching documents:', error);
        });
    },
    showAdvancedSearch() {
      const modal = new bootstrap.Modal(this.$refs.advancedSearchModal);
      modal.show();
    },
    hideAdvancedSearch() {
      const modal = new bootstrap.Modal(this.$refs.advancedSearchModal);
      modal.hide();
    },
    performAdvancedSearch() {
      axios.post('/api/admin/advanced-search', this.advancedSearchQuery)
        .then(response => {
          this.searchResults = response.data;
          this.hideAdvancedSearch();
        })
        .catch(error => {
          console.error('Error performing advanced search:', error);
        });
    },
    goToDocumentDetails(documentId, documentType) {
      // Default route for other document types
      this.$router.push({ name: 'SecPageDocumentDetails', params: { id: documentId } });
    }
  }
  
};
</script>

<style scoped>
/* Add custom styles here */
</style>


