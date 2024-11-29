<template>
  <div>
    <h1>Travel Orders</h1>

    <!-- Search Bar and Top Bar Container -->
    <div class="search-and-top-bar">
      <!-- Search Bar -->
      <div class="search-bar-container">
        <i class="fas fa-search search-icon"></i>
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Search by document no, subject, or employee name..."
          class="search-bar"
        />
      </div>

      <!-- Results Counter and Pagination -->
      <div class="top-bar">
        <div class="results-counter">
          Showing {{ paginatedDocuments.length }} out of {{ filteredDocuments.length }} results
        </div>
        <div class="pagination">
          <button @click="prevPage" :disabled="currentPage === 1">❮</button>
          <button
            v-for="page in visiblePages"
            :key="page"
            :class="{ active: currentPage === page }"
            @click="setPage(page)"
            v-if="page !== '...'"
          >
            {{ page }}
          </button>
          <span v-else>...</span>
          <button @click="nextPage" :disabled="currentPage === totalPages">❯</button>
        </div>
      </div>
    </div>

    <!-- Document Table -->
    <table>
      <thead>
        <tr>
          <th>Document No</th>
          <th>Date Issued</th>
          <th>Subject</th>
          <th>Employee Names</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="document in paginatedDocuments"
          :key="document.id"
          @click="viewDocument(document.id)"
          class="clickable-row"
        >
          <td>{{ document.document_no }}</td>
          <td>{{ document.date_issued }}</td>
          <td>{{ document.subject }}</td>
          <td>
            <ul>
              <li v-for="(name, index) in document.employee_names" :key="index">
                {{ name }}
              </li>
            </ul>
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
      documents: [], // Full list of documents fetched from the server
      searchQuery: "", // User's search query
      currentPage: 1, // Current page for pagination
      perPage: 5, // Number of results per page
    };
  },
  computed: {
    // Filter documents based on the search query
    filteredDocuments() {
      const query = this.searchQuery.toLowerCase();
      return this.documents.filter(
        (document) =>
          document.document_no?.toLowerCase().includes(query) ||
          document.subject?.toLowerCase().includes(query) ||
          document.employee_names?.some((name) =>
            name.toLowerCase().includes(query)
          )
      );
    },
    // Get paginated documents based on the current page
    paginatedDocuments() {
      const start = (this.currentPage - 1) * this.perPage;
      const end = start + this.perPage;
      return this.filteredDocuments.slice(start, end);
    },
    // Calculate total number of pages
    totalPages() {
      return Math.ceil(this.filteredDocuments.length / this.perPage);
    },
    // Dynamic pagination logic for displaying ellipses
    visiblePages() {
      const total = this.totalPages;
      const current = this.currentPage;

      if (total <= 5) {
        return Array.from({ length: total }, (_, i) => i + 1);
      } else if (current <= 3) {
        return [1, 2, 3, 4, "...", total];
      } else if (current > total - 3) {
        return [1, "...", total - 3, total - 2, total - 1, total];
      } else {
        return [1, "...", current - 1, current, current + 1, "...", total];
      }
    },
  },
  mounted() {
    this.fetchDocuments(1); // Fetch documents where document_type_id = 1 (Travel Order)
  },
  methods: {
    // Fetch documents from the API
    async fetchDocuments(documentTypeId) {
      try {
        const response = await axios.get(`/api/admin/list/documents/${documentTypeId}`);
        this.documents = response.data;
      } catch (error) {
        console.error("Error fetching documents:", error);
      }
    },
    // Set the page to the selected value
    setPage(page) {
      if (page !== "...") {
        this.currentPage = page;
      }
    },
    // Go to the next page
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    // Go to the previous page
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    // Redirect to the document details page
    viewDocument(documentId) {
      this.$router.push(`/user-dashboard/documents/${documentId}`);
    },
  },
};
</script>

<style scoped>
/* Adjust table styling */
table {
  margin-top: 15px;
  width: 100%;
  border-collapse: collapse;
}

th,
td {
  padding: 10px;
  border: 1px solid #ddd;
  text-align: left;
}

th {
  background-color: navy; /* Navy blue background */
  color: white;
}

/* Container for search bar and top bar */
.search-and-top-bar {
  display: flex;
  flex-direction: column;
  gap: 10px; /* Adds space between search bar and counter/pagination */
}

/* Search bar styling */
.search-bar-container {
  align-self: flex-end; /* Align search bar to the right */
  position: relative;
  width: 300px;
}

.search-icon {
  position: absolute;
  left: 10px;
  top: 50%;
  transform: translateY(-50%);
  color: #888;
}

.search-bar {
  width: 100%;
  padding: 8px 12px 8px 30px; /* Add padding for icon */
  font-size: 14px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

/* Top bar container for results and pagination */
.top-bar {
  display: flex;
  justify-content: space-between; /* Space between results counter and pagination */
  align-items: center; /* Align items vertically in the center */
  flex-wrap: nowrap; /* Ensure everything stays on one line */
  gap: 10px; /* Optional: Add some space between counter and pagination */
}

/* Results Counter */
.results-counter {
  font-size: 14px;
  white-space: nowrap; /* Prevent wrapping */
}

/* Pagination styling */
.pagination {
  display: flex;
  align-items: center;
  gap: 5px;
}

.pagination button {
  padding: 8px 12px;
  background-color: #f4f4f4;
  color: #333;
  border: 1px solid #ddd;
  border-radius: 4px;
  cursor: pointer;
  min-width: 36px;
}

.pagination button.active {
  background-color: #007bff;
  color: white;
}

.pagination button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.pagination span {
  padding: 8px 12px;
  color: #888;
}

/* Style clickable rows */
.clickable-row {
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.clickable-row:hover {
  background-color: #f0f0f0;
}
</style>
