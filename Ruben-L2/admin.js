// Admin Page Functionality with Chart.js
document.addEventListener("DOMContentLoaded", function () {
  initializeAdminPanel();
});

let usersChart = null;
let growthChart = null;

function initializeAdminPanel() {
  // Initialize charts
  setTimeout(() => {
    initializeCharts();
  }, 100);

  // Setup admin interactions
  setupAdminInteractions();

  // Load admin data
  loadAdminData();

  // Setup moderation actions
  setupModerationActions();

  // Setup real-time monitoring
  setupRealTimeMonitoring();

  console.log("🔒 Admin panel initialized successfully!");
}

function initializeCharts() {
  createUsersChart();
  createGrowthChart();
}

function createUsersChart() {
  const canvas = document.getElementById("usersChart");
  if (!canvas) return;

  const ctx = canvas.getContext("2d");

  // Destroy existing chart if it exists
  if (usersChart) {
    usersChart.destroy();
  }

  usersChart = new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: ["Utilisateurs", "Agents Immobiliers", "Administrateurs"],
      datasets: [
        {
          data: [2391, 156, 3],
          backgroundColor: ["#3b82f6", "#f97316", "#10b981"],
          borderWidth: 3,
          borderColor: "#ffffff",
          hoverOffset: 8,
          hoverBorderWidth: 4,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: "bottom",
          labels: {
            padding: 20,
            color: "#6b7280",
            font: {
              size: 13,
              weight: "500",
            },
            usePointStyle: true,
            pointStyle: "circle",
          },
        },
        tooltip: {
          backgroundColor: "rgba(0, 0, 0, 0.8)",
          titleColor: "white",
          bodyColor: "white",
          borderColor: "#f97316",
          borderWidth: 1,
          cornerRadius: 8,
          displayColors: true,
          callbacks: {
            label: function (context) {
              const total = context.dataset.data.reduce((a, b) => a + b, 0);
              const percentage = ((context.parsed / total) * 100).toFixed(1);
              return `${context.label}: ${context.parsed.toLocaleString()} (${percentage}%)`;
            },
          },
        },
      },
      cutout: "60%",
      layout: {
        padding: 10,
      },
    },
  });
}

function createGrowthChart() {
  const canvas = document.getElementById("growthChart");
  if (!canvas) return;

  const ctx = canvas.getContext("2d");

  // Destroy existing chart if it exists
  if (growthChart) {
    growthChart.destroy();
  }

  growthChart = new Chart(ctx, {
    type: "line",
    data: {
      labels: ["Jan", "Fév", "Mar", "Avr", "Mai", "Jun"],
      datasets: [
        {
          label: "Nouveaux utilisateurs",
          data: [85, 127, 156, 203, 189, 234],
          borderColor: "#3b82f6",
          backgroundColor: "rgba(59, 130, 246, 0.1)",
          borderWidth: 3,
          fill: true,
          tension: 0.4,
          pointBackgroundColor: "#3b82f6",
          pointBorderColor: "#ffffff",
          pointBorderWidth: 2,
          pointRadius: 5,
        },
        {
          label: "Nouveaux agents",
          data: [12, 18, 15, 23, 19, 28],
          borderColor: "#f97316",
          backgroundColor: "rgba(249, 115, 22, 0.1)",
          borderWidth: 3,
          fill: true,
          tension: 0.4,
          pointBackgroundColor: "#f97316",
          pointBorderColor: "#ffffff",
          pointBorderWidth: 2,
          pointRadius: 5,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: "top",
          labels: {
            color: "#6b7280",
            font: {
              size: 12,
              weight: "500",
            },
            usePointStyle: true,
            pointStyle: "circle",
            padding: 20,
          },
        },
        tooltip: {
          backgroundColor: "rgba(0, 0, 0, 0.8)",
          titleColor: "white",
          bodyColor: "white",
          borderColor: "#f97316",
          borderWidth: 1,
          cornerRadius: 8,
          displayColors: true,
          callbacks: {
            title: function (context) {
              return `${context[0].label} 2024`;
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
              size: 11,
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
              size: 11,
            },
            padding: 10,
          },
        },
      },
      interaction: {
        intersect: false,
        mode: "index",
      },
    },
  });
}

function setupAdminInteractions() {
  // Setup pending approvals
  setupPendingApprovals();

  // Setup property moderation
  setupPropertyModeration();

  // Setup activity monitoring
  setupActivityMonitoring();

  // Setup system stats
  setupSystemStats();
}

