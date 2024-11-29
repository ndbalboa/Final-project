<template>
  <div class="document-form">
    <h2>Document Details</h2>
    <form @submit.prevent="saveDocument" class="form-container">
      <div class="form-group">
        <label>Document Type:</label>
        <select v-model="document.document_type">
          <option>Travel Order</option>
          <option>Office Order</option>
          <option>Special Order</option>
          <optgroup label="DBM">
            <option>SARO</option>
            <option>NCA</option>
            <option>Budget Circular</option>
            <option>Advised of NCA Issued</option>
            <option>Advisory</option>
            <option>Joint Circular</option>
            <option>Memorandum Circular</option>
          </optgroup>
        </select>
      </div>

      <!-- Conditional Rendering for DBM Document Types -->
      <div v-if="isDBMDocumentType">
        <div class="form-group">
          <label>Date Issued:</label>
          <input type="date" v-model="document.date_issued" />
        </div>
        <div class="form-group">
          <label>Subject:</label>
          <input type="text" v-model="document.subject" />
        </div>
        <div class="form-group">
          <label>Description:</label>
          <textarea v-model="document.description"></textarea>
        </div>
        <div class="form-group">
          <label>Tag Employee:</label>
          <input type="checkbox" v-model="tagEmployee" />
        </div>
      </div>

      <!-- Default Fields for Non-DBM Document Types -->
      <div v-else>
        <!-- Document Number Section with Dynamic Label -->
        <div class="form-group">
          <label>{{ documentNumberLabel }}:</label>
          <input type="text" v-model="document.document_no" />
        </div>
        <div class="form-group">
          <label>Series No:</label>
          <input type="text" v-model="document.series_no" />
        </div>
        <div class="form-group">
          <label>Date Issued:</label>
          <input type="date" v-model="document.date_issued" />
        </div>
        <div class="form-group">
          <label>From:</label>
          <input type="text" v-model="document.from_date" />
        </div>
        <div class="form-group">
          <label>To:</label>
          <input type="text" v-model="document.to_date" />
        </div>
        <div class="form-group">
          <label>Subject:</label>
          <input type="text" v-model="document.subject" />
        </div>
        <div class="form-group">
          <label>Description:</label>
          <textarea v-model="document.description"></textarea>
        </div>

        <!-- Employee Names Section -->
        <div class="form-group">
          <label>Employee Names:</label>
          <transition-group name="fade" tag="div">
            <div v-for="(employee, index) in document.employee_names" :key="index" class="employee-group">
              <input type="text" v-model="document.employee_names[index]" />
              <button type="button" class="btn-remove" @click="removeEmployee(index)">Remove</button>
            </div>
          </transition-group>
          <button type="button" class="btn-add" @click="addEmployee">Add Employee</button>
        </div>
      </div>

      <button type="submit" class="btn-submit">Save</button>
    </form>

    <div v-if="errorMessage" class="error">{{ errorMessage }}</div>
    <div v-if="successMessage" class="success">{{ successMessage }}</div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      document: {
        document_no: "",
        series_no: "",
        date_issued: "",
        from: "",
        to: "",
        subject: "",
        description: "",
        document_type: "Travel Order", // Default document type
        employee_names: [], // Array to store employee names
        file_path: "", // For storing the file path returned from the backend
      },
      tagEmployee: false, // Checkbox state for DBM documents
      errorMessage: null,
      successMessage: null,
    };
  },
  created() {
    const extractedData = localStorage.getItem("extractedData");
    if (extractedData) {
      try {
        this.document = JSON.parse(extractedData);

        if (this.document.date_issued) {
          this.document.date_issued = this.formatDate(this.document.date_issued);
        }

        if (!Array.isArray(this.document.employee_names)) {
          this.document.employee_names = [];
        }
      } catch (error) {
        console.error("Error parsing extracted data from localStorage:", error);
      }
    }
  },
  computed: {
    // Dynamic label for the document number field
    documentNumberLabel() {
      switch (this.document.document_type) {
        case "Travel Order":
          return "Travel Order Number";
        case "Office Order":
          return "Office Order Number";
        case "Special Order":
          return "Special Order Number";
        default:
          return "Document Number"; // Fallback
      }
    },
    // Check if the selected document type belongs to DBM
    isDBMDocumentType() {
      const dbmTypes = ["SARO", "NCA", "Budget Circular", "Advised of NCA Issued", "Advisory", "Joint Circular", "Memorandum Circular"];
      return dbmTypes.includes(this.document.document_type);
    },
  },
  methods: {
    async saveDocument() {
      try {
        this.clearMessages();

        if (!this.document.document_type || !this.document.file_path) {
          this.errorMessage = "Please ensure all required fields are filled.";
          return;
        }

        const response = await axios.post("/api/admin/documents/save", this.document);

        if (response.data) {
          this.successMessage = "Document saved successfully.";
          localStorage.removeItem("extractedData");
        }
      } catch (error) {
        this.errorMessage = error.response?.data?.error || "Failed to save the document.";
      }
    },
    formatDate(dateStr) {
      const dateObj = new Date(dateStr);
      return dateObj.toISOString().split("T")[0];
    },
    clearMessages() {
      this.errorMessage = null;
      this.successMessage = null;
    },
    addEmployee() {
      this.document.employee_names.push("");
    },
    removeEmployee(index) {
      this.document.employee_names.splice(index, 1);
    },
  },
};
</script>

<style scoped>
/* Form Styling */
.document-form {
  background-color: #f9f9f9;
  padding: 20px;
  border-radius: 8px;
  margin-left: 20px;
  margin-right: 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #333;
}

.form-container {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

label {
  margin-bottom: 5px;
  font-weight: bold;
}

input[type="text"], textarea, select, input[type="date"] {
  padding: 10px;
  border-radius: 4px;
  border: 1px solid #ccc;
  font-size: 1rem;
}

textarea {
  resize: none;
  height: 100px;
}

button {
  cursor: pointer;
  border: none;
  border-radius: 4px;
  padding: 10px;
  font-size: 1rem;
}

.btn-add {
  background-color: #4caf50;
  color: white;
  width: 500px;
}

.btn-remove {
  background-color: #f44336;
  color: white;
  margin-left: 10px;
}

.btn-submit {
  background-color: #007bff;
  color: white;
  margin-top: 20px;
}

.error {
  color: red;
  margin-top: 10px;
  text-align: center;
}

.success {
  color: green;
  margin-top: 10px;
  text-align: center;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}

.employee-group input[type="text"] {
  width: 400px; /* Adjust the width as needed */
}

.employee-group {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}
</style>
