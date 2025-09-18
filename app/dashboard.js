// Dashboard Page Functionality with Chart.js
document.addEventListener("DOMContentLoaded", function () {
  initializeDashboard();
});

let viewsChart = null;
let revenueChart = null;

function initializeDashboard() {
  // Initialize charts
  setTimeout(() => {
    initializeCharts();
  }, 100);

  // Setup dashboard interactions
  setupDashboardInteractions();

  // Load dashboard data
  loadDashboardData();

  // Setup real-time updates (simulation)
  setupRealTimeUpdates();

  console.log("📊 Dashboard initialized successfully!");
}

function initializeCharts() {
  createViewsChart();
  createRevenueChart();
}

function createViewsChart() {
  const canvas = document.getElementById("viewsChart");
  if (!canvas) return;

  const ctx = canvas.getContext("2d");

  // Destroy existing chart if it exists
  if (viewsChart) {
    viewsChart.destroy();
  }

  viewsChart = new Chart(ctx, {
    type: "line",
    data: {
      labels: ["Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim"],
      datasets: [
        {
          label: "Vues des annonces",
          data: [45, 52, 38, 65, 71, 58, 43],
          borderColor: "#f97316",
          backgroundColor: "rgba(249, 115, 22, 0.1)",
          borderWidth: 3,
          fill: true,
          tension: 0.4,
          pointBackgroundColor: "#f97316",
          pointBorderColor: "#ffffff",
          pointBorderWidth: 2,
          pointRadius: 5,
          pointHoverRadius: 7,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        },
        tooltip: {
          backgroundColor: "rgba(0, 0, 0, 0.8)",
          titleColor: "white",
          bodyColor: "white",
          borderColor: "#f97316",
          borderWidth: 1,
          cornerRadius: 8,
          displayColors: false,
          callbacks: {
            title: function (context) {
              return `${context[0].label}`;
            },
            label: function (context) {
              return `${context.parsed.y} vues`;
            },
          },
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            color: "#f3f4f6",
            drawBorder: false,
          },
          ticks: {
            color: "#6b7280",
            font: {
              size: 12,
            },
            padding: 10,
          },
        },
        x: {
          grid: {
            display: false,
          },
          ticks: {
            color: "#6b7280",
            font: {
              size: 12,
            },
            padding: 10,
          },
        },
      },
      elements: {
        point: {
          hoverBackgroundColor: "#ea580c",
        },
      },
      interaction: {
        intersect: false,
        mode: "index",
      },
    },
  });
}

function createRevenueChart() {
  const canvas = document.getElementById("revenueChart");
  if (!canvas) return;

  const ctx = canvas.getContext("2d");

  // Destroy existing chart if it exists
  if (revenueChart) {
    revenueChart.destroy();
  }

  revenueChart = new Chart(ctx, {
    type: "bar",
    data: {
      labels: ["Jan", "Fév", "Mar", "Avr", "Mai", "Jun"],
      datasets: [
        {
          label: "Revenus ($)",
          data: [12500, 15800, 11200, 18900, 16400, 21200],
          backgroundColor: "rgba(249, 115, 22, 0.8)",
          borderColor: "#f97316",
          borderWidth: 1,
          borderRadius: 4,
          borderSkipped: false,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        },
        tooltip: {
          backgroundColor: "rgba(0, 0, 0, 0.8)",
          titleColor: "white",
          bodyColor: "white",
          borderColor: "#f97316",
          borderWidth: 1,
          cornerRadius: 8,
          displayColors: false,
          callbacks: {
            title: function (context) {
              return `${context[0].label} 2024`;
            },
            label: function (context) {
              return `$${context.parsed.y.toLocaleString()}`;
            },
          },
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            color: "#f3f4f6",
            drawBorder: false,
          },
          ticks: {
            color: "#6b7280",
            font: {
              size: 12,
            },
            padding: 10,
            callback: function (value) {
              return "$" + value.toLocaleString();
            },
          },
        },
        x: {
          grid: {
            display: false,
          },
          ticks: {
            color: "#6b7280",
            font: {
              size: 12,
            },
            padding: 10,
          },
        },
      },
    },
  });
}

function setupDashboardInteractions() {
  // Setup request actions
  setupRequestActions();

  // Setup quick actions
  setupQuickActions();

  // Setup property management
  setupPropertyManagement();

  // Setup stats animations
  animateStats();
}

function setupRequestActions() {
  const requestItems = document.querySelectorAll(".request-item");

  requestItems.forEach((item) => {
    const acceptBtn = item.querySelector(".btn-primary");
    const refuseBtn = item.querySelector(".btn-outline");

    if (acceptBtn) {
      acceptBtn.addEventListener("click", function (e) {
        e.stopPropagation();
        handleRequestAction(item, "accept");
      });
    }

    if (refuseBtn) {
      refuseBtn.addEventListener("click", function (e) {
        e.stopPropagation();
        handleRequestAction(item, "refuse");
      });
    }
  });
}

