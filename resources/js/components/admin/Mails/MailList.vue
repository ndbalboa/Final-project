<template>
  <div class="mail-list-container">
    <h2>Mail List</h2>
    <!-- Filter/Search Bar -->
    <div class="filter-bar">
      <!-- Row 1: Date -->
      <div class="filter-row">
        <div class="filter-item">
          <label for="startDate">Date</label>
          <span>from</span>
          <input type="date" v-model="filters.startDate" />
          <span>to</span>
          <input type="date" v-model="filters.endDate" />
        </div>
      </div>

      <!-- Row 2: Priority -->
      <div class="filter-row">
        <div class="filter-item">
          <label for="priority">Priority</label>
          <div class="priority-options">
            <label><input type="checkbox" v-model="filters.priority" value="Very High" /> Very High</label>
            <label><input type="checkbox" v-model="filters.priority" value="High" /> High</label>
            <label><input type="checkbox" v-model="filters.priority" value="Normal" /> Normal</label>
            <label><input type="checkbox" v-model="filters.priority" value="Low" /> Low</label>
            <label><input type="checkbox" v-model="filters.priority" value="Very Low" /> Very Low</label>
          </div>
        </div>
      </div>

      <!-- Row 3: Status -->
      <div class="filter-row">
        <div class="filter-item">
          <label>Status</label>
          <div class="status-options">
            <label><input type="checkbox" v-model="filters.status" value="Undelivered" /> Undelivered</label>
            <label><input type="checkbox" v-model="filters.status" value="Delivered" /> Delivered</label>
          </div>
        </div>
      </div>

      <!-- Row 4: Rows -->
      <div class="filter-row">
        <div class="filter-item rows-dropdown">
          <label for="rows">Rows</label>
          <select v-model="filters.rows" @change="applyFilters">
            <option v-for="option in rowOptions" :key="option" :value="option">{{ option }}</option>
          </select>
        </div>
      </div>

      <!-- Row 5: Show Results Button -->
      <div class="filter-row">
        <button @click="applyFilters" class="show-results-btn">Show Results</button>
      </div>
    </div>

    <!-- Data Table for Mail List -->
    <table class="mail-list-table">
      <thead>
        <tr>
          <th @click="sortTable('to')">To</th>
          <th @click="sortTable('from')">From</th>
          <th @click="sortTable('priority')">Priority</th>
          <th @click="sortTable('status')">Status</th>
          <th @click="sortTable('date_received')">Date Received</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="mails.data.length" v-for="(mail, index) in filteredMails" :key="mail.id">
          <td>{{ mail.to }}</td>
          <td>{{ mail.from }}</td>
          <td>{{ mail.priority }}</td>
          <td>{{ mail.status }}</td>
          <td>{{ formatDate(mail.date_received) }}</td>
          <td>
            <button @click="openEditModal(mail)" class="icon-btn" title="Edit">
              <i class="fas fa-pencil-alt"></i>
            </button>
          </td>
        </tr>
        <tr v-else>
          <td colspan="6">No results found.</td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination-info">
      <span>Results {{ mails.from || 0 }} - {{ mails.to || 0 }} out of {{ mails.total || 0 }}</span>
    </div>

    <!-- Edit Modal -->
    <div v-if="isModalOpen" class="modal fade show" tabindex="-1" aria-labelledby="editMailModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editMailModalLabel">Edit Mail</h5>
            <button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveEdit">
              <div class="mb-3">
                <label for="to" class="form-label">To:</label>
                <input type="text" class="form-control" id="to" v-model="currentMail.to" required />
              </div>
              <div class="mb-3">
                <label for="from" class="form-label">From:</label>
                <input type="text" class="form-control" id="from" v-model="currentMail.from" required />
              </div>
              <div class="mb-3">
                <label for="priority" class="form-label">Priority:</label>
                <input type="text" class="form-control" id="priority" v-model="currentMail.priority" required />
              </div>
              <div class="mb-3">
                <label for="status" class="form-label">Status:</label>
                <select class="form-select" v-model="currentMail.status" required>
                  <option value="Delivered">Delivered</option>
                  <option value="Undelivered">Undelivered</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="date_received" class="form-label">Date Received:</label>
                <input type="date" class="form-control" id="date_received" v-model="currentMail.date_received" required />
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" @click="saveEdit">Save</button>
            <button type="button" class="btn btn-danger" @click="deleteMail(currentMail.id)">Delete</button>
            <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      filters: {
        startDate: "",
        endDate: "",
        priority: [],
        status: [],
        rows: 10,
      },
      rowOptions: [5, 10, 15, 20],
      mails: {
        data: [],
        total: 0,
        from: 0,
        to: 0,
      },
      isModalOpen: false,
      currentMail: null,
      filteredMails: [],
      sortColumn: "",
      sortOrder: "asc",
    };
  },
  computed: {
    filteredMails() {
      return this.mails.data.filter(mail => {
        // Filter by date range
        const startDate = this.filters.startDate ? new Date(this.filters.startDate) : null;
        const endDate = this.filters.endDate ? new Date(this.filters.endDate) : null;
        const dateReceived = new Date(mail.date_received);

        const isDateInRange = (!startDate || dateReceived >= startDate) && (!endDate || dateReceived <= endDate);

        // Filter by priority
        const isPriorityMatch = this.filters.priority.length ? this.filters.priority.includes(mail.priority) : true;

        // Filter by status
        const isStatusMatch = this.filters.status.length ? this.filters.status.includes(mail.status) : true;

        return isDateInRange && isPriorityMatch && isStatusMatch;
      });
    },
  },
  methods: {
    async fetchMails() {
      try {
        const response = await axios.get("/api/admin/getmails", { params: this.filters });
        this.mails = response.data;
      } catch (error) {
        console.error("Error fetching mails:", error);
      }
    },
    applyFilters() {
      this.fetchMails();
    },
    sortTable(column) {
      if (this.sortColumn === column) {
        this.sortOrder = this.sortOrder === "asc" ? "desc" : "asc";
      } else {
        this.sortColumn = column;
        this.sortOrder = "asc";
      }
      this.filteredMails.sort((a, b) => {
        if (a[column] < b[column]) return this.sortOrder === "asc" ? -1 : 1;
        if (a[column] > b[column]) return this.sortOrder === "asc" ? 1 : -1;
        return 0;
      });
    },
    openEditModal(mail) {
      this.currentMail = { ...mail };
      this.isModalOpen = true;
    },
    closeModal() {
      this.isModalOpen = false;
      this.currentMail = null;
    },
    async saveEdit() {
      try {
        await axios.put(`/api/admin/mails/${this.currentMail.id}`, this.currentMail);
        this.fetchMails();
        this.closeModal();
      } catch (error) {
        console.error("Error saving mail:", error);
      }
    },
    async deleteMail(id) {
      try {
        await axios.delete(`/api/admin/mails/${id}`);
        this.fetchMails();
        this.closeModal();
      } catch (error) {
        console.error("Error deleting mail:", error);
      }
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString();
    },
  },
  mounted() {
    this.fetchMails();
  },
};
</script>

