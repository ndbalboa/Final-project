<template>
    <div>
      <!-- Add Button -->
      <button
        type="button"
        class="btn btn-success btn-circle"
        @click="openModal"
        data-bs-toggle="modal"
        data-bs-target="#addDepartmentModal"
      >
        <i class="bi bi-plus"></i> <!-- Bootstrap Icon for Plus -->
      </button>
  
      <!-- Modal for Adding/Editing Department -->
      <div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="addDepartmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addDepartmentModalLabel">Add/Update Office</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form @submit.prevent="submitForm">
                <div class="form-group">
                  <label for="department">Office</label>
                  <input
                    type="text"
                    id="department"
                    v-model="department"
                    class="form-control"
                    placeholder="Enter Office Name"
                    required
                  />
                </div>
                <button type="submit" class="btn btn-primary mt-3">
                  {{ editingDepartment ? "Update Department" : "Add Department" }}
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Alerts -->
      <div v-if="errorMessage" class="alert alert-danger mt-3">
        {{ errorMessage }}
      </div>
      <div v-if="successMessage" class="alert alert-success mt-3">
        {{ successMessage }}
      </div>
  
      <!-- Department Table -->
      <h3 class="mt-5">Offices</h3>
      <table class="table table-striped">
        <thead>
          <tr class="bg-primary text-white">
            <th>Office</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="departmentItem in departments" :key="departmentItem.id">
            <td>{{ departmentItem.department }}</td>
            <td>
              <button
                @click="editDepartment(departmentItem)"
                class="btn btn-warning btn-sm"
              >
                <i class="bi bi-pencil"></i> Edit
              </button>
              <button
                @click="deleteDepartment(departmentItem.id)"
                class="btn btn-danger btn-sm ml-2"
              >
                <i class="bi bi-trash"></i> Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  
  export default {
    data() {
      return {
        department: "",
        departments: [],
        successMessage: "",
        errorMessage: "",
        editingDepartment: null,
      };
    },
    methods: {
      openModal() {
        this.department = "";
        this.editingDepartment = null;
        this.successMessage = "";
        this.errorMessage = "";
      },
      async submitForm() {
        try {
          if (this.editingDepartment) {
            // If editing, send an update request
            await this.updateDepartment();
          } else {
            // If adding new, send a create request
            await this.createDepartment();
          }
  
          this.successMessage = this.editingDepartment
            ? "Department updated successfully!"
            : "Department added successfully!";
  
          this.department = ""; // Reset form field
          this.editingDepartment = null; // Reset the editing state
          this.fetchDepartments(); // Fetch the updated list of departments
  
          // Close the modal after success
          const modal = new bootstrap.Modal(document.getElementById('addDepartmentModal'));
          modal.hide(); // This will close the modal
  
        } catch (error) {
          this.errorMessage = "Failed to process department. Please try again.";
        }
      },
      async createDepartment() {
        await axios.post("/api/admin/store/departments", {
          department: this.department,
        });
      },
      async updateDepartment() {
        await axios.put(`/api/admin/update/departments/${this.editingDepartment.id}`, {
          department: this.department,
        });
      },
      async deleteDepartment(id) {
        if (confirm("Are you sure you want to delete this department?")) {
          try {
            await axios.delete(`/api/admin/delete/departments/${id}`);
            this.successMessage = "Department deleted successfully!";
            this.fetchDepartments();
          } catch (error) {
            this.errorMessage = "Failed to delete department.";
          }
        }
      },
      async fetchDepartments() {
        try {
          const response = await axios.get("/api/admin/departments");
          this.departments = response.data.departments;
        } catch (error) {
          this.errorMessage = "Failed to load departments.";
        }
      },
      editDepartment(departmentItem) {
        this.editingDepartment = departmentItem;
        this.department = departmentItem.department;
  
        const modal = new bootstrap.Modal(document.getElementById("addDepartmentModal"));
        modal.show();
      },
    },
    mounted() {
      this.fetchDepartments();
    },
  };
  </script>
  
  <style scoped>
  .form-group {
    margin-bottom: 1rem;
  }
  .table th {
    background-color: navy;
    color: white;
  }
  .mt-5 {
    margin-top: 3rem;
  }
  .ml-2 {
    margin-left: 0.5rem;
  }
  </style>
  