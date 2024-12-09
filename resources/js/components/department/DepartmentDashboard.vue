<template>
  <div class="container">
    <div class="row">
      <!-- Documents Section -->
      <div class="col-lg-4">
        <div class="card bg-primary text-white">
          <div class="card-body">
            <h5>Total Number of Documents</h5>
            <h2>{{ totalDocuments }}</h2>
            <a href="#" class="btn btn-outline-light btn-sm mt-3">View Details</a>
          </div>
        </div>
        <div class="mt-3">
          <h5>Document Count</h5>
          <table class="table table-striped">
            <tbody>
              <tr v-for="(count, type) in documentCounts" :key="type">
                <td>{{ type }}</td>
                <td>{{ count }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      totalDocuments: 0,
      documentCounts: {}, // Object to hold counts for each document type
    };
  },
  created() {
    this.fetchDocumentCounts();
  },
  methods: {
    fetchDocumentCounts() {
      axios.get('/api/admin/department/document-type-counts')
        .then(response => {
          // Assuming the response structure contains documentTypeCounts with keys as document types
          this.documentCounts = response.data.documentTypeCounts || {};
          // Calculate the total number of documents from the documentCounts object
          this.totalDocuments = Object.values(this.documentCounts).reduce((acc, count) => acc + count, 0);
        })
        .catch(error => {
          console.error("There was an error fetching document counts:", error);
        });
    },
  }
};
</script>

<style scoped>
/* Add custom styles here */
</style>
