<template>
  <div class="container">
    <h2>System Settings</h2>
    <section class="settings-section">
      <h3>Backup & Restore Database and Files</h3>
      <p>Secure your data by backing up or restoring the database and files.</p>
      
      <!-- Backup Section -->
      <div class="form-group">
        <label>Backup Database and Files:</label>
        <button 
          class="btn btn-primary" 
          @click="backupDatabaseAndFiles"
          :disabled="isProcessing"
        >
          {{ isProcessing ? 'Processing...' : 'Backup Now' }}
        </button>
      </div>

      <!-- Restore Section -->
      <div class="form-group">
        <label>Restore Database and Files:</label>
        <input 
          type="file" 
          @change="onFileSelect"
          accept=".sql,.zip"
        />
        <button 
          class="btn btn-secondary mt-2" 
          @click="restoreDatabaseAndFiles" 
          :disabled="!backupFile || isProcessing"
        >
          {{ isProcessing ? 'Processing...' : 'Restore' }}
        </button>
      </div>
    </section>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      backupFile: null, // Selected file for restoration
      isProcessing: false, // Processing state
    };
  },
  methods: {
    async backupDatabaseAndFiles() {
      this.isProcessing = true; // Set processing state
      try {
        const response = await axios.post('/api/admin/backup-database', {}, { responseType: 'blob' });
        
        // Create download link for the backup file
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'database_and_files_backup.zip');
        document.body.appendChild(link);
        link.click();

        alert("Backup completed successfully. Check your downloads.");
      } catch (error) {
        console.error("Error during backup:", error);
        alert("An error occurred while creating the backup. Please try again.");
      } finally {
        this.isProcessing = false; // Reset processing state
      }
    },
    onFileSelect(event) {
      this.backupFile = event.target.files[0]; // Assign selected file
    },
    async restoreDatabaseAndFiles() {
      if (!this.backupFile) {
        alert("Please select a backup file to restore.");
        return;
      }

      this.isProcessing = true; // Set processing state
      try {
        const formData = new FormData();
        formData.append('backup_file', this.backupFile);

        const response = await axios.post('/api/admin/restore-database', formData, {
          headers: { 'Content-Type': 'multipart/form-data' },
        });

        alert("Restore completed successfully.");
      } catch (error) {
        console.error("Error during restore:", error);

        // Display user-friendly error messages
        if (error.response && error.response.data && error.response.data.error) {
          alert(`Restore failed: ${error.response.data.error}`);
        } else {
          alert("An error occurred while restoring the database and files. Please try again.");
        }
      } finally {
        this.isProcessing = false; // Reset processing state
      }
    },
  },
};
</script>

<style scoped>
.container {
  max-width: 1000px;
  margin: 0 auto;
  padding: 20px;
}

.settings-section {
  background-color: #f9f9f9;
  padding: 20px;
  margin-bottom: 20px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h3 {
  color: #333;
}

.form-group {
  margin-bottom: 15px;
}

button {
  margin-top: 10px;
}

.btn-primary {
  background-color: #007bff;
  color: white;
}

.btn-secondary {
  background-color: #6c757d;
  color: white;
}
</style>
