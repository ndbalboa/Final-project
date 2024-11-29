<template>
    <h2>{{ document ? document.document_type : 'Travel Order' }}</h2>
    <div v-if="document" class="action-buttons">
      <button @click="viewFile" v-if="document.file_path" class="btn-primary">
        <i class="fas fa-eye"></i> View File
      </button>
      <button v-if="!isEditing" @click="editDocument" class="btn-secondary">
        <i class="fas fa-edit"></i> Edit Document
      </button>
      <button v-if="isEditing" @click="saveDocument" class="btn-primary">
        <i class="fas fa-save"></i> Save Changes
      </button>
      <button @click="deleteDocument" class="btn-danger">
        <i class="fas fa-trash"></i> Delete Document
      </button>
    </div>
    <div class="document-details-container">
      <div v-if="document" class="document-details">
        <div class="details-grid">
          <label for="doc-no"><strong>Document No:</strong></label>
          <input id="doc-no" v-model="document.document_no" :disabled="!isEditing" />
  
          <label for="series-no"><strong>Series Year:</strong></label>
          <input id="series-no" v-model="document.series_no" :disabled="!isEditing" />
  
          <div class="inclusive-date">
            <label><strong>Inclusive Date:</strong></label>
            <div class="date-range">
              <input id="from-date" :value="formatDate(document.from_date)" :disabled="!isEditing" />
              <span>to</span>
              <input id="to-date" :value="formatDate(document.to_date)" :disabled="!isEditing" />
            </div>
          </div>
  
          <label for="subject"><strong>Subject:</strong></label>
          <textarea 
            id="subject" 
            v-model="document.subject" 
            @input="resizeTextarea($event)" 
            :disabled="!isEditing"
            class="resizable-textarea"
          ></textarea>
  
          <label for="description"><strong>Description:</strong></label>
          <textarea 
            id="description" 
            v-model="document.description" 
            @input="resizeTextarea($event)" 
            :disabled="!isEditing"
            class="resizable-textarea"
          ></textarea>
  
          <label for="date-issued"><strong>Date Issued:</strong></label>
          <input id="date-issued" v-model="document.date_issued" :disabled="!isEditing" />
  
          <label for="employee-names"><strong>Employee Names:</strong></label>
          <textarea 
            id="employee-names" 
            v-model="employeeNames" 
            @input="resizeTextarea($event)" 
            :disabled="!isEditing"
            class="resizable-textarea"
          ></textarea>

          <label for="venue"><strong>Venue:</strong></label>
          <input id="venue" v-model="document.venue" :disabled="!isEditing" />

          <label for="destination"><strong>Destination:</strong></label>
          <input id="destination" v-model="document.destination" :disabled="!isEditing" />
        </div>
      </div>
  
      <div v-else-if="error" class="error-message">
        <p>{{ error }}</p>
      </div>
  
      <div v-else>
        <p>Loading document details...</p>
      </div>
  
      <button @click="goBack" class="btn-secondary">Go Back</button>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        document: null,
        error: null,
        isEditing: false,
        appUrl: 'http://127.0.0.1:8000',
      };
    },
    computed: {
      employeeNames() {
        return this.document && this.document.employee_names 
          ? this.document.employee_names.join('\n') 
          : '';
      },
    },
    methods: {
      async fetchDocumentDetails() {
        const documentId = this.$route.params.id;
  
        if (!documentId) {
          this.error = 'Invalid document ID.';
          return;
        }
  
        try {
          const response = await axios.get(`/api/admin/documents/${documentId}`);
          this.document = response.data;
          this.$nextTick(() => {
            this.autoResizeTextareas();
          });
        } catch (error) {
          console.error('Error fetching document details:', error);
          if (error.response && error.response.status === 404) {
            this.error = 'Document not found.';
          } else {
            this.error = 'Failed to load document details. Please try again later.';
          }
        }
      },
      editDocument() {
        this.isEditing = true;
      },
      async saveDocument() {
        try {
          await axios.put(`/api/admin/documents/${this.document.id}`, this.document);
          alert('Document updated successfully.');
          this.isEditing = false;
        } catch (error) {
          console.error('Error saving document:', error);
          alert('Failed to save document. Please try again later.');
        }
      },
      formatDate(date) {
        if (!date) return '';
        const d = new Date(date);
        const month = (d.getMonth() + 1).toString().padStart(2, '0');
        const day = d.getDate().toString().padStart(2, '0');
        const year = d.getFullYear();
        return `${month}-${day}-${year}`;
      },
      getFileUrl(filePath) {
        if (!filePath) {
          console.error('File path is undefined');
          return '';
        }
        return `${this.appUrl}/storage/${filePath}`;
      },
      viewFile() {
        const fileUrl = this.getFileUrl(this.document.file_path);
        if (fileUrl) {
          window.open(fileUrl, '_blank');
        } else {
          console.error('Cannot view file: URL is undefined');
        }
      },
      async deleteDocument() {
      if (confirm('Are you sure you want to delete this document?')) {
        try {
          await axios.delete(`/api/admin/documents/${this.document.id}`);
          alert('Document deleted successfully.');
          this.$router.push('/documents'); 
        } catch (error) {
          console.error('Error deleting document:', error);
          alert('Failed to delete document. Please try again later.');
        }
      } else {
        alert('Document deletion canceled.');
      }
    },
      goBack() {
        this.$router.go(-1);
      },
      autoResizeTextareas() {
        this.$nextTick(() => {
          const textareas = this.$refs.textareas || [];
          textareas.forEach(textarea => {
            textarea.style.height = 'auto';
            textarea.style.height = `${textarea.scrollHeight}px`;
          });
        });
      },
      resizeTextarea(event) {
        const textarea = event.target;
        textarea.style.height = 'auto';
        textarea.style.height = `${textarea.scrollHeight}px`;
      },
    },
    mounted() {
      this.fetchDocumentDetails();
    },
    watch: {
      document() {
        this.$nextTick(() => {
          this.autoResizeTextareas();
        });
      },
    },
  };
  </script>
  
  <style scoped>
  .document-details-container {
    margin: 20px auto;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    max-width: 720px;
    position: relative;
  }
  
  .action-buttons {
    margin-top: 115px;
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    gap: 10px;
  }
  
  h2 {
    text-align: left;
    font-family: 'Arial', sans-serif;
    color: #333;
    margin-bottom: 20px;
  }
  
  .details-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 10px;
  }
  
  .details-grid label {
    font-weight: bold;
  }
  
  .details-grid input,
  .details-grid textarea {
    font-size: 14px;
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #ccc;
    background-color: #f9f9f9;
    resize: none; 
  }
  
  .details-grid textarea {
    height: auto; 
    min-height: 40px; 
    overflow: hidden; 
    resize: vertical; /* Enable vertical resizing */
  }
  
  #description {
    height: 200px; 
  }
  
  .btn-primary,
  .btn-danger,
  .btn-secondary {
    display: inline-block;
    padding: 10px 20px;
    font-size: 14px;
    color: white;
    text-align: center;
    border-radius: 5px;
    cursor: pointer;
  }
  
  .btn-primary {
    background-color: #007bff;
  }
  
  .btn-primary:hover {
    background-color: #0056b3;
  }
  
  .btn-danger {
    background-color: #dc3545;
  }
  
  .btn-danger:hover {
    background-color: #c82333;
  }
  
  .btn-secondary {
    background-color: #6c757d;
  }
  
  .btn-secondary:hover {
    background-color: #5a6268;
  }
  
  .error-message {
    color: red;
    text-align: center;
    margin-top: 20px;
  }
  
  .inclusive-date {
    grid-column: span 2;
    display: flex;
    align-items: center;
    gap: 120px; 
  }
  
  .inclusive-date .date-range {
    display: flex; 
    align-items: center; 
    gap: 5px; 
  }
  
  .inclusive-date input {
    width: auto; 
    text-align: center; 
  }
  
  .resizable-textarea {
    overflow: auto; 
    resize: vertical; 
  }
  
  </style>
  