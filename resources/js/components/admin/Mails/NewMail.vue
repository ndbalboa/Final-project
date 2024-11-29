<template>
  <div class="new-mail">
    <h2>Record Mail</h2>
    <div class="form-container">
      <form @submit.prevent="submitForm">
        <!-- Receiver (To) and Sender (From) Fields -->
        <div class="form-group-row">
          <div class="form-group half">
            <label for="to">Receiver</label>
            <select id="to" v-model="form.to" required>
              <option disabled value="">-- Select Employee --</option>
              <option 
                v-for="employee in employees" 
                :key="employee.id" 
                :value="`${employee.firstName} ${employee.lastName}`">
                {{ employee.firstName }} {{ employee.lastName }}
              </option>
            </select>
            <p v-if="employees.length === 0" class="no-employees">No employees available for selection</p>
          </div>

          <div class="form-group half">
            <label for="from">Sender</label>
            <input
              type="text"
              id="from"
              v-model="form.from"
              placeholder="Enter sender's full name or company name"
              required
            />
          </div>
        </div>

        <!-- Description Field -->
        <div class="form-group">
          <label for="description">Description</label>
          <textarea
            id="description"
            v-model="form.description"
            placeholder="Enter keywords that best describe the mail."
            rows="4"
          ></textarea>
        </div>

        <!-- Priority and Status Fields -->
        <div class="form-group-row">
          <div class="form-group half">
            <label for="priority">Priority</label>
            <select id="priority" v-model="form.priority" required>
              <option value="Very High">Very High</option>
              <option value="High">High</option>
              <option value="Normal">Normal</option>
              <option value="Low">Low</option>
              <option value="Very Low">Very Low</option>
            </select>
          </div>

          <div class="form-group half">
            <label for="status">Status</label>
            <select id="status" v-model="form.status" required>
              <option value="undelivered">Undelivered</option>
              <option value="delivered">Delivered</option>
            </select>
          </div>
        </div>

        <!-- Date Received (Optional) -->
        <div class="form-group">
          <label for="date_received">Date Received</label>
          <input
            type="date"
            id="date_received"
            v-model="form.date_received"
          />
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
          <button type="button" @click="clearForm" class="btn secondary">Clear Form</button>
          <button type="submit" class="btn primary">Record Mail</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      form: {
        to: '', 
        from: '', 
        description: '', 
        priority: 'Normal', 
        status: 'undelivered',
        date_received: '', 
      },
      employees: [], 
    };
  },
  mounted() {
    this.loadEmployees();
  },
  methods: {
    loadEmployees() {
      axios.get('/api/admin/employeeselect')
        .then(response => {
          this.employees = response.data;
        })
        .catch(error => {
          console.error('Error loading employees:', error);
        });
    },

    submitForm() {
      axios.post('/api/admin/mails', this.form)
        .then(response => {
          alert(response.data.message);
          this.clearForm();
        })
        .catch(error => {
          alert('There was an error recording the mail.');
          console.error(error);
        });
    },

    clearForm() {
      this.form.to = '';
      this.form.from = '';
      this.form.description = '';
      this.form.priority = 'Normal';
      this.form.status = 'undelivered';
      this.form.date_received = '';
    },
  },
};
</script>

<style scoped>
.new-mail {
  font-family: 'Arial', sans-serif;
  color: #333;
  padding: 20px;
  max-width: 1100px;
  margin: 0 auto;
}
h2 {
  margin-left: 20px;
  margin-bottom: 5px;
  color: #343a40; /* Darker text color for better readability */
}

.form-container {
  background: white;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.form-group {
  margin-bottom: 15px;
}

label {
  font-weight: bold;
  display: block;
  margin-bottom: 5px;
}

input,
select,
textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  box-sizing: border-box;
}

textarea {
  resize: none;
}

input:focus,
select:focus,
textarea:focus {
  border-color: #007bff;
  outline: none;
  box-shadow: 0 0 4px rgba(0, 123, 255, 0.25);
}

.no-employees {
  color: red;
  font-size: 14px;
  margin-top: 5px;
}

/* Flexbox Row for Horizontal Alignment */
.form-group-row {
  display: flex;
  gap: 15px;
  justify-content: space-between;
}

.form-group.half {
  flex: 1;
}

.form-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 20px;
}

.btn {
  padding: 10px 20px;
  font-size: 16px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.btn.primary {
  background-color: #007bff;
  color: white;
}

.btn.secondary {
  background-color: #6c757d;
  color: white;
}

.btn:hover {
  opacity: 0.9;
}

@media (max-width: 600px) {
  .form-group-row {
    flex-direction: column;
  }

  .form-actions {
    flex-direction: column;
  }

  .btn {
    width: 100%;
    margin-bottom: 10px;
  }
}
</style>
