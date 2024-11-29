<template>
  <div v-if="employee" class="card profile-card">
    <div class="card-body profile-card-body">
      <!-- Profile Header -->
      <div class="profile-header d-flex align-items-center mb-4">
        <div class="profile-image me-4">
          <img :src="profileImageUrl" alt="Profile Image" class="img-thumbnail rounded-circle">
          <input type="file" @change="onFileChange" :disabled="!isEditing" class="mt-2">
        </div>
        <div class="profile-header-info">
          <h3 class="mb-1">{{ employee.firstName }} {{ employee.lastName }}</h3>
          <p class="text-muted">{{ employee.universityPosition }}</p>
          <p v-if="employee.emailAddress" class="text-muted"><i class="fas fa-envelope"></i> {{ employee.emailAddress }}</p>
        </div>
      </div>

      <!-- Personal Information Section -->
      <div class="personal-info">
        <h2 class="section-title">Personal Information</h2>
        <div class="row">
          <div class="col-4">
            <label for="lastName">Last Name</label>
            <input type="text" id="lastName" class="form-control" v-model="employee.lastName" :disabled="!isEditing">
          </div>
          <div class="col-4">
            <label for="firstName">First Name</label>
            <input type="text" id="firstName" class="form-control" v-model="employee.firstName" :disabled="!isEditing">
          </div>
          <div class="col-4">
            <label for="middleName">Middle Name</label>
            <input type="text" id="middleName" class="form-control" v-model="employee.middleName" :disabled="!isEditing">
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-4">
            <label for="sex">Sex</label>
            <select id="sex" class="form-select" v-model="employee.sex" :disabled="!isEditing">
              <option value="Male">Male</option>
              <option value="Female">Female</option>
              <option value="Other">Other</option>
            </select>
          </div>
          <div class="col-4">
            <label for="civilStatus">Civil Status</label>
            <select id="civilStatus" class="form-select" v-model="employee.civilStatus" :disabled="!isEditing">
              <option value="Single">Single</option>
              <option value="Married">Married</option>
              <option value="Widowed">Widowed</option>
              <option value="Separated">Separated</option>
            </select>
          </div>
          <div class="col-4">
            <label for="dateOfBirth">Date of Birth</label>
            <input type="date" id="dateOfBirth" class="form-control" v-model="employee.dateOfBirth" :disabled="!isEditing">
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-4">
            <label for="religion">Religion</label>
            <input type="text" id="religion" class="form-control" v-model="employee.religion" :disabled="!isEditing">
          </div>
          <div class="col-4">
            <label for="emailAddress">Email Address</label>
            <input type="email" id="emailAddress" class="form-control" v-model="employee.emailAddress" :disabled="!isEditing">
          </div>
          <div class="col-4">
            <label for="phoneNumber">Phone Number</label>
            <input type="text" id="phoneNumber" class="form-control" v-model="employee.phoneNumber" :disabled="!isEditing">
          </div>
        </div>
      </div>

      <!-- Government IDs -->
      <div class="government-ids mt-4">
        <h2 class="section-title">Government IDs</h2>
        <div class="row">
          <div class="col-4">
            <label for="gsisId">GSIS ID</label>
            <input type="text" id="gsisId" class="form-control" v-model="employee.gsisId" :disabled="!isEditing">
          </div>
          <div class="col-4">
            <label for="pagibigId">Pag-IBIG ID</label>
            <input type="text" id="pagibigId" class="form-control" v-model="employee.pagibigId" :disabled="!isEditing">
          </div>
          <div class="col-4">
            <label for="philhealthId">PhilHealth ID</label>
            <input type="text" id="philhealthId" class="form-control" v-model="employee.philhealthId" :disabled="!isEditing">
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-4">
            <label for="sssNo">SSS No</label>
            <input type="text" id="sssNo" class="form-control" v-model="employee.sssNo" :disabled="!isEditing">
          </div>
          <div class="col-4">
            <label for="agencyEmploymentNo">Agency Employment No</label>
            <input type="text" id="agencyEmploymentNo" class="form-control" v-model="employee.agencyEmploymentNo" :disabled="!isEditing">
          </div>
          <div class="col-4">
            <label for="taxId">Tax ID</label>
            <input type="text" id="taxId" class="form-control" v-model="employee.taxId" :disabled="!isEditing">
          </div>
        </div>
      </div>

      <!-- Address Section -->
      <div class="address-section mt-4">
        <h2 class="section-title">Address Details</h2>
        <div class="row">
          <div class="col-6">
            <h5>Permanent Address</h5>
            <input type="text" v-model="employee.permanent_street" placeholder="Street" class="form-control mb-2" :disabled="!isEditing">
            <input type="text" v-model="employee.permanent_barangay" placeholder="Barangay" class="form-control mb-2" :disabled="!isEditing">
            <input type="text" v-model="employee.permanent_city" placeholder="City" class="form-control mb-2" :disabled="!isEditing">
            <input type="text" v-model="employee.permanent_province" placeholder="Province" class="form-control mb-2" :disabled="!isEditing">
            <input type="text" v-model="employee.permanent_country" placeholder="Country" class="form-control mb-2" :disabled="!isEditing">
            <input type="text" v-model="employee.permanent_zipcode" placeholder="Zip Code" class="form-control mb-2" :disabled="!isEditing">
          </div>
          <div class="col-6">
            <h5>Residential Address</h5>
            <input type="text" v-model="employee.residential_street" placeholder="Street" class="form-control mb-2" :disabled="!isEditing">
            <input type="text" v-model="employee.residential_barangay" placeholder="Barangay" class="form-control mb-2" :disabled="!isEditing">
            <input type="text" v-model="employee.residential_city" placeholder="City" class="form-control mb-2" :disabled="!isEditing">
            <input type="text" v-model="employee.residential_province" placeholder="Province" class="form-control mb-2" :disabled="!isEditing">
            <input type="text" v-model="employee.residential_country" placeholder="Country" class="form-control mb-2" :disabled="!isEditing">
            <input type="text" v-model="employee.residential_zipcode" placeholder="Zip Code" class="form-control mb-2" :disabled="!isEditing">
          </div>
        </div>
      </div>

      <!-- Academic and Employment Section -->
      <div class="academic-employment mt-4">
        <h2 class="section-title">Academic and Employment</h2>
        <div class="row">
          <div class="row">
          <div class="col-4">
            <label for="academicRank">Academic Rank</label>
            <input type="text" id="academicRank" class="form-control" v-model="employee.academicRank" :disabled="!isEditing">
          </div>
          <div class="col-4">
            <label for="universityPosition">University Position</label>
            <input type="text" id="universityPosition" class="form-control" v-model="employee.universityPosition" :disabled="!isEditing">
          </div>
          <div class="col-4">
            <label for="department">Department</label>
            <input type="text" id="department" class="form-control" v-model="employee.department" :disabled="!isEditing">
          </div>
        </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="d-flex justify-content-end mt-4">
        <button v-if="!isEditing" @click="editProfile" class="btn btn-primary">Edit</button>
        <button v-if="isEditing" @click="saveProfile" class="btn btn-success">Save</button>
        <button v-if="isEditing" @click="cancelEdit" class="btn btn-danger ms-2">Cancel</button>
      </div>
    </div>
  </div>
