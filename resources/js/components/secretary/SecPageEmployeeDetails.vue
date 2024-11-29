<template>
  <!-- Profile Info Container -->
  <div class="profile-info-container">
    <!-- Left side: Personal Info, IDs, and Addresses -->
    <div class="left-side">
      <h2>Account Settings</h2>
      <h4>Personal Information</h4>
      <div class="form-row">
        <div class="form-group">
          <label>Last Name</label>
          <input type="text" v-model="employee.lastName" class="form-control" :disabled="!isEditing">
        </div>
        <div class="form-group">
          <label>First Name</label>
          <input type="text" v-model="employee.firstName" class="form-control" :disabled="!isEditing">
        </div>
        <div class="form-group">
          <label>Middle Name</label>
          <input type="text" v-model="employee.middleName" class="form-control" :disabled="!isEditing">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Sex</label>
          <select v-model="employee.sex" class="form-control" :disabled="!isEditing">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <div class="form-group">
          <label>Civil Status</label>
          <input type="text" v-model="employee.civilStatus" class="form-control" :disabled="!isEditing">
        </div>
        <div class="form-group">
          <label>Date of Birth</label>
          <input type="date" v-model="employee.dateOfBirth" class="form-control" :disabled="!isEditing">
        </div>
        <div class="form-group">
          <label>Religion</label>
          <input type="text" v-model="employee.religion" class="form-control" :disabled="!isEditing">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Email Address</label>
          <input type="email" v-model="employee.emailAddress" class="form-control" :disabled="!isEditing">
        </div>
        <div class="form-group">
          <label>Phone Number</label>
          <input type="tel" v-model="employee.phoneNumber" class="form-control" :disabled="!isEditing">
        </div>
      </div>

      <!-- Government IDs -->
      <h4>Government IDs</h4>
      <div class="form-row">
        <div class="form-group">
          <label>GSIS ID</label>
          <input type="text" v-model="employee.gsisId" class="form-control" :disabled="!isEditing">
        </div>
        <div class="form-group">
          <label>Pag-IBIG ID</label>
          <input type="text" v-model="employee.pagibigId" class="form-control" :disabled="!isEditing">
        </div>
        <div class="form-group">
          <label>PhilHealth ID</label>
          <input type="text" v-model="employee.philhealthId" class="form-control" :disabled="!isEditing">
        </div>
      </div>
      
      <div class="form-row">
        <div class="form-group">
          <label>SSS No</label>
          <input type="text" v-model="employee.sssNo" class="form-control" :disabled="!isEditing">
        </div>
        <div class="form-group">
          <label>Agency Employment No</label>
          <input type="text" v-model="employee.agencyEmploymentNo" class="form-control" :disabled="!isEditing">
        </div>
        <div class="form-group">
          <label>Tax ID</label>
          <input type="text" v-model="employee.taxId" class="form-control" :disabled="!isEditing">
        </div>
      </div>

      <h4>Academic Details</h4>
      <div class="form-row">
        <div class="form-group">
          <label>Academic Rank</label>
          <input type="text" v-model="employee.academicRank" class="form-control" :disabled="!isEditing">
        </div>
        <div class="form-group">
          <label>University Position</label>
          <input type="text" v-model="employee.universityPosition" class="form-control" :disabled="!isEditing">
        </div>
        <div class="form-group">
          <label>Department</label>
          <input type="text" v-model="employee.department" class="form-control" :disabled="!isEditing">
        </div>
      </div>

      <h4>Addresses</h4>
      <div class="form-row">
        <div class="form-group">
          <label>Permanent Street</label>
          <input type="text" v-model="employee.permanent_street" class="form-control" :disabled="!isEditing">
        </div>
        <div class="form-group">
          <label>Permanent Barangay</label>
          <input type="text" v-model="employee.permanent_barangay" class="form-control" :disabled="!isEditing">
        </div>
        <div class="form-group">
          <label>Permanent City</label>
          <input type="text" v-model="employee.permanent_city" class="form-control" :disabled="!isEditing">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Permanent Province</label>
          <input type="text" v-model="employee.permanent_province" class="form-control" :disabled="!isEditing">
        </div>
        <div class="form-group">
          <label>Permanent Country</label>
          <input type="text" v-model="employee.permanent_country" class="form-control" :disabled="!isEditing">
        </div>
        <div class="form-group">
          <label>Permanent Zip Code</label>
          <input type="text" v-model="employee.permanent_zipcode" class="form-control" :disabled="!isEditing">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Residential Street</label>
          <input type="text" v-model="employee.residential_street" class="form-control" :disabled="!isEditing">
        </div>
        <div class="form-group">
          <label>Residential Barangay</label>
          <input type="text" v-model="employee.residential_barangay" class="form-control" :disabled="!isEditing">
        </div>
        <div class="form-group">
          <label>Residential City</label>
          <input type="text" v-model="employee.residential_city" class="form-control" :disabled="!isEditing">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Residential Province</label>
          <input type="text" v-model="employee.residential_province" class="form-control" :disabled="!isEditing">
        </div>
        <div class="form-group">
          <label>Residential Country</label>
          <input type="text" v-model="employee.residential_country" class="form-control" :disabled="!isEditing">
        </div>
        <div class="form-group">
          <label>Residential Zip Code</label>
          <input type="text" v-model="employee.residential_zipcode" class="form-control" :disabled="!isEditing">
        </div>
      </div>
    </div>

    <!-- Right side: Profile Image and Actions -->
    <div class="right-side">
      <!-- Profile Actions -->
      <button @click="viewEmployeeDocuments" class="btn btn-primary">
        <i class="fas fa-folder-open"></i> View Employee's Documents
      </button>
      <div class="profile-image-container">
        <img :src="employee.profileImage || 'default-profile.png'" class="profile-image" alt="Profile Image">
      </div>
      <div v-if="isEditing" class="upload-section">
        <input type="file" @change="handleImageUpload" accept="image/*" />
        <button v-if="employee.profileImage && !imageToDelete" @click="confirmDeleteImage" class="btn btn-danger mt-2">Delete Image</button>
      </div>

      <!-- Edit, Save, Cancel buttons -->
      <div class="button-group mt-3">
        <button v-if="!isEditing" @click="enableEdit" class="btn btn-warning">
          <i class="fas fa-edit"></i> Update Information
        </button>
        
        <button v-if="isEditing" @click="saveEmployeeDetails" class="btn btn-success me-2">
          <i class="fas fa-save"></i> Save
        </button>
        
        <button v-if="isEditing" @click="cancelEdit" class="btn btn-secondary">
          <i class="fas fa-times"></i> Cancel
        </button>
        
        <button @click="softDeleteEmployee" class="btn btn-danger ms-2">
          <i class="fas fa-user-slash"></i> Deactivate Employee
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      employee: null,
      isEditing: false,
      selectedImage: null,
      imageToDelete: false,
    };
  },
  created() {
    const employeeId = this.$route.params.id;
    if (employeeId) {
      this.fetchEmployeeDetails(employeeId);
    } else {
      console.error('Employee ID is undefined');
    }
  },
  methods: {
    async fetchEmployeeDetails(employeeId) {
      try {
        const response = await axios.get(`/api/admin/employees/${employeeId}`);
        this.employee = response.data.employee;
      } catch (error) {
        console.error('Error fetching employee details:', error);
      }
    },
    enableEdit() {
      this.isEditing = true;
    },
    cancelEdit() {
      this.isEditing = false;
      this.fetchEmployeeDetails(this.employee.id); // Reset the form to original data
    },
    async saveEmployeeDetails() {
      try {
        if (this.selectedImage) {
          const formData = new FormData();
          formData.append('profileImage', this.selectedImage);
          await axios.post(`/api/admin/employees/${this.employee.id}/upload-image`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
          });
        } else if (this.imageToDelete) {
          await axios.post(`/api/admin/employees/${this.employee.id}/delete-image`);
        }
        await axios.put(`/api/admin/employees/${this.employee.id}`, this.employee);
        this.isEditing = false;
        alert('Employee details updated successfully!');
      } catch (error) {
        console.error('Error saving employee details:', error);
        alert('Failed to update employee details');
      }
    },
    handleImageUpload(event) {
      const file = event.target.files[0];
      if (file) {
        this.selectedImage = file;
        this.imageToDelete = false;
      }
    },
    confirmDeleteImage() {
      if (confirm('Are you sure you want to delete this image?')) {
        this.imageToDelete = true;
        this.selectedImage = null;
      }
    },
    async softDeleteEmployee() {
      if (confirm('Are you sure you want to deactivate this employee?')) {
        try {
          await axios.delete(`/api/admin/employees/${this.employee.id}/deactivate`);
          alert('Employee has been deactivated.');
          this.$router.push({ name: 'SecPageEmployeeList' });
        } catch (error) {
          console.error('Error deactivating employee:', error);
          alert('Failed to deactivate employee');
        }
      }
    },
    viewEmployeeDocuments() {
      this.$router.push({ name: 'SecPageEmployeeDocuments', params: { id: this.employee.id } });
    }
  }
};
</script>

