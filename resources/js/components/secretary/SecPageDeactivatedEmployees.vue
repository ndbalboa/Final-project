<template>
  <div class="container">
    <h1 class="mt-3">Deactivated Employees</h1>

    <div class="d-flex justify-content-end mb-3">
      <div class="input-group" style="width: 300px;">
        <input
          type="text"
          v-model="searchTerm"
          placeholder="Search by name"
          class="form-control"
          @input="filterEmployees"
        />
        <div class="input-group-append">
          <span class="input-group-text">
            <i class="fa fa-search"></i>
          </span>
        </div>
      </div>
    </div>

    <nav aria-label="Page navigation" class="d-flex justify-content-end">
      <ul class="pagination">
        <li class="page-item" :class="{ disabled: currentPage === 1 }">
          <a class="page-link" @click.prevent="prevPage">Previous</a>
        </li>
        <li
          class="page-item"
          v-for="page in totalPages"
          :key="page"
          :class="{ active: currentPage === page }"
        >
          <a class="page-link" @click.prevent="fetchDeactivatedEmployees(page)">{{ page }}</a>
        </li>
        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
          <a class="page-link" @click.prevent="nextPage">Next</a>
        </li>
      </ul>
    </nav>

    <table v-if="filteredEmployees.length > 0" class="table table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>Employee ID</th>
          <th>Last Name</th>
          <th>First Name</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="employee in paginatedEmployees" :key="employee.id">
          <td>{{ employee.employee_id }}</td>
          <td>{{ employee.lastName }}</td>
          <td>{{ employee.firstName }}</td>
          <td>deactivated</td>
          <td>
            <button
              @click="activateUser(employee.id)"
              class="btn btn-success"
            >Activate</button>
          </td>
        </tr>
      </tbody>
    </table>

    <p v-else>No deactivated employees found.</p>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      employees: [],
      searchTerm: '',
      currentPage: 1,
      pageSize: 5, // Number of employees per page
    };
  },
  computed: {
    filteredEmployees() {
      if (this.searchTerm) {
        return this.employees.filter(employee => {
          const fullName = `${employee.firstName} ${employee.lastName}`.toLowerCase();
          return fullName.includes(this.searchTerm.toLowerCase());
        });
      }
      return this.employees;
    },
    totalPages() {
      return Math.ceil(this.filteredEmployees.length / this.pageSize);
    },
    paginatedEmployees() {
      const start = (this.currentPage - 1) * this.pageSize;
      return this.filteredEmployees.slice(start, start + this.pageSize);
    },
  },
  methods: {
    async fetchDeactivatedEmployees() {
      try {
        const response = await axios.get('/api/admin/employees/no-user-or-deleted');
        this.employees = response.data; // Assuming response data contains the required structure
      } catch (error) {
        console.error('Error fetching deactivated employees:', error);
        alert('Failed to fetch deactivated employees.');
      }
    },
    async activateUser(employeeId) {
      try {
        await axios.post(`/api/admin/employees/${employeeId}/restore`);
        alert('Employee information restored successfully.');
        this.fetchDeactivatedEmployees(); // Refresh the list after activation
      } catch (error) {
        console.error('Error activating user account or restoring employee info:', error);
        alert('Failed to restore employee information.');
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    filterEmployees() {
      this.currentPage = 1; // Reset to first page on search
    },
  },
  mounted() {
    this.fetchDeactivatedEmployees();
  },
};
</script>

<style scoped>
.table {
  width: 100%;
  margin-top: 20px;
}
.table th, .table td {
  text-align: left;
}
.btn {
  padding: 5px 10px;
}
.pagination {
  margin-top: 20px;
  display: flex;
  align-items: center;
}
.input-group {
  width: 300px;
}
</style>
