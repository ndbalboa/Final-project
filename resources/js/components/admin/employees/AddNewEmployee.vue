<template>
  <h2>Add New Employee</h2>
  <div class="container mt-5">
    <h4 class="text-muted">Personal Information</h4>
    <form @submit.prevent="submitForm">
      <!-- Personal Information -->
      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="lastName">Last Name</label>
          <input type="text" placeholder="Last Name" class="form-control" id="lastName" v-model="form.lastName" @input="validateName('lastName')" required>
          <span v-if="errors.lastName" class="text-danger">{{ errors.lastName }}</span>
        </div>
        <div class="col-md-4 mb-3">
          <label for="firstName">First Name</label>
          <input type="text" placeholder="First Name" class="form-control" id="firstName" v-model="form.firstName" @input="validateName('firstName')" required>
          <span v-if="errors.firstName" class="text-danger">{{ errors.firstName }}</span>
        </div>
        <div class="col-md-4 mb-3">
          <label for="middleName">Middle Name</label>
          <input type="text" placeholder="Middle Name" class="form-control" id="middleName" v-model="form.middleName" @input="validateName('middleName')">
          <span v-if="errors.middleName" class="text-danger">{{ errors.middleName }}</span>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3 mb-3">
          <label for="sex">Sex</label>
          <select class="form-control" id="sex" v-model="form.sex" required>
            <option value="" disabled selected>--Sex--</option>
            <option>Male</option>
            <option>Female</option>
            <option>Other</option>
          </select>
        </div>
        <div class="col-md-3 mb-3">
          <label for="civilStatus">Civil Status</label>
          <select class="form-control" id="civilStatus" v-model="form.civilStatus" required>
            <option value="" disabled selected>--Civil Status--</option>
            <option>Single</option>
            <option>Married</option>
            <option>Divorced</option>
            <option>Widowed</option>
          </select>
        </div>
        <div class="col-md-3 mb-3">
          <label for="dateOfBirth">Date of Birth</label>
          <input type="date" class="form-control" id="dateOfBirth" v-model="form.dateOfBirth" required>
        </div>
        <div class="col-md-3 mb-3">
          <label for="religion">Religion</label>
          <input type="text" placeholder="Religion" class="form-control" id="religion" v-model="form.religion">
        </div>
      </div>
      <!-- Contact Information -->
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="emailAddress">Email Address</label>
          <input type="email" placeholder="sample@gmail.com" class="form-control" id="emailAddress" v-model="form.emailAddress" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="phoneNumber">Phone Number</label>
          <input type="tel" class="form-control" id="phoneNumber" v-model="form.phoneNumber" required>
        </div>
      </div>
      <!-- Identification Numbers -->
      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="gsisId">GSIS Id No.</label>
          <input type="text" class="form-control" id="gsisId" v-model="form.gsisId">
        </div>
        <div class="col-md-4 mb-3">
          <label for="pagibigId">PAG-IBIG Id No.</label>
          <input type="text" class="form-control" id="pagibigId" v-model="form.pagibigId">
        </div>
        <div class="col-md-4 mb-3">
          <label for="philhealthId">PhilHealth Id No.</label>
          <input type="text" class="form-control" id="philhealthId" v-model="form.philhealthId">
        </div>
      </div>
      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="sssNo">SSS No.</label>
          <input type="text" class="form-control" id="sssNo" v-model="form.sssNo">
        </div>
        <div class="col-md-4 mb-3">
          <label for="agencyEmploymentNo">Agency Employment No.</label>
          <input type="text" class="form-control" id="agencyEmploymentNo" v-model="form.agencyEmploymentNo">
        </div>
        <div class="col-md-4 mb-3">
          <label for="taxId">Tax Identification No.</label>
          <input type="text" class="form-control" id="taxId" v-model="form.taxId">
        </div>
      </div>
