<template>
  <div>
    <!-- Add Button -->
    <button
      type="button"
      class="btn btn-success btn-circle"
      @click="openModal"
      data-bs-toggle="modal"
      data-bs-target="#addAcademicRankModal"
    >
      <i class="bi bi-plus"></i> <!-- Bootstrap Icon for Plus -->
    </button>

    <!-- Modal for Adding/Editing Academic Rank -->
    <div class="modal fade" id="addAcademicRankModal" tabindex="-1" aria-labelledby="addAcademicRankModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addAcademicRankModalLabel">Add/Update Academic Rank</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitForm">
              <div class="form-group">
                <label for="rank">Rank</label>
                <input
                  type="text"
                  id="rank"
                  v-model="rank"
                  class="form-control"
                  placeholder="Enter Academic Rank"
                  required
                />
              </div>
              <button type="submit" class="btn btn-primary mt-3">
                {{ editingRank ? "Update Rank" : "Add Rank" }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Alerts -->
    <div v-if="errorMessage" class="alert alert-danger mt-3">
      {{ errorMessage }}
    </div>
    <div v-if="successMessage" class="alert alert-success mt-3">
      {{ successMessage }}
    </div>

    <!-- Academic Ranks Table -->
    <h3 class="mt-5">Academic Ranks</h3>
    <table class="table table-striped">
      <thead>
        <tr class="bg-primary text-white">
          <th>Rank</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="rankItem in academicRanks" :key="rankItem.id">
          <td>{{ rankItem.rank }}</td>
          <td>
            <button
              @click="editAcademicRank(rankItem)"
              class="btn btn-warning btn-sm"
            >
              <i class="bi bi-pencil"></i> Edit
            </button>
            <button
              @click="deleteAcademicRank(rankItem.id)"
              class="btn btn-danger btn-sm ml-2"
            >
              <i class="bi bi-trash"></i> Delete
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
      rank: "",
      academicRanks: [],
      successMessage: "",
      errorMessage: "",
      editingRank: null,
    };
  },
  methods: {
    openModal() {
      this.rank = "";
      this.editingRank = null;
      this.successMessage = "";
      this.errorMessage = "";
    },
    async submitForm() {
      try {
        if (this.editingRank) {
          // If editing, send an update request
          await this.updateAcademicRank();
        } else {
          // If adding new, send a create request
          await this.createAcademicRank();
        }
        this.successMessage = this.editingRank
          ? "Academic rank updated successfully!"
          : "Academic rank added successfully!";
        this.rank = ""; // Reset form field
        this.editingRank = null; // Reset the editing state
        this.fetchAcademicRanks(); // Fetch the updated list of academic ranks

        // Close the modal after success
        const modal = new bootstrap.Modal(document.getElementById("addAcademicRankModal"));
        modal.hide(); // This will close the modal
      } catch (error) {
        this.errorMessage = "Failed to process academic rank. Please try again.";
      }
    },
    async createAcademicRank() {
      await axios.post("/api/admin/store/academic-ranks", {
        rank: this.rank,
      });
    },
    async updateAcademicRank() {
      await axios.put(`/api/admin/update/academic-ranks/${this.editingRank.id}`, {
        rank: this.rank,
      });
    },
    async deleteAcademicRank(id) {
      if (confirm("Are you sure you want to delete this academic rank?")) {
        try {
          await axios.delete(`/api/admin/delete/academic-ranks/${id}`);
          this.successMessage = "Academic rank deleted successfully!";
          this.fetchAcademicRanks();
        } catch (error) {
          this.errorMessage = "Failed to delete academic rank.";
        }
      }
    },
    async fetchAcademicRanks() {
      try {
        const response = await axios.get("/api/admin/academic-ranks");
        this.academicRanks = response.data.academicRanks;
      } catch (error) {
        this.errorMessage = "Failed to load academic ranks.";
      }
    },
    editAcademicRank(rankItem) {
      this.editingRank = rankItem;
      this.rank = rankItem.rank;

      const modal = new bootstrap.Modal(document.getElementById("addAcademicRankModal"));
      modal.show();
    },
  },
  mounted() {
    this.fetchAcademicRanks();
  },
};
</script>

<style scoped>
.form-group {
  margin-bottom: 1rem;
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
