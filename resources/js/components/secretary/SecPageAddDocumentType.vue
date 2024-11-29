<template>
  <div>
    <!-- Plus Icon to Trigger Modal -->
    <button
      type="button"
      class="btn btn-success btn-circle"
      @click="openModal"
      data-bs-toggle="modal"
      data-bs-target="#addDocumentTypeModal"
    >
      <i class="bi bi-plus"></i> <!-- Bootstrap Icon for Plus -->
    </button>

    <!-- Modal for Adding Document Type -->
    <div
      class="modal fade"
      id="addDocumentTypeModal"
      tabindex="-1"
      aria-labelledby="addDocumentTypeModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addDocumentTypeModalLabel">Add New Document Type</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitForm">
              <div class="form-group">
                <label for="document_type">Document Type</label>
                <input
                  type="text"
                  id="document_type"
                  v-model="documentType"
                  class="form-control"
                  required
                />
              </div>

              <button type="submit" class="btn btn-primary mt-3">
                Add Document Type
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Success and Error Messages -->
    <div v-if="errorMessage" class="alert alert-danger mt-3">
      {{ errorMessage }}
    </div>
    <div v-if="successMessage" class="alert alert-success mt-3">
      {{ successMessage }}
    </div>

    <!-- Document Types Table -->
    <h3 class="mt-5">Document Types</h3>
    <table class="table table-striped">
      <thead>
        <tr class="bg-primary text-white">
          <th>Document Type</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="documentType in documentTypes" :key="documentType.id">
          <td>{{ documentType.document_type }}</td>
          <td>
            <button
              @click="editDocumentType(documentType)"
              class="btn btn-warning btn-sm"
            >
              <i class="bi bi-pencil"></i> Edit <!-- Edit Icon -->
            </button>
            <button
              @click="deleteDocumentType(documentType.id)"
              class="btn btn-danger btn-sm ml-2"
            >
              <i class="bi bi-trash"></i> Delete <!-- Delete Icon -->
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
      documentType: "",
      documentTypes: [],
      successMessage: "",
      errorMessage: "",
      editingDocumentType: null, // Keeps track of which document type is being edited
    };
  },
  methods: {
    openModal() {
      // Reset form and error messages when opening the modal
      this.documentType = "";
      this.errorMessage = "";
      this.successMessage = "";
    },

    async submitForm() {
      try {
        if (this.editingDocumentType) {
          // If editing, send an update request
          await this.updateDocumentType();
        } else {
          // If adding new, send a create request
          await this.createDocumentType();
        }
        this.successMessage = this.editingDocumentType
          ? "Document type updated successfully!"
          : "Document type added successfully!";
        this.documentType = ""; // Reset form field
        this.editingDocumentType = null; // Reset the editing state
        this.fetchDocumentTypes(); // Fetch the updated list of document types
        // Close the modal after success
        const modal = new bootstrap.Modal(document.getElementById('addDocumentTypeModal'));
        modal.hide();
      } catch (error) {
        this.errorMessage = "Failed to process document type. Please try again.";
      }
    },

    async createDocumentType() {
      await axios.post("/api/admin/store/document-types", {
        document_type: this.documentType,
      });
    },

    async updateDocumentType() {
      await axios.put(`/api/admin/update/document-types/${this.editingDocumentType.id}`, {
        document_type: this.documentType,
      });
    },

    async deleteDocumentType(id) {
      if (confirm("Are you sure you want to delete this document type?")) {
        try {
          await axios.delete(`/api/admin/delete/document-types/${id}`);
          this.successMessage = "Document type deleted successfully!";
          this.fetchDocumentTypes(); // Refresh the list
        } catch (error) {
          this.errorMessage = "Failed to delete document type.";
        }
      }
    },

    async fetchDocumentTypes() {
      try {
        const response = await axios.get("/api/admin/document-types");
        this.documentTypes = response.data.documentTypes; // Populate the table with document types
      } catch (error) {
        this.errorMessage = "Failed to load document types.";
      }
    },

    editDocumentType(documentType) {
      this.editingDocumentType = documentType;
      this.documentType = documentType.document_type; // Populate the form with the document type to edit

      // Trigger the modal programmatically
      const modal = new bootstrap.Modal(document.getElementById('addDocumentTypeModal'));
      modal.show(); // Open the modal
    },
  },
  mounted() {
    this.fetchDocumentTypes(); // Fetch the list of document types when the component is mounted
  },
};
</script>

<style scoped>
.form-group {
  margin-bottom: 1rem;
}

.btn-circle {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.btn-circle i {
  font-size: 24px;
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
