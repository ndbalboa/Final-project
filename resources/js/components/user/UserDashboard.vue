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
              <tr v-for="(document, type) in documentCounts" :key="type">
                <td>{{ document.type }}</td>
                <td>{{ document.count }}</td>
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
      documentCounts: [], // Array to hold counts for each document type
      recentActivities: [],
      loginCountToday: 0, // Variable to store today's login count
    };
  },
  created() {
    this.fetchUserDocumentCounts(); // Update to fetch user-specific document counts
    this.fetchRecentActivities();
  },
  methods: {
    fetchUserDocumentCounts() {
      axios.get('/api/user/documents/counts') // Update the API endpoint to fetch user-specific counts
        .then(response => {
          this.totalDocuments = response.data.total; // Update according to the actual response structure
          this.documentCounts = response.data.counts; // Directly populate documentCounts from the response
        })
        .catch(error => {
          console.error("There was an error fetching user-specific document counts:", error);
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
