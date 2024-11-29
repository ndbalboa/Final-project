<template>
    <div class="create-department">
      <h2>Create Department Account</h2>
      <form @submit.prevent="createDepartment" class="create-department-form">
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="firstName">First Name</label>
            <input
              id="firstName"
              v-model="firstName"
              placeholder="First Name"
              required
            />
          </div>
          <div class="col-md-4 mb-3">
            <label for="lastName">Last Name</label>
            <input
              id="lastName"
              v-model="lastName"
              placeholder="Last Name"
              required
            />
          </div>
        </div>
  
        <!-- New Row: Email, Username, and Department -->
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="email">Email</label>
            <input
              id="email"
              type="email"
              v-model="email"
              placeholder="Email"
              required
            />
          </div>
          <div class="col-md-4 mb-3">
            <label for="username">Username</label>
            <input
              id="username"
              v-model="username"
              placeholder="Username"
              required
            />
          </div>
          <div class="col-md-4 mb-3">
            <label for="department">Department</label>
            <select class="form-control" id="department" v-model="department">
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
  
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="password">Password</label>
            <input
              id="password"
              type="password"
              v-model="password"
              placeholder="Password"
              required
            />
          </div>
          <div class="col-md-4 mb-3">
            <label for="confirmPassword">Confirm Password</label>
            <input
              id="confirmPassword"
              type="password"
              v-model="confirmPassword"
              placeholder="Confirm Password"
              required
            />
          </div>
        </div>
  
        <button type="submit" class="submit-button">Create Account</button>
  
        <!-- Notification Messages -->
        <div
          v-if="successMessage"
          class="alert alert-success mt-3"
        >{{ successMessage }}</div>
        <div
          v-if="errorMessage"
          class="alert alert-danger mt-3"
        >{{ errorMessage }}</div>
      </form>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  
  export default {
    data() {
      return {
        firstName: "",
        lastName: "",
        email: "",
        department: "",
        username: "",
        password: "",
        confirmPassword: "",
        successMessage: "",
        errorMessage: "",
      };
    },
    methods: {
      async createDepartment() {
        try {
          const response = await axios.post("/api/admin/departments", {
            firstName: this.firstName,
            lastName: this.lastName,
            email: this.email,
            department: this.department,
            username: this.username,
            password: this.password,
            password_confirmation: this.confirmPassword,
            role: "department", // Explicitly setting role for department
          });
  
          this.successMessage = "Department account created successfully.";
          this.errorMessage = "";
          this.clearForm(); // Optional: Clear form fields after successful creation
        } catch (error) {
          if (error.response && error.response.status === 422) {
            this.errorMessage =
              "Validation errors: " +
              Object.values(error.response.data.errors).flat().join(", ");
          } else {
            this.errorMessage = "Error creating department account.";
          }
          this.successMessage = "";
        }
      },
      clearForm() {
        this.firstName = "";
        this.lastName = "";
        this.email = "";
        this.department = "";
        this.username = "";
        this.password = "";
        this.confirmPassword = "";
      },
    },
  };
  </script>
  
  <style scoped>
  /* Maintain existing styles */
  .create-department {
    padding: 30px;
    background-color: #f3f4f6;
    border-radius: 10px;
    margin: 0 auto;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  
  h2 {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
    font-size: 24px;
  }
  
  .create-department-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  
  .row {
    display: flex;
    gap: 20px;
  }
  
  .col-md-4 {
    flex: 1;
  }
  
  label {
    font-weight: bold;
    margin-bottom: 5px;
    display: block;
    color: #555;
  }
  
  input,
  select {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 14px;
    transition: border-color 0.3s;
  }
  
  input:focus,
  select:focus {
    border-color: #007bff;
    outline: none;
  }
  
  .submit-button {
    padding: 12px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    text-align: center;
    transition: background-color 0.3s;
  }
  
  .submit-button:hover {
    background-color: #0056b3;
  }
  
  .alert {
    padding: 15px;
    border-radius: 5px;
    margin-top: 10px;
  }
  
  .alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
  }
  
  .alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
  }
  </style>
  