<!-- Academic and University Information -->
<div class="row">
  <!-- Academic Rank -->
  <div class="col-md-4 mb-3">
    <label for="academicRank">Academic Rank</label>
    <select class="form-control" id="academicRank" v-model="form.academicRank">
      <option>Professor</option>
      <option>Associate Professor</option>
      <option>Assistant Professor</option>
      <option>Lecturer</option>
      <option>Instructor</option>
    </select>
  </div>

  <!-- University Position -->
  <div class="col-md-4 mb-3">
    <label for="universityPosition">University Position</label>
    <select class="form-control" id="universityPosition" v-model="form.universityPosition">
      <option>Dean</option>
      <option>Director</option>
      <option>Coordinator</option>
      <option>Faculty</option>
      <option>Staff</option>
    </select>
  </div>

  <!-- Department -->
  <div class="col-md-4 mb-3">
    <label for="department">Department</label>
    <select class="form-control" id="department" v-model="form.department">
      <option>Supply and Property Management Office</option>
      <option>Physical Plants and Facilities Office</option>
      <option>General Services Office</option>
      <option>HRDC Office</option>
      <option>Medical Service Office</option>
      <option>Quality Management System on Administrative Services Office</option>
      <option>Human Resource Management Office</option>
      <option>IT Support Office</option>
      <option>Security Office</option>
      <option>Income Generating Office</option>
      <option>Integrated Multimedia Office</option>
      <option>Housing Service Office</option>
      <option>Accounting Office</option>
      <option>Budget Office</option>
      <option>Cash Management Office</option>
      <option>Guidance Office</option>
      <option>Sports Development Office</option>
      <option>Student Organization and Services Office</option>
      <option>Scholarship and Financial Assistance Office</option>
      <option>Students with Special Needs Office</option>
      <option>Students Performing Arts Office</option>
      <option>Office of the Vice President for Research, Innovation and Extension</option>
      <option>Research Ethics Office</option>
      <option>Community Extension Services Office</option>
      <option>Curriculum Development Office</option>
    </select>
  </div>
</div>

      <!-- Address and Profile Image -->
      <div class="row">
        <div class="col-md-6 mb-3">
          <h5>Permanent Address</h5>
        </div>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="permanent_street">Street</label>
            <input type="text" class="form-control" id="permanent_street" placeholder="Street" v-model="form.permanent_street" required>
          </div>
          <div class="col-md-4 mb-3">
            <label for="permanent_barangay">Barangay</label>
            <input type="text" class="form-control" id="permanent_barangay" placeholder="Barangay" v-model="form.permanent_barangay" required>
          </div>
          <div class="col-md-4 mb-3">
            <label for="permanent_city">Town/City</label>
            <input type="text" class="form-control" id="permanent_city" placeholder="Town/City" v-model="form.permanent_city">
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="permanent_province">Province</label>
            <input type="text" class="form-control" id="permanent_province" placeholder="Province" v-model="form.permanent_province" required>
          </div>
          <div class="col-md-4 mb-3">
            <label for="permanent_country">Country</label>
            <input type="text" class="form-control" id="permanent_country" placeholder="Country" v-model="form.permanent_country" required>
          </div>
          <div class="col-md-4 mb-3">
            <label for="permanent_zipcode">Zipcode</label>
            <input type="text" class="form-control" id="permanent_zipcode" placeholder="Zipcode" v-model="form.permanent_zipcode">
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <h5>Residential Address</h5>
        </div>
        <div class="nrow">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="sameAddress" v-model="sameAddress" @change="autoFillResidentialAddress">
            <label class="form-check-label" for="sameAddress">
              Residential address is the same as permanent address
            </label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="residential_street">Street</label>
            <input type="text" class="form-control" id="residential_street" placeholder="Street" v-model="form.residential_street" required>
          </div>
          <div class="col-md-4 mb-3">
            <label for="residential_barangay">Barangay</label>
            <input type="text" class="form-control" id="residential_barangay" placeholder="Barangay" v-model="form.residential_barangay" required>
          </div>
          <div class="col-md-4 mb-3">
            <label for="residential_city">Town/City</label>
            <input type="text" class="form-control" id="residential_city" placeholder="Town/City"v-model="form.residential_city">
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="residential_province">Province</label>
            <input type="text" class="form-control" id="residential_province" placeholder="Province" v-model="form.residential_province" required>
          </div>
          <div class="col-md-4 mb-3">
            <label for="residential_country">Country</label>
            <input type="text" class="form-control" id="residential_country" placeholder="Country" v-model="form.residential_country" required>
          </div>
          <div class="col-md-4 mb-3">
            <label for="residential_zipcode">Zipcode</label>
            <input type="text" class="form-control" id="residential_zipcode" placeholder="Zipcode" v-model="form.residential_zipcode">
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Add Employee</button>
    </form>
  </div>
</template>

<script>

