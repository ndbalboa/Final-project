<template>
  <div class="container">
    <div class="row">
      <!-- Documents Section -->
      <div class="col-lg-4">
        <div class="card bg-primary text-white">
          <div class="card-body d-flex align-items-center">
            <i class="fas fa-file-alt fa-3x me-3"></i>
            <div>
              <h5>Total Number of Documents</h5>
              <h2>{{ totalDocuments }}</h2>
              <a href="#" class="btn btn-outline-light btn-sm mt-3">View Details</a>
            </div>
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
          <div class="card-body d-flex align-items-center">
            <i class="fas fa-envelope fa-3x me-3"></i>
            <div>
              <h5>Number of Mails</h5>
              <h2>{{ totalMails }}</h2>
              <a href="#" class="btn btn-outline-light btn-sm mt-3" @click.prevent="toggleMailDetails">View Details</a>
            </div>
          </div>
        </div>
        <!-- Mail Details Table -->
        <div v-if="showMailDetails" class="mt-3">
          <h5>Mail Details</h5>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>To</th>
                <th>From</th>
                <th>Priority</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="mail in mailDetails" :key="mail.id">
                <td>{{ mail.to }}</td>
                <td>{{ mail.from }}</td>
                <td>{{ mail.priority }}</td>
                <td>{{ mail.status }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Logged Activities Section -->
      <div class="col-lg-4 mt-3 mt-lg-0">
        <div class="card bg-danger text-white">
          <div class="card-body d-flex align-items-center">
            <i class="fas fa-user-clock fa-3x me-3"></i>
            <div>
              <h5>Logged Activities Today</h5>
              <h2>{{ loginCountToday }}</h2>
              <a href="#" class="btn btn-outline-light btn-sm mt-3">View Details</a>
            </div>
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
      showMailDetails: false, // Toggle mail details visibility
      mailDetails: [], // List of mail details
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
          this.totalDocuments = response.data.total;
          this.documentCounts = {};
          response.data.counts.forEach(item => {
            this.documentCounts[item.document_type] = item.count;
          });
        })
        .catch(error => {
          console.error("There was an error fetching document counts:", error);
        });
    },
    fetchMailCounts() {
      axios.get('/api/admin/mails/count')
        .then(response => {
          this.totalMails = response.data.total;
        })
        .catch(error => {
          console.error("There was an error fetching mail counts:", error);
        });
    },
    fetchMailDetails() {
      axios.get('/api/admin/mails/details')
        .then(response => {
          this.mailDetails = response.data.mails;
        })
        .catch(error => {
          console.error("There was an error fetching mail details:", error);
        });
    },
    fetchRecentActivities() {
      axios.get('/api/logs/recent-activities')
        .then(response => {
          this.recentActivities = response.data.activities;
          this.loginCountToday = response.data.login_count_today;
        })
        .catch(error => {
          console.error("There was an error fetching recent activities:", error);
        });
    },
    toggleMailDetails() {
      this.showMailDetails = !this.showMailDetails;
      if (this.showMailDetails) {
        this.fetchMailDetails();
      }
    },
    timeAgo(date) {
      return moment(date).fromNow();
    },
  },
};
</script>
