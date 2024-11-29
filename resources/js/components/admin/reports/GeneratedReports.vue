<template>
  <div class="report-container">
    <h2>Generated Reports</h2>
    <p>Results 1-{{ reports.length }} out of {{ reports.length }} | Page 1 of 1</p>
    
    <table class="report-table">
      <thead>
        <tr>
          <th>Description</th>
          <th>Date Generated</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="report in reports" :key="report.id">
          <td>{{ report.description }}</td>
          <td>{{ formatDate(report.created_at) }}</td>
          <td class="actions">
            <button v-if="report.filePath" class="btn btn-view" @click="viewReport(report)">
              <i class="fas fa-eye"></i> View
            </button>
            <button class="btn btn-delete" @click="deleteReport(report.id)">
              <i class="fas fa-trash-alt"></i> Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      reports: [], // List of reports to display
    };
  },
  methods: {
    // Format date as shown in the provided code
    formatDate(dateString) {
      const options = { year: 'numeric', month: 'short', day: '2-digit', hour: '2-digit', minute: '2-digit', hour12: true };
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', options);
    },
    viewReport(report) {
      if (report.filePath) {
        window.open(report.filePath, '_blank'); // Open the report in a new tab
      } else {
        alert('No file available for this report.');
      }
    },
    // Fetch reports from the API (replace with your actual endpoint)
    async fetchReports() {
      try {
        const response = await axios.get('/api/admin/reports');
        console.log('Fetched reports:', response.data);
        this.reports = response.data.map(report => {
          if (report.filePath) {
            return {
              ...report,
              filePath: `http://127.0.0.1:8000/storage/${report.filePath}`,
            };
          } else {
            console.warn(`Report ID ${report.id} is missing a filePath.`);
            return { ...report, filePath: null };
          }
        });
      } catch (error) {
        console.error('Error fetching reports:', error);
      }
    },
    // Delete a report
    async deleteReport(reportId) {
      const confirmation = confirm('Are you sure you want to delete this report?');
      if (confirmation) {
        try {
          // Send DELETE request to the backend
          const response = await axios.delete(`/api/admin/reports/${reportId}`);
          console.log('Report deleted:', response.data);

          // Remove the deleted report from the local reports array
          this.reports = this.reports.filter(report => report.id !== reportId);

          // Optionally, you can refetch the reports (e.g., if needed to ensure fresh data)
          // this.fetchReports(); 
        } catch (error) {
          console.error('Error deleting report:', error);
          alert('An error occurred while deleting the report.');
        }
      }
    },
  },
  mounted() {
    this.fetchReports(); // Fetch reports when the component is mounted
  },
};
</script>

<style scoped>
.report-container {
  max-width: 900px;
  margin: 0 auto;
  padding: 20px;
}

.report-container h2 {
  font-size: 24px;
  font-weight: bold;
}

.report-container p {
  font-size: 14px;
  color: #555;
  margin-bottom: 10px;
}

.report-table {
  width: 100%;
  border-collapse: collapse;
}

.report-table th,
.report-table td {
  padding: 10px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.report-table th {
  background-color: #003366;
  color: white;
}

.actions {
  display: flex;
  gap: 10px; /* Add space between the buttons */
}

.btn {
  padding: 5px 10px;
  font-size: 12px;
  border: none;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 5px;
  white-space: nowrap; /* Prevent text from wrapping */
}

.btn-view {
  background-color: #4CAF50;
  color: white;
}

.btn-view:hover {
  background-color: #45a049;
}

.btn-delete {
  background-color: #f44336;
  color: white;
}

.btn-delete:hover {
  background-color: #e53935;
}

i {
  font-size: 14px;
}
</style>
