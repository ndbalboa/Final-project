<template>
    <h2>System Settings</h2>
    <div class="container">
  
      <!-- 
     <section class="settings-section">
        <h3>Two-Factor Authentication</h3>
        <p>Enhance account security by enabling Two-Factor Authentication (2FA).</p>
        <div class="form-group">
          <label>Enable 2FA:</label>
          <button @click="toggleTwoFactorAuth" :class="{'btn-success': twoFactorEnabled, 'btn-danger': !twoFactorEnabled}">
            {{ twoFactorEnabled ? 'Disable' : 'Enable' }}
          </button>
        </div>
        <div v-if="twoFactorEnabled" class="alert alert-info">
          Two-Factor Authentication is enabled. Please use an authenticator app for your login code.
        </div>
      </section> -->
  
      <!-- Backup & Restore Section -->
      <section class="settings-section">
        <h3>Backup & Restore Database</h3>
        <p>Secure your data by backing up or restoring the database.</p>
        <div class="form-group">
          <label>Backup Database:</label>
          <button class="btn btn-primary" @click="backupDatabase">Backup Now</button>
        </div>
        <div class="form-group">
          <label>Restore Database:</label>
          <button class="btn btn-secondary mt-2" @click="restoreDatabase" :disabled="!backupFile">Restore</button>
        </div>
      </section>
  
      <!-- About Us Section -->
      <section class="settings-section">
        <h3>About Us</h3>
        <p>Learn more about our system and the team behind it.</p>
        <div class="about-content">
          <p>Our system is dedicated to providing secure and efficient solutions for managing and backing up your data. We value your trust and strive to keep your information safe.</p>
          <p>For further details, visit our website or contact us at support@TeamPotchi.com.</p>
        </div>
      </section>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        twoFactorEnabled: false,
        backupFile: null,
      };
    },
    methods: {
      toggleTwoFactorAuth() {
        this.twoFactorEnabled = !this.twoFactorEnabled;
        alert(`Two-Factor Authentication has been ${this.twoFactorEnabled ? 'enabled' : 'disabled'}.`);
      },
      async backupDatabase() {
        try {
          const response = await axios.post('/api/admin/backup-database', {}, { responseType: 'blob' });
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'database_backup.sql');
          document.body.appendChild(link);
          link.click();
          alert("Database backup was successful. Check your downloads.");
        } catch (error) {
          console.error("Error during backup:", error);
          alert("An error occurred while creating the backup.");
        }
      },
      onFileSelect(event) {
        this.backupFile = event.target.files[0];
      },
      async restoreDatabase() {
        if (!this.backupFile) return alert("Please select a backup file to restore.");
  
        try {
          const formData = new FormData();
          formData.append('backup_file', this.backupFile);
  
          await axios.post('/api/admin/restore-database', formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
          });
  
          alert("Database restore was successful.");
        } catch (error) {
          console.error("Error during restore:", error);
          alert("An error occurred while restoring the database.");
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
  
  h2 {
    text-align: left;
    margin-bottom: 20px;
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
  
  .btn-success {
    background-color: #28a745;
    color: white;
  }
  
  .btn-danger {
    background-color: #dc3545;
    color: white;
  }
  
  .about-content p {
    margin: 0 0 10px;
  }
  </style>
  