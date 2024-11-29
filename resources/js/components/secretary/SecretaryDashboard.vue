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

      <!-- Mail Section -->
      <div class="col-lg-4">
        <div class="card bg-warning text-white">
          <div class="card-body">
            <h5>Number of Mails</h5>
            <h2>50</h2>
            <a href="#" class="btn btn-outline-light btn-sm mt-3">View Details</a>
          </div>
        </div>
      </div>

      <!-- Logged Activities Section -->
      <div class="col-lg-4">
        <div class="card bg-danger text-white">
          <div class="card-body">
            <h5>Logged Activities Today</h5>
            <h2>{{ loginCountToday }}</h2>
            <a href="#" class="btn btn-outline-light btn-sm mt-3">View Details</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';

export default {
  data() {
    return {
      totalDocuments: 0,
      documentCounts: {}, // Object to hold counts for each document type
      recentActivities: [],
      loginCountToday: 0, // Variable to store today's login count
    };
  },
  created() {
    this.fetchDocumentCounts();
    this.fetchRecentActivities();
  },
  methods: {
    fetchDocumentCounts() {
      axios.get('/api/documents/counts')
        .then(response => {
          this.totalDocuments = response.data.total; // Update according to the actual response structure
          this.documentCounts = {}; // Initialize the documentCounts object
          response.data.counts.forEach(item => {
            this.documentCounts[item.document_type] = item.count; // Populate the documentCounts object
          });
        })
        .catch(error => {
          console.error("There was an error fetching document counts:", error);
        });
    },
    fetchRecentActivities() {
      axios.get('/api/recent-activities')
        .then(response => {
          this.recentActivities = response.data.activities; // Update to access activities from the response
          this.loginCountToday = response.data.login_count_today; // Get today's login count
        })
        .catch(error => {
          console.error("There was an error fetching the recent activities:", error);
        });
    },
    timeAgo(date) {
      return moment(date).fromNow();
    }
  }
};
</script>

<style scoped>
/* Add custom styles here */
</style>
