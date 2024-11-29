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
            <h2>{{ totalMails }}</h2>
            <a href="#" class="btn btn-outline-light btn-sm mt-3">View Details</a>
          </div>
        </div>
      </div>

      <!-- Logged Activities Section (Now below Mail Card) -->
      <div class="col-lg-4 mt-3 mt-lg-0"> <!-- Added mt-3 to provide space between the cards -->
        <div class="card bg-danger text-white">
          <div class="card-body">
            <h5>Logged Activities Today</h5>
            <h2>{{ loginCountToday }}</h2>
            <a href="#" class="btn btn-outline-light btn-sm mt-3">View Details</a>
          </div>
        </div>
        <div class="mt-3">
          <h5>Recent Activities</h5>
          <ul class="list-group">
            <li class="list-group-item small" v-for="activity in recentActivities" :key="activity.id">
              <strong>{{ activity.user_full_name }}</strong> 
              <span class="text-muted">- {{ activity.action }}</span>
              <br>
              <small class="text-muted float-right">{{ timeAgo(activity.created_at) }}</small>
            </li>
          </ul>
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
      totalMails: 0, // Total number of mails
      recentActivities: [], // List of recent activities
      loginCountToday: 0, // Variable to store today's login count
    };
  },
  created() {
    this.fetchDocumentCounts();
    this.fetchMailCounts();
    this.fetchRecentActivities();
  },
  methods: {
    fetchDocumentCounts() {
      axios.get('/api/documents/counts')
        .then(response => {
          this.totalDocuments = response.data.total; // Update according to the actual response structure
          this.documentCounts = {};
          response.data.counts.forEach(item => {
            this.documentCounts[item.document_type] = item.count; // Populate the documentCounts object
          });
        })
        .catch(error => {
          console.error("There was an error fetching document counts:", error);
        });
    },
    fetchMailCounts() {
      axios.get('/api/mails/count')
        .then(response => {
          this.totalMails = response.data.total; // Update according to the actual response structure
        })
        .catch(error => {
          console.error("There was an error fetching mail counts:", error);
        });
    },
    fetchRecentActivities() {
      axios.get('/api/logs/recent-activities')
        .then(response => {
          this.recentActivities = response.data.activities; // List of activities
          this.loginCountToday = response.data.login_count_today; // Today's login count
        })
        .catch(error => {
          console.error("There was an error fetching recent activities:", error);
        });
    },
    timeAgo(date) {
      return moment(date).fromNow();
    }
  }
};
</script>
