<template>
  <div>
    <!-- Add Button -->
    <button
      type="button"
      class="btn btn-success btn-circle"
      @click="openModal"
      data-bs-toggle="modal"
      data-bs-target="#addUniversityPositionModal"
    >
      <i class="bi bi-plus"></i> <!-- Bootstrap Icon for Plus -->
    </button>

    <!-- Modal for Adding/Editing University Position -->
    <div class="modal fade" id="addUniversityPositionModal" tabindex="-1" aria-labelledby="addUniversityPositionModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addUniversityPositionModalLabel">Add/Update University Position</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitForm">
              <div class="form-group">
                <label for="position">Position</label>
                <input
                  type="text"
                  id="position"
                  v-model="position"
                  class="form-control"
                  placeholder="Enter University Position"
                  required
                />
              </div>
              <button type="submit" class="btn btn-primary mt-3">
                {{ editingPosition ? "Update Position" : "Add Position" }}
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

    <!-- University Positions Table -->
    <h3 class="mt-5">University Positions</h3>
    <table class="table table-striped">
      <thead>
        <tr class="bg-primary text-white">
          <th>Position</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="positionItem in universityPositions" :key="positionItem.id">
          <td>{{ positionItem.position }}</td>
          <td>
            <button
              @click="editUniversityPosition(positionItem)"
              class="btn btn-warning btn-sm"
            >
              <i class="bi bi-pencil"></i> Edit
            </button>
            <button
              @click="deleteUniversityPosition(positionItem.id)"
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
      position: "",
      universityPositions: [],
      successMessage: "",
      errorMessage: "",
      editingPosition: null,
    };
  },
  methods: {
    openModal() {
      this.position = "";
      this.editingPosition = null;
      this.successMessage = "";
      this.errorMessage = "";
    },
    async submitForm() {
      try {
        if (this.editingPosition) {
          // If editing, send an update request
          await this.updateUniversityPosition();
        } else {
          // If adding new, send a create request
          await this.createUniversityPosition();
        }
        this.successMessage = this.editingPosition
          ? "University position updated successfully!"
          : "University position added successfully!";
        
        this.position = ""; // Reset form field
        this.editingPosition = null; // Reset the editing state
        this.fetchUniversityPositions(); // Fetch the updated list of university positions

        // Close the modal after success
        const modal = new bootstrap.Modal(document.getElementById('addUniversityPositionModal'));
        modal.hide(); // This will close the modal

      } catch (error) {
        this.errorMessage = "Failed to process university position. Please try again.";
      }
    },
    async createUniversityPosition() {
      await axios.post("/api/admin/store/university-positions", {
        position: this.position, // Updated to use 'position'
      });
    },
    async updateUniversityPosition() {
      await axios.put(`/api/admin/update/university-positions/${this.editingPosition.id}`, {
        position: this.position, // Updated to use 'position'
      });
    },
    async deleteUniversityPosition(id) {
      if (confirm("Are you sure you want to delete this university position?")) {
        try {
          await axios.delete(`/api/admin/delete/university-positions/${id}`);
          this.successMessage = "University position deleted successfully!";
          this.fetchUniversityPositions();
        } catch (error) {
          this.errorMessage = "Failed to delete university position.";
        }
      }
    },
    async fetchUniversityPositions() {
      try {
        const response = await axios.get("/api/admin/university-positions");
        this.universityPositions = response.data.universityPositions;
      } catch (error) {
        this.errorMessage = "Failed to load university positions.";
      }
    },
    editUniversityPosition(positionItem) {
      this.editingPosition = positionItem;
      this.position = positionItem.position; // Updated to use 'position'

      const modal = new bootstrap.Modal(document.getElementById("addUniversityPositionModal"));
      modal.show();
    },
  },
  mounted() {
    this.fetchUniversityPositions();
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