function setupPendingApprovals() {
  const pendingItems = document.querySelectorAll(".pending-item");

  pendingItems.forEach((item) => {
    const approveBtn = item.querySelector(".btn-success");
    const rejectBtn = item.querySelector(".btn-danger");
    const viewBtn = item.querySelector(".btn-outline");

    if (approveBtn) {
      approveBtn.addEventListener("click", function (e) {
        e.stopPropagation();
        handleApprovalAction(item, "approve");
      });
    }

    if (rejectBtn) {
      rejectBtn.addEventListener("click", function (e) {
        e.stopPropagation();
        handleApprovalAction(item, "reject");
      });
    }

    if (viewBtn) {
      viewBtn.addEventListener("click", function (e) {
        e.stopPropagation();
        const name = item.querySelector("h4").textContent;
        showNotification(`Consultation du dossier: ${name}`, "info");
      });
    }
  });
}

function handleApprovalAction(item, action) {
  const name = item.querySelector("h4").textContent;
  const isAgent = item
    .closest(".dashboard-card")
    .querySelector("h3")
    .textContent.includes("Agents");

  if (action === "approve") {
    showNotification(
      `${isAgent ? "Agent" : "Annonce"} "${name}" approuvé(e)!`,
      "success",
    );
    item.style.backgroundColor = "#f0fdf4";
    item.style.borderColor = "#10b981";
  } else {
    showNotification(
      `${isAgent ? "Agent" : "Annonce"} "${name}" rejeté(e)`,
      "warning",
    );
    item.style.backgroundColor = "#fef2f2";
    item.style.borderColor = "#ef4444";
  }

  // Disable buttons
  const buttons = item.querySelectorAll("button");
  buttons.forEach((btn) => (btn.disabled = true));

  // Remove item after delay
  setTimeout(() => {
    item.style.opacity = "0";
    item.style.transform = "translateY(-20px)";
    setTimeout(() => {
      if (item.parentNode) {
        item.parentNode.removeChild(item);
        updatePendingCounts(action);
      }
    }, 300);
  }, 1500);
}

function updatePendingCounts(action) {
  // Update the stats cards
  const agentsStat = document.querySelector(
    ".stat-card:nth-child(2) .stat-value",
  );
  const pendingStat = document.querySelector(
    ".stat-card:nth-child(3) .stat-value",
  );

  if (action === "approve" && agentsStat) {
    const currentValue = parseInt(agentsStat.textContent);
    agentsStat.textContent = (currentValue + 1).toString();
    agentsStat.style.color = "#10b981";
    setTimeout(() => {
      agentsStat.style.color = "#1f2937";
    }, 1000);
  }

  if (pendingStat) {
    const currentValue = parseInt(pendingStat.textContent);
    if (currentValue > 0) {
      pendingStat.textContent = (currentValue - 1).toString();
      pendingStat.style.color = action === "approve" ? "#10b981" : "#ef4444";
      setTimeout(() => {
        pendingStat.style.color = "#1f2937";
      }, 1000);
    }
  }
}

function setupPropertyModeration() {
  const propertyItems = document.querySelectorAll(".pending-item");

  propertyItems.forEach((item) => {
    // Add hover effects for better UX
    item.addEventListener("mouseenter", function () {
      this.style.backgroundColor = "#f9fafb";
    });

    item.addEventListener("mouseleave", function () {
      if (!this.classList.contains("processed")) {
        this.style.backgroundColor = "white";
      }
    });
  });
}

function setupActivityMonitoring() {
  const activityItems = document.querySelectorAll(".activity-item");

  activityItems.forEach((item, index) => {
    // Add staggered animation
    item.style.opacity = "0";
    item.style.transform = "translateY(20px)";

    setTimeout(() => {
      item.style.transition = "all 0.3s ease";
      item.style.opacity = "1";
      item.style.transform = "translateY(0)";
    }, index * 100);
  });
}

function setupSystemStats() {
  const systemStatItems = document.querySelectorAll(".system-stat-item");

  systemStatItems.forEach((item) => {
    const statValue = item.querySelector(".stat-value");
    const originalValue = statValue.textContent;

    // Add hover effect to show additional info
    item.addEventListener("mouseenter", function () {
      this.style.backgroundColor = "#f9fafb";

      // Simulate showing trend
      if (originalValue.includes("%")) {
        const trendIndicator = document.createElement("span");
        trendIndicator.textContent = " ↗";
        trendIndicator.style.color = "#10b981";
        trendIndicator.style.marginLeft = "5px";
        statValue.appendChild(trendIndicator);
      }
    });

    item.addEventListener("mouseleave", function () {
      this.style.backgroundColor = "white";
      statValue.innerHTML = originalValue;
    });
  });
}

