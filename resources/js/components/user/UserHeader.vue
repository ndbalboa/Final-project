<template>
  <header class="d-flex justify-content-between align-items-center p-3 bg-custom border-bottom-custom">
    <h1 class="h5 mb-0 text-white">Optimized Document Categorization </h1>
    <div class="clock text-white">{{ currentTime }}</div>
  </header>
</template>

<script>
export default {
  name: 'Header',
  data() {
    return {
      currentTime: this.formatTime(new Date())
    };
  },
  created() {
    this.updateTime();
    setInterval(this.updateTime, 1000);
  },
  methods: {
    updateTime() {
      this.currentTime = this.formatTime(new Date());
    },
    formatTime(date) {
      const options = {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
        hour: '2-digit', minute: '2-digit', second: '2-digit'
      };
      return date.toLocaleDateString('en-US', options);
    }
  }
};
</script>

<style scoped>
.bg-custom {
  background-color: #002f6c; /* Navy blue */
  border-bottom: 4px solid #ffd700; /* Gold */
}

.border-bottom-custom {
  border-bottom: 4px solid #ffd700; /* Gold */
}

header {
  height: 80px; /* Adjust height as needed */
}

h1 {
  font-size: 1.25rem; /* Slightly larger font size for the header */
  font-weight: 600; /* Slightly bolder font weight */
  margin-bottom: 0; /* Remove margin-bottom */
}

.clock {
  font-size: 0.875rem; /* Font size for the clock */
  font-weight: 400; /* Normal font weight */
  background-color: rgba(0, 0, 0, 0.3); /* Slightly transparent background for better readability */
  padding: 5px 10px; /* Add padding around the time */
  border-radius: 5px; /* Rounded corners */
}
</style>
