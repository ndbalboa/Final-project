<template>
  <div class="department-accounts">
    <h2>Office Accounts</h2>
    <p>Results 1-{{ users.length }} out of {{ users.length }} | Page 1 of 1</p>

    <table class="accounts-table">
      <thead>
        <tr>
          <th>Username</th>
          <th>Office Name</th>
          <th>Name</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.username }}</td>
          <td>{{ user.department }}</td>
          <td>{{ user.firstName }} {{ user.lastName }}</td>
          <td class="actions">
            <button class="btn btn-view" @click="openModal(user)">
              <i class="fas fa-eye"></i> View Details
            </button>
            <button class="btn btn-delete" @click="deleteAccount(user.id)">
              <i class="fas fa-trash-alt"></i> Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Modal for viewing user details -->
    <div v-if="showModal" class="modal-overlay" @click="closeModal">
      <div class="modal-content" @click.stop>
        <h3>User Details</h3>
        <p><strong>Username:</strong> {{ selectedUser.username }}</p>
        <p><strong>Department:</strong> {{ selectedUser.department }}</p>
        <p><strong>Name:</strong> {{ selectedUser.firstName }} {{ selectedUser.lastName }}</p>
        <p><strong>Email:</strong> {{ selectedUser.email }}</p>
        <p><strong>Role:</strong> {{ selectedUser.role }}</p>
        <p><strong>Status:</strong> {{ selectedUser.status }}</p>
        
        <!-- Display Password -->
        <p><strong>Password:</strong> {{ selectedUser.password ? '********' : 'No password set' }}</p>
        
        <div class="modal-actions">
          <button class="btn btn-close" @click="closeModal">
            <i class="fas fa-times"></i> Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      users: [], // List of department users to display
      selectedUser: null, // For viewing detailed user info
      showModal: false, // Control modal visibility
    };
  },
  methods: {
    async fetchUsers() {
      try {
        const response = await axios.get('/api/admin/accounts/departments', {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        console.log('Fetched department accounts:', response.data);
        this.users = response.data;
      } catch (error) {
        console.error('Error fetching department accounts:', error);
        alert('Error fetching department accounts');
      }
    },
    async openModal(user) {
      try {
        const response = await axios.get(`/api/admin/accounts/department-accounts/${user.id}`, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          }
        });
        this.selectedUser = response.data;
        this.showModal = true;
      } catch (error) {
        console.error('Error fetching user details:', error);
        alert('Error fetching user details');
      }
    },
    closeModal() {
      this.showModal = false;
      this.selectedUser = null;
    },
    async deleteAccount(userId) {
      const confirmation = confirm('Are you sure you want to delete this account?');
      if (confirmation) {
        try {
          const response = await axios.delete(`/api/admin/accounts/department-accounts/${userId}`, {
            headers: {
              'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
          });
          console.log('Account deleted:', response.data);

          // Remove the deleted account from the local users array
          this.users = this.users.filter(user => user.id !== userId);
          alert('Account deleted successfully.');
        } catch (error) {
          console.error('Error deleting account:', error);
          alert('An error occurred while deleting the account.');
        }
      }
    },
  },
  mounted() {
    this.fetchUsers(); // Fetch users when the component is mounted
  },
};
</script>

<style scoped>
.department-accounts {
  max-width: 900px;
  margin: 0 auto;
  padding: 20px;
}

.department-accounts h2 {
  font-size: 24px;
  font-weight: bold;
}

.accounts-table {
  width: 100%;
  border-collapse: collapse;
}

.accounts-table th,
.accounts-table td {
  padding: 10px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.accounts-table th {
  background-color: #003366;
  color: white;
}

.actions {
  display: flex;
  gap: 10px;
}

.btn {
  padding: 5px 10px;
  font-size: 12px;
  border: none;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 5px;
  white-space: nowrap;
}

.btn-view {
  background-color: #4CAF50;
  color: white;
}

.btn-view:hover {
  background-color: #45a049;
}

.btn-delete {
  background-color: #f44336;
  color: white;
}

.btn-delete:hover {
  background-color: #e53935;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background-color: white;
  padding: 20px;
  border-radius: 5px;
  width: 500px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

.modal-actions {
  margin-top: 15px;
  display: flex;
  justify-content: flex-end;
}

.btn-close {
  background-color: #007bff;
  color: white;
}

.btn-close:hover {
  background-color: #0056b3;
}

i {
  font-size: 14px;
}
</style>
