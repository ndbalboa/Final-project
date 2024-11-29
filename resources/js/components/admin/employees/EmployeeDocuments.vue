<template>
  <div class="document-list-container">
    <!-- Loading indicator -->
    <p v-if="loading" class="loading-message">Loading employee details...</p>

    <!-- Employee Header -->
    <h2 v-if="!loading && employee.firstName && employee.lastName" class="employee-header">
      {{ employee.firstName }} {{ employee.lastName }}'s Documents
    </h2>

    <!-- Document Type Tabs and Search Bar -->
    <div v-if="!loading" class="tabs-container">
      <div class="tabs">
        <button
          v-for="type in documentTypes"
          :key="type"
          :class="['tab', { active: selectedType === type }]"
          @click="selectedType = type"
        >
          {{ type }}
        </button>
      </div>
      <div class="search-container">
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Search documents..."
          class="search-input"
        />
        <span class="search-icon" @click="searchDocuments">
          <i class="bi bi-search"></i> <!-- Bootstrap search icon -->
        </span>
      </div>
    </div>

    <!-- Table displaying documents -->
    <table v-if="!loading && filteredDocuments.length > 0" class="table table-bordered">
      <thead>
        <tr>
          <th>Document No</th>
          <th>Subject</th>
          <th>Description</th>
          <th>Date Issued</th>
          <th>Document Type</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="document in filteredDocuments"
          :key="document.id"
          :class="{ 'selected-row': document.id === selectedDocumentId }"
          @click="selectDocument(document.id)"
        >
          <td>{{ document.document_no }}</td>
          <td>{{ document.subject || 'Subject not found' }}</td>
          <td>{{ document.description || 'Description not found' }}</td>
          <td>{{ document.date_issued }}</td>
          <td>{{ document && document.document_type ? document.document_type.document_type : 'Travel Order' }}</td>
        </tr>
      </tbody>
    </table>

    <!-- No Documents Message -->
    <p v-if="!loading && filteredDocuments.length === 0" class="no-documents">No documents found for this document type.</p>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: ['id'],
  data() {
    return {
      employee: {},
      documents: [],
      selectedDocumentId: null,
      selectedType: 'All',
      documentTypes: ['All', 'Travel Order', 'Office Order', 'Special Order'],
      loading: true,
      searchQuery: '',
    };
  },
  computed: {
    filteredDocuments() {
      let filtered = this.documents;
      if (this.selectedType !== 'All') {
        filtered = filtered.filter((doc) => doc.document_type === this.selectedType);
      }
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase();
        filtered = filtered.filter((doc) =>
          doc.subject.toLowerCase().includes(query) || 
          doc.description.toLowerCase().includes(query)
        );
      }
      return filtered;
    },
  },
  methods: {
    fetchEmployeeDetails() {
      axios
        .get(`/api/admin/employees/${this.id}`)
        .then((response) => {
          this.employee = response.data;
          this.fetchEmployeeDocuments();
        })
        .catch((error) => {
          console.error('Error fetching employee details:', error);
        })
        .finally(() => {
          this.loading = false;
        });
    },
    fetchEmployeeDocuments() {
      axios
        .get(`/api/admin/employees/${this.id}/documents`)
        .then((response) => {
          this.documents = response.data;
        })
        .catch((error) => {
          console.error('Error fetching employee documents:', error);
        });
    },
    selectDocument(documentId) {
      this.selectedDocumentId = documentId;
      this.$router.push({ name: 'DocumentDetails', params: { id: documentId } });
    },
    searchDocuments() {
      console.log('Search initiated for:', this.searchQuery);
    },
  },
  mounted() {
    this.fetchEmployeeDetails();
  },
};
</script>

<style scoped>
.document-list-container {
  max-width: 1200px;
  margin: auto;
  padding: 20px;
  background-color: #ffffff;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.employee-header {
  font-family: 'Arial', sans-serif;
  font-size: 1.5em;
  color: #333;
  margin-bottom: 20px;
  text-align: center;
}

.loading-message {
  text-align: center;
  color: #555;
  font-size: 1.2em;
  margin-top: 20px;
}

.tabs-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.tabs {
  display: flex;
}

.tab {
  padding: 10px 20px;
  margin: 0 5px;
  background-color: #f0f0f0;
  border-radius: 5px;
  cursor: pointer;
  font-weight: bold;
  color: #333;
  transition: background-color 0.3s;
}

.tab:hover {
  background-color: #ddd;
}

.tab.active {
  background-color: #007bff;
  color: white;
}

.search-container {
  display: flex;
  align-items: center;
}

.search-input {
  padding: 5px;
  margin-left: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  width: 200px; /* Adjust width as needed */
}

.search-icon {
  margin-left: 5px;
  cursor: pointer;
  font-size: 1.2em; /* Adjust icon size */
  color: #007bff; /* Icon color */
}

.table {
  width: 100%;
  border-collapse: collapse;
}

.table th,
.table td {
  padding: 12px;
  text-align: left;
  border: 1px solid #ddd;
}

.table th {
  background-color: #f2f2f2;
  font-weight: bold;
  color: #555;
}

.selected-row {
  background-color: #87ceeb; /* Sky blue */
}

.no-documents {
  text-align: center;
  color: #777;
  font-size: 1em;
  margin-top: 20px;
}
</style>
