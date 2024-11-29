<template>
  <h2 class="upload-title">Upload Document</h2>
  <div class="container mt-5">
    <div class="upload-card shadow-sm">
      <form @submit.prevent="uploadFile">
        <!-- File Upload Box -->
        <div class="file-upload-box mb-3">
          <p class="file-upload-text">Select File here</p>
          <p class="file-support-text">Files Supported: PDF, DOCX, PNG, JPG, JPEG</p>
          <input
            type="file"
            id="file"
            class="file-input"
            @change="handleFileUpload"
            accept=".pdf,.docx,.png,.jpg,.jpeg"
            required
          />
          <label for="file" class="file-label">Choose File</label>
        </div>

        <!-- Upload Button -->
        <button type="submit" class="btn btn-primary w-100" :disabled="isLoading || !selectedFile">
          {{ isLoading ? 'Scanning Document...' : 'Upload Document' }}
        </button>
      </form>

      <!-- Display progress and status -->
      <div v-if="uploadStatus.length" class="file-status-list">
        <div v-for="(status, index) in uploadStatus" :key="index" class="file-status">
          <p>{{ status.fileName }} - {{ status.progress === 100 ? "Uploaded" : "Scanning..." }}</p>
          <div class="progress-bar">
            <div :style="{ width: status.progress + '%' }"></div>
          </div>
        </div>
      </div>

      <!-- Success and Error Messages -->
      <div v-if="errorMessage" class="error">{{ errorMessage }}</div>
      <div v-if="successMessage" class="success">{{ successMessage }}</div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      selectedFile: null,
      errorMessage: null,
      successMessage: null,
      isLoading: false,
      uploadStatus: [],
    };
  },
  methods: {
    handleFileUpload(event) {
      this.selectedFile = event.target.files[0];
      this.uploadStatus = [];
    },
    async uploadFile() {
      this.clearMessages();
      if (!this.selectedFile) {
        this.errorMessage = "Please select a file.";
        return;
      }

      const formData = new FormData();
      formData.append("file", this.selectedFile);

      // Initialize upload status
      this.uploadStatus.push({
        fileName: this.selectedFile.name,
        progress: 0,
      });

      try {
        this.isLoading = true;

        const response = await axios.post("/api/admin/documents/upload", formData, {
          headers: { "Content-Type": "multipart/form-data" },
          onUploadProgress: (progressEvent) => {
            const progress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
            this.uploadStatus[0].progress = progress;
          },
        });

        if (response.data && response.data.document) {
          this.successMessage = "File uploaded successfully.";
          localStorage.setItem("extractedData", JSON.stringify(response.data.document));
          this.$router.push({ name: "SecPageAutofill" });
        }
      } catch (error) {
        this.errorMessage = error.response?.data?.error || "Failed to upload the file.";
      } finally {
        this.isLoading = false;
      }
    },
    clearMessages() {
      this.errorMessage = null;
      this.successMessage = null;
    },
  },
};
</script>

<style scoped>
.container {
  max-width: 500px;
  margin: auto;
}

.upload-card {
  border-radius: 10px;
  padding: 20px;
  border: 1px solid #ddd;
}

.upload-title {
  font-weight: bold;
  font-size: 2rem;
}

.file-upload-box {
  border: 2px dashed #ccc;
  border-radius: 8px;
  padding: 30px;
  text-align: center;
  position: relative;
}

.file-upload-text {
  font-weight: bold;
  font-size: 1.2rem;
  color: #333;
  margin-bottom: 5px;
}

.file-support-text {
  color: #888;
  font-size: 0.9rem;
}

.file-input {
  opacity: 0;
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  cursor: pointer;
}

.file-label {
  background-color: #007bff;
  color: white;
  padding: 8px 16px;
  border-radius: 5px;
  font-weight: bold;
  display: inline-block;
  cursor: pointer;
  margin-top: 10px;
}

.error {
  color: red;
  margin-top: 10px;
}

.success {
  color: green;
  margin-top: 10px;
}

.loading-bar {
  margin-top: 10px;
  color: blue;
  font-weight: bold;
}

.file-status-list {
  margin-top: 15px;
}

.file-status {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 10px;
}

.progress-bar {
  width: 100%;
  background-color: #f3f3f3;
  border-radius: 5px;
  overflow: hidden;
  height: 8px;
  margin-top: 5px;
}

.progress-bar div {
  height: 100%;
  background-color: #4caf50;
}

button:disabled {
  background-color: #ddd;
  cursor: not-allowed;
}
</style>
