<template>
    <div class="container mt-4">
      <div class="card">
        <div class="card-header bg-primary text-white">
          <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Employee Information</h4>
            <button class="btn btn-light" @click="openEditModal">Edit</button>
          </div>
        </div>
        <div class="card-body">
          <div v-if="employee">
            <div class="row mb-3">
              <div class="col-md-4">
                <p class="fw-bold">ID:</p>
                <p>{{ employee.id }}</p>
              </div>
              <div class="col-md-4">
                <p class="fw-bold">Last Name:</p>
                <p>{{ employee.lastName }}</p>
              </div>
              <div class="col-md-4">
                <p class="fw-bold">First Name:</p>
                <p>{{ employee.firstName }}</p>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-4">
                <p class="fw-bold">Middle Name:</p>
                <p>{{ employee.middleName }}</p>
              </div>
              <div class="col-md-4">
                <p class="fw-bold">Sex:</p>
                <p>{{ employee.sex }}</p>
              </div>
              <div class="col-md-4">
                <p class="fw-bold">Civil Status:</p>
                <p>{{ employee.civilStatus }}</p>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-4">
                <p class="fw-bold">Date of Birth:</p>
                <p>{{ employee.dateOfBirth }}</p>
              </div>
              <div class="col-md-4">
                <p class="fw-bold">Religion:</p>
                <p>{{ employee.religion }}</p>
              </div>
              <div class="col-md-4">
                <p class="fw-bold">Email Address:</p>
                <p>{{ employee.emailAddress }}</p>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-4">
                <p class="fw-bold">Phone Number:</p>
                <p>{{ employee.phoneNumber }}</p>
              </div>
              <div class="col-md-4">
                <p class="fw-bold">GSIS ID:</p>
                <p>{{ employee.gsisId }}</p>
              </div>
              <div class="col-md-4">
                <p class="fw-bold">PAG-IBIG ID:</p>
                <p>{{ employee.pagibigId }}</p>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-4">
                <p class="fw-bold">PhilHealth ID:</p>
                <p>{{ employee.philhealthId }}</p>
              </div>
              <div class="col-md-4">
                <p class="fw-bold">SSS No:</p>
                <p>{{ employee.sssNo }}</p>
              </div>
              <div class="col-md-4">
                <p class="fw-bold">Agency Employment No:</p>
                <p>{{ employee.agencyEmploymentNo }}</p>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-4">
                <p class="fw-bold">Tax ID:</p>
                <p>{{ employee.taxId }}</p>
              </div>
              <div class="col-md-4">
                <p class="fw-bold">Academic Rank:</p>
                <p>{{ employee.academicRank }}</p>
              </div>
              <div class="col-md-4">
                <p class="fw-bold">University Position:</p>
                <p>{{ employee.universityPosition }}</p>
              </div>
            </div>
            <div class="text-center">
              <img :src="employee.profileImage" alt="Profile Image" class="img-fluid rounded-circle border" style="max-width: 150px; height: auto;" />
            </div>
          </div>
          <div v-else>
            <p>Loading...</p>
          </div>
        </div>
      </div>
  
      <!-- Edit Modal -->
      <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form @submit.prevent="updateEmployee">
                <div class="mb-3">
                  <label for="lastName" class="form-label">Last Name</label>
                  <input type="text" class="form-control" id="lastName" v-model="editEmployee.lastName" required>
                </div>
                <div class="mb-3">
                  <label for="firstName" class="form-label">First Name</label>
                  <input type="text" class="form-control" id="firstName" v-model="editEmployee.firstName" required>
                </div>
                <div class="mb-3">
                  <label for="middleName" class="form-label">Middle Name</label>
                  <input type="text" class="form-control" id="middleName" v-model="editEmployee.middleName">
                </div>
                <div class="mb-3">
                  <label for="sex" class="form-label">Sex</label>
                  <select class="form-select" id="sex" v-model="editEmployee.sex" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="civilStatus" class="form-label">Civil Status</label>
                  <input type="text" class="form-control" id="civilStatus" v-model="editEmployee.civilStatus" required>
                </div>
                <div class="mb-3">
                  <label for="dateOfBirth" class="form-label">Date of Birth</label>
                  <input type="date" class="form-control" id="dateOfBirth" v-model="editEmployee.dateOfBirth" required>
                </div>
                <div class="mb-3">
                  <label for="religion" class="form-label">Religion</label>
                  <input type="text" class="form-control" id="religion" v-model="editEmployee.religion">
                </div>
                <div class="mb-3">
                  <label for="emailAddress" class="form-label">Email Address</label>
                  <input type="email" class="form-control" id="emailAddress" v-model="editEmployee.emailAddress" required>
                </div>
                <div class="mb-3">
                  <label for="phoneNumber" class="form-label">Phone Number</label>
                  <input type="text" class="form-control" id="phoneNumber" v-model="editEmployee.phoneNumber" required>
                </div>
                <div class="mb-3">
                  <label for="gsisId" class="form-label">GSIS ID</label>
                  <input type="text" class="form-control" id="gsisId" v-model="editEmployee.gsisId">
                </div>
                <div class="mb-3">
                  <label for="pagibigId" class="form-label">PAG-IBIG ID</label>
                  <input type="text" class="form-control" id="pagibigId" v-model="editEmployee.pagibigId">
                </div>
                <div class="mb-3">
                  <label for="philhealthId" class="form-label">PhilHealth ID</label>
                  <input type="text" class="form-control" id="philhealthId" v-model="editEmployee.philhealthId">
                </div>
                <div class="mb-3">
                  <label for="sssNo" class="form-label">SSS No</label>
                  <input type="text" class="form-control" id="sssNo" v-model="editEmployee.sssNo">
                </div>
                <div class="mb-3">
                  <label for="agencyEmploymentNo" class="form-label">Agency Employment No</label>
                  <input type="text" class="form-control" id="agencyEmploymentNo" v-model="editEmployee.agencyEmploymentNo">
                </div>
                <div class="mb-3">
                  <label for="taxId" class="form-label">Tax ID</label>
                  <input type="text" class="form-control" id="taxId" v-model="editEmployee.taxId">
                </div>
                <div class="mb-3">
                  <label for="academicRank" class="form-label">Academic Rank</label>
                  <input type="text" class="form-control" id="academicRank" v-model="editEmployee.academicRank">
                </div>
                <div class="mb-3">
                  <label for="universityPosition" class="form-label">University Position</label>
                  <input type="text" class="form-control" id="universityPosition" v-model="editEmployee.universityPosition">
                </div>
                <div class="mb-3">
                  <label for="profileImage" class="form-label">Profile Image URL</label>
                  <input type="text" class="form-control" id="profileImage" v-model="editEmployee.profileImage">
                </div>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, onMounted } from 'vue';
  import { useRoute } from 'vue-router';
  import axios from 'axios';
  import { Modal } from 'bootstrap';
  
  export default {
    setup() {
      const route = useRoute();
      const employee = ref(null);
      const editEmployee = ref({});
  
      const fetchEmployee = async id => {
        try {
          const response = await axios.get(`/api/admin/employees/${id}`);
          employee.value = response.data.employee;
          editEmployee.value = { ...response.data.employee }; // Initialize edit form with employee data
        } catch (error) {
          console.error('Error fetching employee information:', error);
        }
      };
  
      const updateEmployee = async () => {
        try {
          const response = await axios.put(`/api/admin/employees/${editEmployee.value.id}`, editEmployee.value);
          employee.value = response.data.employee;
          closeEditModal();
        } catch (error) {
          console.error('Error updating employee information:', error);
        }
      };
  
      const openEditModal = () => {
        const modal = new Modal(document.getElementById('editEmployeeModal'));
        modal.show();
      };
  
      const closeEditModal = () => {
        const modal = new Modal(document.getElementById('editEmployeeModal'));
        modal.hide();
      };
  
      onMounted(() => {
        const id = route.params.id;
        fetchEmployee(id);
      });
  
      return {
        employee,
        editEmployee,
        openEditModal,
        closeEditModal,
        updateEmployee,
      };
    },
  };
  </script>
  
  <style scoped>
  .card {
    border-radius: 0.5rem;
  }
  
  .card-header {
    border-bottom: 1px solid #dee2e6;
  }
  
  .img-fluid {
    max-width: 150px;
    height: auto;
    border-radius: 50%;
    border: 2px solid #dee2e6;
  }
  
  .fw-bold {
    font-weight: 700;
  }
  
  .modal-content {
    border-radius: 0.5rem;
  }
  </style>
  >