<style scoped>
.profile-info-container {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  padding: 20px;
  max-width: 1200px;
  margin: 0 auto;
  background-color: #f9f9f9;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.left-side {
  flex: 3;
  margin-right: 20px;
}

.right-side {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.profile-image-container {
  position: relative;
  width: 150px;
  height: 200px;
  background-image: url('/public/user.png'); /* Replace with actual path to background image */
  background-size: cover;
  background-position: center;
  border-radius: 50%;
  margin-top: 10px;
  overflow: hidden; /* Ensures the image stays within circular shape */
}

.profile-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.upload-section {
  margin-top: 20px;
}

.upload-section input {
  display: block;
  margin: 0 auto;
}

.upload-section button {
  display: block;
  width: 100%;
  margin-top: 10px;
}

.button-group {
  display: flex;
  justify-content: flex-end;
  width: 100%;
  margin-top: 20px;
}

.form-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 15px;
  gap: 15px;
}

.form-group {
  flex: 1 1 calc(33.33% - 10px);
  margin-bottom: 15px;
  min-width: 150px; 
}

.form-group label {
  font-weight: bold;
}

.me-2 {
  margin-right: 8px;
}

.ms-2 {
  margin-left: 8px;
}
.profile-image-container {
  position: relative;
  width: 150px;
  height: 200px;
  background-color: #e0e0e0;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 10px;
  overflow: hidden;
  margin-top: 20px;
}

.profile-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.button-group button {
  width: 100%;
  margin-top: 10px;
}

.form-group {
  margin-bottom: 15px;
}

.form-row {
  display: flex;
  gap: 15px;
}

.upload-section input[type="file"] {
  margin-top: 10px;
}

</style>