<style scoped>
/* Global Styles */
body {
  font-family: 'Roboto', sans-serif;
  background-color: #f8f9fc;
  margin: 0;
  padding: 0;
}

/* Container */
.mail-list-container {
  width: 90%;
  margin: 0 auto;
  padding: 20px;
  background-color: #ffffff;
  box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
}

/* Header */
h2 {
  font-size: 24px;
  color: #003366;
  margin-bottom: 20px;
  text-align: center;
}

/* Filter Bar */
.filter-bar {
  display: flex;
  flex-direction: column;
  gap: 15px;
  padding: 20px;
  background-color: #f8f9fc;
  border-radius: 10px;
  margin-bottom: 20px;
  box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
  position: relative; /* Set position to relative so button can be positioned absolutely */
}

/* Filter Row */
.filter-row {
  display: flex;
  flex-direction: column;
  gap: 10px; /* Add space between label and input */
}

/* Filter Bar Span Styles */
.filter-row span {
  font-size: 14px;
  color: #003366;
  margin: 0 5px;
  font-weight: bold;
  text-transform: lowercase;
}

.show-results-btn {
  background-color: #007bff;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
  font-weight: bold;
  transition: all 0.3s ease;
  position: absolute; /* Position button absolutely */
  bottom: 15px; /* Place it 15px from the bottom */
  right: 15px; /* Place it 15px from the right */
}