function handleRequestAction(requestItem, action) {
  const requestInfo = requestItem.querySelector(".request-info h4").textContent;

  if (action === "accept") {
    showNotification(`Demande "${requestInfo}" acceptée!`, "success");
    requestItem.style.opacity = "0.5";
    requestItem.style.backgroundColor = "#f0fdf4";
  } else {
    showNotification(`Demande "${requestInfo}" refusée`, "info");
    requestItem.style.opacity = "0.5";
    requestItem.style.backgroundColor = "#fef2f2";
  }

  // Disable buttons
  const buttons = requestItem.querySelectorAll("button");
  buttons.forEach((btn) => (btn.disabled = true));

  // Remove item after animation
  setTimeout(() => {
    requestItem.style.transform = "translateX(-100%)";
    setTimeout(() => {
      if (requestItem.parentNode) {
        requestItem.parentNode.removeChild(requestItem);
      }
    }, 300);
  }, 2000);
}

function setupQuickActions() {
  const quickActionBtns = document.querySelectorAll(".quick-action-btn");

  quickActionBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      const actionText = this.querySelector("span").textContent;
      showNotification(`Action: ${actionText}`, "info");

      // Add visual feedback
      this.style.transform = "scale(0.95)";
      setTimeout(() => {
        this.style.transform = "scale(1)";
      }, 150);
    });
  });
}

function setupPropertyManagement() {
  const propertyItems = document.querySelectorAll(".property-item");

  propertyItems.forEach((item) => {
    const viewBtn = item.querySelector(".btn");

    if (viewBtn) {
      viewBtn.addEventListener("click", function (e) {
        e.stopPropagation();
        const propertyTitle = item.querySelector("h4").textContent;
        showNotification(`Ouverture: ${propertyTitle}`, "info");
      });
    }
  });
}

function animateStats() {
  const statValues = document.querySelectorAll(".stat-value");

  statValues.forEach((stat) => {
    const finalValue = stat.textContent;
    const isNumber = !isNaN(parseFloat(finalValue));

    if (isNumber) {
      const target = parseFloat(finalValue);
      let current = 0;
      const increment = target / 20;
      const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
          current = target;
          clearInterval(timer);
        }

        if (finalValue.includes("%")) {
          stat.textContent = current.toFixed(1) + "%";
        } else if (finalValue.includes(",")) {
          stat.textContent = Math.round(current).toLocaleString();
        } else {
          stat.textContent = Math.round(current);
        }
      }, 50);
    }
  });
}

function loadDashboardData() {
  // Simulate loading dashboard data
  const loadingStates = document.querySelectorAll(".stat-value");

  loadingStates.forEach((stat) => {
    stat.style.opacity = "0.5";
  });

  setTimeout(() => {
    loadingStates.forEach((stat) => {
      stat.style.opacity = "1";
      stat.style.transition = "opacity 0.3s ease";
    });

    showNotification("Données du dashboard mises à jour", "success");
  }, 1000);
}

function setupRealTimeUpdates() {
  // Simulate real-time updates every 30 seconds
  setInterval(() => {
    updateViewsChart();
    updateStats();
  }, 30000);
}

function updateViewsChart() {
  if (!viewsChart) return;

  // Generate new random data
  const newData = viewsChart.data.datasets[0].data.map(
    () => Math.floor(Math.random() * 50) + 30,
  );

  viewsChart.data.datasets[0].data = newData;
  viewsChart.update("none");
}

function updateStats() {
  // Simulate small changes in stats
  const statCards = document.querySelectorAll(".stat-card .stat-value");

  statCards.forEach((stat, index) => {
    const currentValue = parseInt(stat.textContent.replace(/[^\d]/g, ""));
    if (!isNaN(currentValue)) {
      const change = Math.floor(Math.random() * 10) - 5; // -5 to +5
      const newValue = Math.max(0, currentValue + change);

      // Animate the change
      stat.style.transform = "scale(1.1)";
      stat.style.color =
        change > 0 ? "#10b981" : change < 0 ? "#ef4444" : "#1f2937";

      setTimeout(() => {
        if (stat.textContent.includes(",")) {
          stat.textContent = newValue.toLocaleString();
        } else {
          stat.textContent = newValue;
        }

        setTimeout(() => {
          stat.style.transform = "scale(1)";
          stat.style.color = "#1f2937";
        }, 300);
      }, 200);
    }
  });
}

// Export functions for debugging
window.dashboard = {
  updateViewsChart,
  updateStats,
  handleRequestAction,
  createViewsChart,
  createRevenueChart,
};

// Handle window resize
window.addEventListener(
  "resize",
  debounce(() => {
    if (viewsChart) viewsChart.resize();
    if (revenueChart) revenueChart.resize();
  }, 250),
);

// Initialize Chart.js defaults
Chart.defaults.font.family = "Inter";
Chart.defaults.color = "#6b7280";
Chart.defaults.font.size = 12;