</template>


<script>
import axios from "axios";

export default {
  data() {
    return {
      employee: {},
      isEditing: false,
      profileImage: null,
    };
  },
  computed: {
    profileImageUrl() {
      return this.employee.profileImage
        ? `/storage/${this.employee.profileImage}`
        : "/placeholder-profile.png";
    },
  },
  methods: {
    fetchEmployeeProfile() {
      axios
        .get("/api/employee-profile")
        .then((response) => {
          this.employee = response.data;
        })
        .catch((error) => {
          console.error("Error fetching employee profile:", error);
        });
    },
    editProfile() {
      this.isEditing = true;
    },
    cancelEdit() {
      this.isEditing = false;
      this.fetchEmployeeProfile(); // Reset to fetched data
    },
    saveProfile() {
      const formData = new FormData();
      for (const key in this.employee) {
        if (this.employee.hasOwnProperty(key)) {
          formData.append(key, this.employee[key]);
        }
      }
      if (this.profileImage) {
        formData.append("profileImage", this.profileImage);
      }

      axios
        .post("/api/employee-profile", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then(() => {
          this.isEditing = false;
          this.fetchEmployeeProfile(); // Refresh profile data
        })
        .catch((error) => {
          console.error("Error saving profile:", error);
        });
    },
    onFileChange(event) {
      this.profileImage = event.target.files[0];
    },
  },
  mounted() {
    this.fetchEmployeeProfile();
  },
};
</script>

<style scoped>
.profile-card {
  max-width: 1100px;
  margin: auto;
}
.profile-header {
  border-bottom: 1px solid #ddd;
  padding-bottom: 15px;
}
.section-title {
  margin-top: 20px;
  font-size: 1.5rem;
  font-weight: bold;
}
.profile-image img {
  max-width: 120px;
  height: 120px;
  object-fit: cover;
}
</style>