.show-results-btn:hover {
  background-color: #0056b3;
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

.filter-item label {
  font-weight: bold;
  color: #003366;
  display: block;
  margin-bottom: 5px;
}

input[type="date"],
select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  background-color: #ffffff;
  font-size: 14px;
}

input[type="checkbox"] {
  margin-right: 8px;
}

/* Table */
.mail-list-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  font-size: 14px;
}

.mail-list-table th,
.mail-list-table td {
  padding: 12px 15px;
  text-align: left;
}

.mail-list-table th {
  background-color: #003366;
  color: #fff;
  cursor: pointer;
  position: sticky;
  top: 0;
}

.mail-list-table tr:nth-child(even) {
  background-color: #f8f9fc;
}

.mail-list-table tr:hover {
  background-color: #e6f7ff;
}

/* Pagination */
.pagination-info {
  margin-top: 20px;
  text-align: center;
  color: #555;
  font-size: 14px;
}

/* Modal */
.modal {
  display: flex;
  align-items: center;
  justify-content: center;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1050;
}

.modal-dialog {
  background-color: #ffffff;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
  width: 40%;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #e5e5e5;
  padding-bottom: 10px;
}

.modal-title {
  font-size: 18px;
  font-weight: bold;
}

.btn-close {
  background: none;
  border: none;
  font-size: 20px;
  color: #333;
  cursor: pointer;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  padding-top: 10px;
}

.btn {
  padding: 8px 15px;
  font-size: 14px;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-primary {
  background-color: #007bff;
  color: #fff;
  border: none;
}

.btn-primary:hover {
  background-color: #0056b3;
}

.btn-danger {
  background-color: #dc3545;
  color: #fff;
  border: none;
}

.btn-danger:hover {
  background-color: #a71d2a;
}

.btn-secondary {
  background-color: #6c757d;
  color: #fff;
  border: none;
}

.btn-secondary:hover {
  background-color: #5a6268;
}

/* Action Button */
.icon-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border: none;
  cursor: pointer;
  color: #003366;
  background-color: #f8f9fc;
  border-radius: 50%;
  transition: all 0.3s ease;
}

.icon-btn:hover {
  background-color: #e6f7ff;
  color: #0056b3;
}

/* Hover Effects */
.mail-list-table th:hover,
.icon-btn:hover {
  box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
}

/* Filter Items Alignment Fix */
.filter-bar {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 15px; /* Gap between filter rows */
  padding: 20px;
  background-color: #f8f9fc;
  border-radius: 10px;
  margin-bottom: 20px;
  box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
  position: relative;
}

.filter-item {
  display: flex;
  flex-direction: column;
  gap: 10px; /* Add space between label and input */
}

.filter-item label {
  font-weight: bold;
  color: #003366;
  display: block;
  margin-bottom: 5px;
}

input[type="date"],
select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  background-color: #ffffff;
  font-size: 14px;
}

input[type="checkbox"] {
  margin-right: 8px;
}

.show-results-btn {
  background-color: #0056b3;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
  font-weight: bold;
  transition: all 0.3s ease;
  position: absolute; /* Position button absolutely */
  bottom: 15px; /* Place it 15px from the bottom */
  right: 15px; /* Place it 15px from the right */
}

.show-results-btn:hover {
  background-color: #0056b3;
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}


</style>
