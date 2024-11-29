<template>
  <header class="d-flex justify-content-between align-items-center p-3 bg-custom border-bottom-custom">
    <div class="d-flex align-items-center">
      <img src="/public/assets/lnulogo.jpg" alt="Logo" class="logo-image" />
      <h1 class="h5 mb-0 text-white ms-2">
        LEYTE NORMAL UNIVERSITY Records Office
      </h1>
    </div>
    <div class="clock-box">
      <div class="clock">{{ currentDate }}<br />{{ currentTime }}</div>
    </div>
  </header>
</template>

<script>
export default {
  name: 'Header',
  data() {
    return {
      currentDate: this.formatDate(new Date()),
      currentTime: this.formatTime(new Date())
    };
  },
  created() {
    this.updateClock();
    setInterval(this.updateClock, 1000);
  },
  methods: {
    updateClock() {
      const now = new Date();
      this.currentDate = this.formatDate(now);
      this.currentTime = this.formatTime(now);
    },
    formatDate(date) {
      return date.toISOString().split('T')[0] + ' ' + date.toLocaleDateString('en-US', { weekday: 'short' }).toUpperCase();
    },
    formatTime(date) {
      return date.toLocaleTimeString('en-US', { hour12: true });
    }
  }
};
</script>

<style scoped>
.bg-custom {
  background-color: #002f6c; 
  border-bottom: 4px solid #ffd700;
}

.border-bottom-custom {
  border-bottom: 4px solid #ffd700;
}

header {
  height: 110px;
}

.logo-image {
  width: 90px; 
  height: auto;
  margin-right: 10px; 
  border-radius: 100%;
}

h1 {
  margin-left: 20px;
  font-size: 1.5rem;
  font-weight: bold;
  font-family: 'Georgia', serif; /* Serif font similar to the example */
  letter-spacing: 3px; /* Adds space between characters for a stretched look */
  text-transform: uppercase; /* Ensures text is in all uppercase */
}

.subtext {
  display: block;
  font-size: 1rem;
  font-weight: bold;
  font-family: 'Georgia', serif; /* Match font with main heading */
  margin-top: 5px;
  margin-left: 300px; /* Adjust margin for alignment */
  letter-spacing: 1px; /* Adds space between characters for a similar look */
}
.clock-box {
  border: 1px solid #ffffff;
  padding: 5px 10px;
  border-radius: 5px;
  margin-right: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.clock {
  font-family: 'Courier New', Courier, monospace;
  font-size: 1.5rem;
  color: #ffffff;
  text-align: center;
}
</style>
