<template>
  <h2>Reports | Travel Orders</h2>
  <div class="container">
    <div class="row">
      <!-- Filter Section -->
      <div class="col-lg-12">
        <div class="card bg-light text-dark">
          <div class="card-body">
            <h5>Generate Travel Order Report</h5>
            <form @submit.prevent="generateReport">
              <!-- Date Filter -->
              <div class="form-row align-items-center">
                <label class="col-md-3 col-form-label">Select Upload Date Range:</label>
                <div class="col-md-4 mb-3">
                  <input type="date" v-model="startDate" class="form-control" required />
                  <small>From</small>
                </div>
                <div class="col-md-4 mb-3">
                  <input type="date" v-model="endDate" class="form-control" required />
                  <small>To</small>
                </div>
              </div>

              <!-- Employee Filter -->
              <div class="form-row align-items-center">
                <label class="col-md-3 col-form-label">Select Employee:</label>
                <div class="col-md-4 mb-3">
                  <select v-model="employee" class="form-control">
                    <option value="">-- ALL --</option>
                    <option v-for="emp in employees" :key="emp.id" :value="emp.id">
                      {{ emp.name }}
                    </option>
                  </select>
                </div>
              </div>

              <!-- Rows Filter -->
              <div class="form-row align-items-center">
                <label class="col-md-3 col-form-label">Rows per Page:</label>
                <div class="col-md-2 mb-3">
                  <select v-model="rowsPerPage" class="form-control">
                    <option v-for="rows in [10, 25, 50, 100]" :key="rows" :value="rows">
                      {{ rows }}
                    </option>
                  </select>
                </div>
              </div>

              <!-- Generate Button -->
              <div class="form-row">
                <div class="col-md-2 mb-3">
                  <button type="submit" class="btn btn-primary">Generate Report</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Loading Spinner -->
      <div v-if="isLoading" class="col-lg-12 text-center mt-3">
        <div class="spinner-border text-primary" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>

      <!-- Error Message -->
      <div v-if="errorMessage" class="col-lg-12 mt-3">
        <div class="alert alert-danger" role="alert">
          {{ errorMessage }}
        </div>
      </div>

      <!-- Document Table -->
      <div v-if="documents.length > 0" class="col-lg-12 mt-3">
        <h5>Travel Order Documents from {{ startDate }} to {{ endDate }}</h5>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Destination</th>
              <th>Subject</th>
              <th>Inclusive Date</th>
              <th>Beneficiaries</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="document in paginatedDocuments" :key="document.id">
              <td>{{ document.destination }}</td>
              <td>{{ document.subject }}</td>
              <td>{{ document.from_date }} &ndash; {{ document.to_date }}</td>
              <td>{{ document.employee_names }}</td>
              <td>
                <a :href="'/storage/' + document.file_path" class="btn btn-info" target="_blank">View</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      startDate: '',
      endDate: '',
      isLoading: false,
      errorMessage: '',
      reportGenerated: false,
      reportLink: '',
      documents: [], // Stores the list of documents
      employees: [], // Stores the list of employees
      employee: '', // Selected employee for filtering
      rowsPerPage: 10, // Rows per page for pagination
    };
  },
  computed: {
    paginatedDocuments() {
      return this.documents.slice(0, this.rowsPerPage);
    },
  },
  methods: {
    // Generate report and fetch documents for the date range
    async generateReport() {
      // Validate if dates are selected
      if (!this.startDate || !this.endDate) {
        this.errorMessage = 'Please select both a start and end date.';
        return;
      }

      // Validate if start date is before end date
      if (this.startDate > this.endDate) {
        this.errorMessage = 'The start date cannot be later than the end date.';
        return;
      }

      this.isLoading = true;
      this.errorMessage = '';
      this.reportGenerated = false;
      this.documents = []; // Clear the documents list before fetching

      try {
        // First, generate the report
        const reportResponse = await axios.post('/api/admin/generate-report', {
          upload_from_date: this.startDate,
          upload_to_date: this.endDate,
          document_type: 'Travel Order', // Specify document type
        });

        this.reportLink = reportResponse.data.file_path;
        this.reportGenerated = true;

        // Then, fetch the documents for the same date range
        const documentsResponse = await axios.get('/api/admin/documentsbydaterange', {
          params: {
            upload_from_date: this.startDate,
            upload_to_date: this.endDate,
            document_type: 'Travel Order', // Filter documents by type
            employee: this.employee, // Filter documents by employee
          },
        });

        this.documents = documentsResponse.data.documents; // Set the documents data
      } catch (error) {
        // Handle errors from the backend
        this.errorMessage = error.response?.data?.message || 'An error occurred while generating the report.';
      } finally {
        this.isLoading = false;
      }
    },
  },
  async mounted() {
    // Fetch employees for the dropdown when the component is mounted
    try {
      const response = await axios.get('/api/admin/employees');
      this.employees = response.data.employees;
    } catch (error) {
      console.error('Error fetching employees:', error);
    }
  },
};
</script>

<style scoped>
.container {
  max-width: 1500px;
  margin: 0 auto;
  padding: 20px;
}

.card {
  border-radius: 10px;
  background-color: transparent;
}

.card-body {
  padding: 25px;
}

.form-row {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
}

label {
  font-weight: bold;
  margin-right: 10px;
  color: rgb(21, 17, 17);
}

table th {
  text-align: center;
  vertical-align: middle;
  background-color: navy; 
  color: white;  
  height: 40px;  
  white-space: nowrap;
}
table td {
  text-align: center;
  vertical-align: middle;
}

thead {
  background-color: #343a40;
  color: rgb(21, 17, 17);
}

button {
  cursor: pointer;
}

button:hover {
  background-color: #007bff;
  color: white;
}

.alert-danger {
  margin-top: 20px;
}
</style>