export default {

  data() {
    return {
      form: {
        lastName: '',
        firstName: '',
        middleName: '',
        sex: '',
        civilStatus: '',
        dateOfBirth: '',
        religion: '',
        emailAddress: '',
        phoneNumber: '',
        gsisId: '',
        pagibigId: '',
        philhealthId: '',
        sssNo: '',
        agencyEmploymentNo: '',
        taxId: '',
        academicRank: '',
        universityPosition: '',
        department: '',
        permanent_street: '',
        permanent_barangay: '',
        permanent_city: '',
        permanent_province: '',
        permanent_country: '',
        permanent_zipcode: '',
        residential_street: '',
        residential_barangay: '',
        residential_city: '',
        residential_province: '',
        residential_country: '',
        residential_zipcode: '',
        
      },
      sameAddress: false,
      errors: {
        lastName: '',
        firstName: '',
        middleName: '',
      },
    };
  },
  methods: {
    validateName(field) {
    // Adjusted regex to allow specific Latin letters and disallow special characters
    const nameRegex = /^[A-Za-zñÑṳṵ\s]+$/; // Add any other characters you want to allow
    if (!nameRegex.test(this.form[field])) {
      this.errors[field] = 'Only letters and spaces are allowed.';
    } else {
      this.errors[field] = '';
    }
    },
    autoFillResidentialAddress() {
      if (this.sameAddress) {
        this.form.residential_street = this.form.permanent_street;
        this.form.residential_barangay = this.form.permanent_barangay;
        this.form.residential_city = this.form.permanent_city;
        this.form.residential_province = this.form.permanent_province;
        this.form.residential_country = this.form.permanent_country;
        this.form.residential_zipcode = this.form.permanent_zipcode;
      } else {
        // Clear residential fields if unchecked
        this.form.residential_street = '';
        this.form.residential_barangay = '';
        this.form.residential_city = '';
        this.form.residential_province = '';
        this.form.residential_country = '';
        this.form.residential_zipcode = '';
      }
    },
    onFileChange(event) {
      const file = event.target.files[0];
      if (file) {
        this.form.profileImage = file;
      }
    },
    submitForm() {
      const formData = new FormData();
      Object.keys(this.form).forEach(key => {
        formData.append(key, this.form[key]);
      });

      // Make your API call to save the employee data
      this.$axios.post('/api/admin/employees', formData)
        .then(response => {
          console.log('Employee added successfully', response.data);
          this.resetForm();
        })
        .catch(error => {
          console.error('Error adding employee:', error);
        });
    },
    resetForm() {
      this.form = {
        lastName: '',
        firstName: '',
        middleName: '',
        sex: '',
        civilStatus: '',
        dateOfBirth: '',
        religion: '',
        emailAddress: '',
        phoneNumber: '',
        gsisId: '',
        pagibigId: '',
        philhealthId: '',
        sssNo: '',
        agencyEmploymentNo: '',
        taxId: '',
        academicRank: '',
        universityPosition: '',
        permanent_street: '',
        permanent_barangay: '',
        permanent_city: '',
        permanent_province: '',
        permanent_country: '',
        permanent_zipcode: '',
        residential_street: '',
        residential_barangay: '',
        residential_city: '',
        residential_province: '',
        residential_country: '',
        residential_zipcode: '',
        department: '',
      };
      this.sameAddress = false;
      this.errors = {
        lastName: '',
        firstName: '',
        middleName: '',
      };
    },
  },
};
</script>

<style scoped>
.container {
  max-width: 1000px;
  margin-left: 40px;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h2 {
  margin-left: 20px;
  margin-bottom: 5px;
  color: #343a40; /* Darker text color for better readability */
}

h4 {
  margin-top: 20px;
  margin-bottom: 15px;
  color: #6c757d; /* Muted color for subheadings */
}
label {
  font-weight: 600;
}

.form-control {
  border-radius: 5px;
  border: 1px solid #ced4da; /* Border color */
  padding: 10px; /* Increased padding */
  transition: border-color 0.3s; /* Smooth border color transition */
}

.form-control:focus {
  border-color: #80bdff; /* Light blue border on focus */
  box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Add subtle shadow */
}

.btn {
  background-color: #007bff; /* Primary button color */
  color: white; /* White text on button */
  padding: 10px 15px; /* Button padding */
  border: none; /* Remove default border */
  border-radius: 5px; /* Rounded corners */
  cursor: pointer; /* Pointer cursor on hover */
  transition: background-color 0.3s; /* Smooth background color transition */
}

.btn:hover {
  background-color: #0056b3; /* Darker blue on hover */
}

.text-danger {
  font-size: 0.9rem; /* Slightly smaller error message font */
  margin-top: 5px; /* Add space above error messages */
}

.row {
  margin-bottom: 15px; /* Space between rows */
}
.nrow {
  margin-bottom: 0px; /* Space between rows */
}

.form-check {
  display: flex;
  align-items: center; /* Center checkbox vertically */

}

.form-check-input {
  margin-right: 10px; /* Space between checkbox and label */
}

/* Styling for the checkbox label */
.form-check-label {
  font-size: 12px; /* Set the desired font size */
  font-family: 'Arial', sans-serif; /* Set the desired font family */
  color: #333; /* Set the desired font color */
}
</style>