function loadAdminData() {
  // Simulate loading admin data
  const cards = document.querySelectorAll(".dashboard-card");

  cards.forEach((card, index) => {
    card.style.opacity = "0";
    card.style.transform = "translateY(20px)";

    setTimeout(() => {
      card.style.transition = "all 0.4s ease";
      card.style.opacity = "1";
      card.style.transform = "translateY(0)";
    }, index * 150);
  });

  setTimeout(() => {
    showNotification("Données d'administration chargées", "success");
  }, 2000);
}

function setupRealTimeMonitoring() {
  // Simulate real-time activity updates
  setInterval(() => {
    addNewActivity();
    updateSystemMetrics();
  }, 45000);

  // Simulate new pending items occasionally
  setInterval(() => {
    if (Math.random() > 0.7) {
      simulateNewPendingItem();
    }
  }, 60000);
}

function addNewActivity() {
  const activityList = document.querySelector(".activity-list");
  if (!activityList) return;

  const activities = [
    "Nouveau utilisateur inscrit",
    "Annonce mise à jour",
    "Demande de contact reçue",
    "Agent immobilier connecté",
    "Propriété vue",
  ];

  const users = [
    "Marie Katolo",
    "Jean Mbala",
    "Paul Kisangani",
    "Grace Ilunga",
    "Patient Mukendi",
  ];

  const newActivity = document.createElement("div");
  newActivity.className = "activity-item";
  newActivity.style.opacity = "0";
  newActivity.style.transform = "translateY(-20px)";

  const randomActivity =
    activities[Math.floor(Math.random() * activities.length)];
  const randomUser = users[Math.floor(Math.random() * users.length)];

  newActivity.innerHTML = `
    <div class="activity-dot"></div>
    <div class="activity-content">
      <p class="activity-action">${randomActivity}</p>
      <p class="activity-details">${randomUser} • À l'instant</p>
    </div>
  `;

  activityList.insertBefore(newActivity, activityList.firstChild);

  // Animate in
  setTimeout(() => {
    newActivity.style.transition = "all 0.3s ease";
    newActivity.style.opacity = "1";
    newActivity.style.transform = "translateY(0)";
  }, 100);

  // Remove oldest activity if too many
  const activities_list = activityList.querySelectorAll(".activity-item");
  if (activities_list.length > 5) {
    const lastActivity = activities_list[activities_list.length - 1];
    lastActivity.style.opacity = "0";
    setTimeout(() => {
      if (lastActivity.parentNode) {
        lastActivity.parentNode.removeChild(lastActivity);
      }
    }, 300);
  }
}

function updateSystemMetrics() {
  const systemStats = document.querySelectorAll(
    ".system-stat-item .stat-value",
  );

  systemStats.forEach((stat) => {
    const currentText = stat.textContent;

    if (currentText.includes("%")) {
      const currentValue = parseFloat(currentText);
      const change = (Math.random() - 0.5) * 0.2; // Small random change
      const newValue = Math.max(0, Math.min(100, currentValue + change));

      stat.style.color = change > 0 ? "#10b981" : "#ef4444";
      stat.textContent = newValue.toFixed(1) + "%";

      setTimeout(() => {
        stat.style.color = "#1f2937";
      }, 2000);
    } else if (currentText.includes("h")) {
      // Time values
      const currentValue = parseFloat(currentText);
      const change = (Math.random() - 0.5) * 0.1;
      const newValue = Math.max(0.1, currentValue + change);

      stat.textContent = newValue.toFixed(1) + "h";
    }
  });
}

function simulateNewPendingItem() {
  showNotification("Nouvelle demande d'agent en attente d'approbation", "info");

  // Update pending count
  const pendingStat = document.querySelector(
    ".stat-card:nth-child(3) .stat-value",
  );
  if (pendingStat) {
    const currentValue = parseInt(pendingStat.textContent);
    pendingStat.textContent = (currentValue + 1).toString();
    pendingStat.style.color = "#f59e0b";
    setTimeout(() => {
      pendingStat.style.color = "#1f2937";
    }, 2000);
  }
}

// Export functions for debugging
window.adminPanel = {
  handleApprovalAction,
  addNewActivity,
  updateSystemMetrics,
  createUsersChart,
  createGrowthChart,
};

// Handle window resize
window.addEventListener(
  "resize",
  debounce(() => {
    if (usersChart) usersChart.resize();
    if (growthChart) growthChart.resize();
  }, 250),
);

// Initialize Chart.js defaults for admin
Chart.defaults.font.family = "Inter";
Chart.defaults.color = "#6b7280";
Chart.defaults.font.size = 12;
