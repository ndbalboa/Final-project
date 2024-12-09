<template>
  <div class="document-form-container">
    <div class="document-form">
      <h2>Document Details</h2>
      <form @submit.prevent="saveDocument" class="form-container">
        <div class="form-group">
          <label>Document Type:</label>
          <select v-model="document.document_type">
            <option>Travel Order</option>
            <option>Office Order</option>
            <option>Special Order</option>
            <option>Notice of Meeting</option>
            <optgroup label="DBM">
              <option>Budget Circular</option>
              <option>CHED Circular</option>
              <option>COA Circular</option>
            </optgroup>
          </select>
        </div>

        <!-- Conditional Rendering for DBM Document Types -->
        <div v-if="isDBMDocumentType">
          <div class="form-group">
            <label>Document No:</label>
            <input type="text" v-model="document.document_no" />
          </div>
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
            <label>Employee Names:</label>
            <transition-group name="fade" tag="div">
              <div v-for="(employee, index) in document.employee_names" :key="index" class="employee-group">
                <input type="text" v-model="document.employee_names[index]" />
                <button type="button" class="btn-remove" @click="removeEmployee(index)">
                  <i class="fas fa-trash-alt"></i> Remove
                </button>
              </div>
            </transition-group>
            <button type="button" class="btn-add" @click="addEmployee">
                <i class="fas fa-plus"></i> Add Employee
            </button>
          </div>
        </div>

        <!-- Default Fields for Non-DBM Document Types -->
        <div v-else>
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
            <textarea v-model="document.subject" />
          </div>
          <div class="form-group">
            <label>Description:</label>
            <textarea v-model="document.description"></textarea>
          </div>
          <div class="form-group">
            <label>Venue:</label>
            <input type="text" v-model="document.venue" />
          </div>
          <div class="form-group">
            <label>Destination:</label>
            <input type="text" v-model="document.destination" />
          </div>

          <!-- Employee Names Section -->
          <div class="form-group">
            <label>Employee Names:</label>
            <transition-group name="fade" tag="div">
              <div v-for="(employee, index) in document.employee_names" :key="index" class="employee-group">
                <input type="text" v-model="document.employee_names[index]" />
                <button type="button" class="btn-remove" @click="removeEmployee(index)">
                  <i class="fas fa-trash-alt"></i> Remove
                </button>
              </div>
            </transition-group>
            <button type="button" class="btn-add" @click="addEmployee">
                <i class="fas fa-plus"></i> Add Employee
            </button>
          </div>
        </div>

        <button type="submit" class="btn-submit">Save</button>
      </form>

      <div v-if="errorMessage" class="error">{{ errorMessage }}</div>
      <div v-if="successMessage" class="success">{{ successMessage }}</div>
    </div>

    <!-- Document Preview Section -->
    <div class="document-preview">
      <h3>Document Preview</h3>
      <div v-if="document.file_path" class="preview-container">
        <!-- Check if the file is an image -->
        <img v-if="isImageFile" :src="document.file_path" alt="Document Image" width="100%" />
        <!-- Otherwise, assume it's a PDF or other supported file format -->
        <iframe v-else :src="document.file_path" width="100%" height="500px" frameborder="0"></iframe>
      </div>
      <div v-else>
        <p>No file selected for preview.</p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      document: {
        file_path: "",  // File path from backend
        document_no: "",
        series_no: "",
        date_issued: "",
        from_date: "",
        to_date: "",
        subject: "",
        description: "",
        venue: "",
        destination: "",
        document_type: "Travel Order", // Default document type
        employee_names: [], // Array to store employee names
      },
      errorMessage: null,
      successMessage: null,
    };
  },
  created() {
    const extractedData = localStorage.getItem("extractedData");
    if (extractedData) {
      try {
        this.document = JSON.parse(extractedData);

        if (this.document.file_path && !this.document.file_path.startsWith('http://127.0.0.1:8000/')) {
        // Prepend the base URL only if the file path doesn't already include it
        this.document.file_path = `/storage/${this.document.file_path}`;
        }

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
    documentNumberLabel() {
      switch (this.document.document_type) {
        case "Travel Order":
          return "Travel Order Number";
        case "Office Order":
          return "Office Order Number";
        case "Special Order":
          return "Special Order Number";
        case "Notice of Meeting":
          return "Meeting Notice Number";
        case "Budget Circular":
          return "Budget Circular Number";
        case "CHED Circular":
          return "CHED Circular Number";
        case "COA Circular":
          return "COA Circular Number";
        default:
          return "Document Number"; // Fallback
      }
    },
    isDBMDocumentType() {
      const dbmTypes = [
        "Budget Circular",
        "CHED Circular",
        "COA Circular"
      ];
      return dbmTypes.includes(this.document.document_type);
    },
    isImageFile() {
      // Check if the file is an image based on its file extension
      return /\.(jpg|jpeg|png|gif|bmp)$/i.test(this.document.file_path);
    },
  },
  methods: {
    async saveDocument() {
      try {
        this.clearMessages();

        // Basic validation
        if (!this.document.document_type || !this.document.date_issued) {
          this.errorMessage = "Please ensure all required fields are filled.";
          return;
        }

        // Validation specific to DBM documents
        if (this.isDBMDocumentType && (!this.document.subject || !this.document.description)) {
          this.errorMessage = "Subject and description are required for DBM documents.";
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
.document-form-container {
  display: flex;
  justify-content: space-between;
  gap: 20px;
}

.document-form {
  flex: 1;
  background-color: #f9f9f9;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.document-preview {
  flex: 1;
  background-color: #f1f1f1;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.preview-container {
  overflow: hidden;
}

h2, h3 {
  text-align: center;
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

button {
  cursor: pointer;
  border: none;
  border-radius: 4px;
  padding: 10px;
  font-size: 1rem;
}

.error, .success {
  margin-top: 10px;
  text-align: center;
}

.error {
  color: red;
}

.success {
  color: green;
}
</style>
