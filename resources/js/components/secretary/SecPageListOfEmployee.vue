<template>
  <div class="container">
    <h2 class="mt-3">List of Employees</h2>
    <div class="d-flex justify-content-end mb-3">
      <div class="input-group">
        <input
          type="text"
          class="form-control"
          placeholder="Search"
          v-model="searchQuery"
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
          <a
            class="page-link"
            @click.prevent="fetchEmployees(currentPage - 1)"
            href="#"
          >
            Previous
          </a>
        </li>
        <li
          class="page-item"
          v-for="page in totalPages"
          :key="page"
          :class="{ active: currentPage === page }"
        >
          <a
            class="page-link"
            @click.prevent="fetchEmployees(page)"
            href="#"
          >
            {{ page }}
          </a>
        </li>
        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
          <a
            class="page-link"
            @click.prevent="fetchEmployees(currentPage + 1)"
            href="#"
          >
            Next
          </a>
        </li>
      </ul>
    </nav>
    <table class="table table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Last Name</th>
          <th>First Name</th>
          <th>Email</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="employee in filteredEmployees"
          :key="employee.id"
          @click="viewEmployee(employee.id)"
          class="clickable-row"
        >
          <td>{{ employee.id }}</td>
          <td>{{ employee.lastName }}</td>
          <td>{{ employee.firstName }}</td>
          <td>{{ employee.emailAddress }}</td>
          <td>
            {{ employee.user?.status === 'active' ? 'Active' : 'Inactive' }}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from "axios";
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";

export default {
  setup() {
    const employees = ref([]);
    const searchQuery = ref("");
    const currentPage = ref(1);
    const totalPages = ref(1);
    const router = useRouter();

    // Function to fetch employees from the API
    const fetchEmployees = (page = 1) => {
      if (page < 1 || page > totalPages.value) return;

      axios
        .get(`/api/admin/employees/list?page=${page}`)
        .then((response) => {
          const { data, current_page, last_page } = response.data;
          employees.value = data;
          currentPage.value = current_page;
          totalPages.value = last_page;
        })
        .catch((error) => {
          console.error("Error fetching employees:", error);
        });
    };

    // Computed property to filter employees based on search query
    const filteredEmployees = computed(() => {
      const query = searchQuery.value.toLowerCase();
      return employees.value.filter((employee) => {
        const status = employee.user?.status === "active" ? "active" : "inactive";
        return (
          employee.id.toString().includes(query) ||
          employee.lastName.toLowerCase().includes(query) ||
          employee.firstName.toLowerCase().includes(query) ||
          status.includes(query)
        );
      });
    });

    // Navigate to employee details and their associated documents
    const viewEmployee = (id) => {
      router.push({ name: "SecPageEmployeeDetails", params: { id } });
    };

    // Fetch employees on component mount
    onMounted(() => {
      fetchEmployees();
    });

    return {
      employees,
      searchQuery,
      filteredEmployees,
      viewEmployee,
      currentPage,
      totalPages,
      fetchEmployees,
    };
  },
};
</script>

<style scoped>
.clickable-row {
  cursor: pointer;
}
.input-group {
  width: 300px;
}
th {
  background-color: navy; /* Navy blue background */
  color: white;
}
</style>
