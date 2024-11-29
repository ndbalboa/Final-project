<template>
  <div class="sidebar p-3">
    <ul class="nav flex-column">
      <li class="nav-item">
        <router-link class="nav-link" to="/secretary-dashboard">
          <i class="bi bi-house-door-fill me-2"></i> Dashboard
        </router-link>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" to="/secretary-dashboard/scan-document">
          <i class="bi bi-printer me-2"></i> Scan Document
        </router-link>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" to="/secretary-dashboard/upload-document">
          <i class="bi bi-upload me-2"></i> Upload Document
        </router-link>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" to="/secretary-dashboard/search-document">
          <i class="bi bi-search me-2"></i> Search Document
        </router-link>
      </li>
      <li class="nav-item" @click.prevent="toggleDocumentsSubMenu">
        <a class="nav-link d-flex align-items-center">
          <i class="bi bi-folder2-open me-2"></i>
          <span>Documents</span>
          <i :class="['bi', isDocumentsSubMenuOpen ? 'bi-caret-down-fill' : 'bi-caret-left-fill', 'ms-auto']"></i>
        </a>
        <transition name="slide-fade">
          <ul v-show="isDocumentsSubMenuOpen" class="nav flex-column ms-3 submenu">
            <li class="nav-item">
              <router-link class="nav-link" to="/secretary-dashboard/documents/travel-order">
                <i class="bi bi-file-earmark-text me-2"></i> Travel Order
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/secretary-dashboard/documents/office-order">
                <i class="bi bi-file-earmark-text me-2"></i> Office Order
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/secretary-dashboard/documents/special-order">
                <i class="bi bi-file-earmark-text me-2"></i> Special Order
              </router-link>
            </li>
          </ul>
        </transition>
      </li>
      <li class="nav-item" @click.prevent="toggleEmployeesSubMenu">
        <a class="nav-link d-flex align-items-center">
          <i class="bi bi-people-fill me-2"></i>
          <span>Employees</span>
          <i :class="['bi', isEmployeesSubMenuOpen ? 'bi-caret-down-fill' : 'bi-caret-left-fill', 'ms-auto']"></i>
        </a>
        <transition name="slide-fade">
          <ul v-show="isEmployeesSubMenuOpen" class="nav flex-column ms-3 submenu">
            <li class="nav-item">
              <router-link class="nav-link" to="/secretary-dashboard/employee/add">
                <i class="bi bi-person-plus-fill me-2"></i> Add New Employee
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/secretary-dashboard/employee/list">
                <i class="bi bi-list-ul me-2"></i> List of Employees
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/secretary-dashboard/employee/deactivated">
                <i class="bi bi-person-dash-fill me-2"></i> Deactivated Employees
              </router-link>
            </li>
          </ul>
        </transition>
      </li>
      <li class="nav-item" @click.prevent="toggleMailsSubMenu">
  <a class="nav-link d-flex align-items-center">
    <i class="bi bi-folder2-open me-2"></i>
    <span>Mails</span>
    <i :class="['bi', isMailsSubMenuOpen ? 'bi-caret-down-fill' : 'bi-caret-left-fill', 'ms-auto']"></i>
  </a>

    <transition name="slide-fade">
      <ul v-show="isMailsSubMenuOpen" class="nav flex-column ms-3 submenu">
        <li class="nav-item">
          <router-link class="nav-link" to="/secretary-dashboard/mail/new">
            <i class="bi bi-file-earmark-text me-2"></i> Record Mail
          </router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" to="/secretary-dashboard/mail/list">
            <i class="bi bi-file-earmark-text me-2"></i> Mails List 
          </router-link>
        </li>
      </ul>
    </transition>
    </li>
    <li class="nav-item" @click.prevent="toggleReportsSubMenu">
        <a class="nav-link d-flex align-items-center">
          <i class="bi bi-file-earmark-bar-graph-fill"></i>
          <span>Reports</span>
          <i :class="['bi', isReportsSubMenuOpen ? 'bi-caret-down-fill' : 'bi-caret-left-fill', 'ms-auto']"></i>
        </a>
        <transition name="slide-fade">
          <ul v-show="isReportsSubMenuOpen" class="nav flex-column ms-3 submenu">
            <li class="nav-item">
              <router-link class="nav-link" to="/secretary-dashboard/generatedReports">
               Generated Report
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/secretary-dashboard/generateTravelReports">
                 Travel Order
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/secretary-dashboard/officeOrderReports">
                 Office Order
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/secretary-dashboard/generateSpecialReports">
                 Special Order
              </router-link>
            </li>
          </ul>
        </transition>
      </li>
      <li class="nav-item" @click.prevent="toggleSettingsSubMenu">
        <a class="nav-link d-flex align-items-center">
          <i class="bi bi-gear-fill me-2"></i>
          <span>System Settings</span>
          <i :class="['bi', isSettingsSubMenuOpen ? 'bi-caret-down-fill' : 'bi-caret-left-fill', 'ms-auto']"></i>
        </a>
        <transition name="slide-fade">
          <ul v-show="isSettingsSubMenuOpen" class="nav flex-column ms-3 submenu">
            <li class="nav-item">
              <router-link class="nav-link" to="secretary-dashboard/adddocumenttype">
                Document Types
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="">
                University Position
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="">
                Academic Rank
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="secretary-dashboard/settings">
                Settings
              </router-link>
            </li>
          </ul>
        </transition>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link" @click.prevent="confirmLogout">
          <i class="fas fa-sign-out-alt me-2"></i> Logout
        </a>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'SecretarySidebar',
  data() {
    return {
      isDocumentsSubMenuOpen: false,
      isEmployeesSubMenuOpen: false,
      isSettingsSubMenuOpen: false,
      isMailsSubMenuOpen:false,
      isReportsSubMenuOpen:false,
    };
  },
  methods: {
    toggleDocumentsSubMenu() {
      this.isDocumentsSubMenuOpen = !this.isDocumentsSubMenuOpen;
    },
    toggleEmployeesSubMenu() {
      this.isEmployeesSubMenuOpen = !this.isEmployeesSubMenuOpen;
    },
    toggleSettingsSubMenu() {
      this.isSettingsSubMenuOpen = !this.isSettingsSubMenuOpen;
    },
    toggleMailsSubMenu() {
      this.isMailsSubMenuOpen = !this.isMailsSubMenuOpen;
    },
    toggleReportsSubMenu() {
      this.isReportsSubMenuOpen = !this.isReportsSubMenuOpen;
    },
    async confirmLogout() {
  const confirmed = confirm('Are you sure you want to logout?');
  if (confirmed) {
    try {
      await axios.post('/api/logout', {}, { withCredentials: true });
      this.$router.push('/');
    } catch (error) {
      console.error('Logout failed:', error.response ? error.response.data : error.message);
      alert('Logout failed: ' + (error.response ? error.response.data.message : 'Server error'));
    }
  }
},

  },
  mounted() {
    // Optional: Set the Axios base URL for all requests
    axios.defaults.baseURL = 'http://127.0.0.1:8000'; // Adjust if necessary
  }
};
</script>

<style scoped>
.sidebar {
  width: 250px;
}
.nav-link {
  display: block;
  padding: 10px 15px;
  text-decoration: none;
  color: inherit;
  transition: background-color 0.3s;
}
.nav-link:hover {
  background-color: #f0f0f0;
}
.nav-link .bi {
  font-size: 1.5rem;
}
.submenu {
  overflow: hidden;
}
.slide-fade-enter-active, .slide-fade-leave-active {
  transition: all 0.3s ease;
}
.slide-fade-enter, .slide-fade-leave-to {
  max-height: 0;
  opacity: 0;
}
</style>
