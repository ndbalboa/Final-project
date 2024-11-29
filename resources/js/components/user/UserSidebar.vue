<template>
  <div class="sidebar p-3">
    <ul class="nav flex-column">
      <li class="nav-item">
        <router-link class="nav-link" to="/user-dashboard">
          <i class="bi bi-house-door-fill me-2"></i> Dashboard
        </router-link>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" to="/user-dashboard/search-document">
          <i class="bi bi-search me-2"></i> Search Document
        </router-link>
      </li>
      <li class="nav-item" @click.prevent="toggleSubMenu('documents')">
        <a class="nav-link d-flex align-items-center" :aria-expanded="isSubMenuOpen.documents">
          <i class="bi bi-folder2-open me-2"></i>
          <span>Documents</span>
          <i :class="['bi', isSubMenuOpen.documents ? 'bi-caret-down-fill' : 'bi-caret-left-fill', 'ms-auto']"></i>
        </a>
        <transition name="slide-fade">
          <ul v-show="isSubMenuOpen.documents" class="nav flex-column ms-3 submenu">
            <li class="nav-item">
              <router-link class="nav-link" to="/user-dashboard/documents/travel-order">
                <i class="bi bi-file-earmark-text me-2"></i> Travel Order
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/user-dashboard/documents/special-order">
                <i class="bi bi-file-earmark-text me-2"></i> Special Order
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/user-dashboard/documents/office-order">
                <i class="bi bi-file-earmark-text me-2"></i> Office Order
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/user-dashboard/documents/others">
                <i class="bi bi-file-earmark-text me-2"></i> Others
              </router-link>
            </li>
          </ul>
        </transition>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" to="/user-dashboard/user-profile">
          <i class="bi bi-person-fill me-2"></i> User Profile
        </router-link>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" to="/user-dashboard/mail">
          <i class="bi bi-envelope-fill me-2"></i> Mail
        </router-link>
      </li>
      <li class="nav-item" @click.prevent="toggleSubMenu('settings')">
        <a class="nav-link d-flex align-items-center" :aria-expanded="isSubMenuOpen.settings">
          <i class="bi bi-gear-fill me-2"></i>
          <span>Settings</span>
          <i :class="['bi', isSubMenuOpen.settings ? 'bi-caret-down-fill' : 'bi-caret-left-fill', 'ms-auto']"></i>
        </a>
        <transition name="slide-fade">
          <ul v-show="isSubMenuOpen.settings" class="nav flex-column ms-3 submenu">
            <li class="nav-item">
              <router-link class="nav-link" to="/user-dashboard/settings/change-credentials">
                Change Credentials
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
  name: 'UserSidebar',
  data() {
    return {
      isSubMenuOpen: {
        documents: false,
        settings: false,
      },
    };
  },
  methods: {
    toggleSubMenu(menu) {
      this.isSubMenuOpen[menu] = !this.isSubMenuOpen[menu];
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
