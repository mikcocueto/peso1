<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Notification Inbox</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f0f2f5;
    }

    .notification-card {
      transition: background-color 0.3s, transform 0.2s;
      border: none;
      border-radius: 1rem;
      cursor: pointer;
    }

    .notification-card.unread {
      background-color: #ffffff;
      font-weight: 500;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .notification-card.read {
      background-color: #f8f9fa;
      color: #6c757d;
    }

    .notification-card:hover {
      background-color: #e9ecef;
      transform: scale(1.01);
    }

    .notification-icon {
      font-size: 1.5rem;
      color: #0d6efd;
    }

    .notification-time {
      font-size: 0.85rem;
      color: #888;
    }

    .action-buttons {
      display: flex;
      gap: 0.5rem;
    }
  </style>
</head>
<body>

<?php include 'comp_navbar&tab.php'; ?>

<div class="container py-5">
  <h3 class="mb-4">🔔 Notification Inbox</h3>

  <div class="row g-3">

    <!-- Notification 1 -->
    <div class="col-12" id="notif-1">
      <div class="card notification-card unread p-3" onclick="viewNotification(1, 'New Job Posted', 'A new job has been posted that matches your skills.', '2 hours ago')">
        <div class="d-flex align-items-start justify-content-between">
          <div class="d-flex align-items-start">
            <i class="bi bi-briefcase-fill notification-icon me-3"></i>
            <div>
              <h5 class="mb-1">New Job Posted</h5>
              <p class="mb-1">A new job has been posted that matches your skills.</p>
              <span class="notification-time">2 hours ago</span>
            </div>
          </div>
          <div class="action-buttons">
            <button class="btn btn-sm btn-outline-primary mark-btn" onclick="toggleReadStatus(1)">Mark as Read</button>
            <button class="btn btn-sm btn-outline-danger" onclick="deleteNotification(1)">Delete</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Notification 2 -->
    <div class="col-12" id="notif-2">
      <div class="card notification-card unread p-3" onclick="viewNotification(2, 'Application Reviewed', 'Your job application has been reviewed by the employer.', '5 hours ago')">
        <div class="d-flex align-items-start justify-content-between">
          <div class="d-flex align-items-start">
            <i class="bi bi-check2-circle notification-icon me-3"></i>
            <div>
              <h5 class="mb-1">Application Reviewed</h5>
              <p class="mb-1">Your job application has been reviewed by the employer.</p>
              <span class="notification-time">5 hours ago</span>
            </div>
          </div>
          <div class="action-buttons">
            <button class="btn btn-sm btn-outline-primary mark-btn" onclick="toggleReadStatus(2)">Mark as Read</button>
            <button class="btn btn-sm btn-outline-danger" onclick="deleteNotification(2)">Delete</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Notification 3 -->
    <div class="col-12" id="notif-3">
      <div class="card notification-card unread p-3" onclick="viewNotification(3, 'Interview Scheduled', 'Your interview is scheduled for tomorrow at 10 AM.', '1 day ago')">
        <div class="d-flex align-items-start justify-content-between">
          <div class="d-flex align-items-start">
            <i class="bi bi-calendar-event-fill notification-icon me-3"></i>
            <div>
              <h5 class="mb-1">Interview Scheduled</h5>
              <p class="mb-1">Your interview is scheduled for tomorrow at 10 AM.</p>
              <span class="notification-time">1 day ago</span>
            </div>
          </div>
          <div class="action-buttons">
            <button class="btn btn-sm btn-outline-primary mark-btn" onclick="toggleReadStatus(3)">Mark as Read</button>
            <button class="btn btn-sm btn-outline-danger" onclick="deleteNotification(3)">Delete</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- Notification Detail Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 shadow">
      <div class="modal-header">
        <h5 class="modal-title" id="notificationModalLabel">Notification Detail</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5 id="modalTitle"></h5>
        <p id="modalMessage" class="mt-2"></p>
        <small id="modalTime" class="text-muted d-block mt-3"></small>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
  function toggleReadStatus(notificationId) {
    const cardWrapper = document.getElementById(`notif-${notificationId}`);
    const card = cardWrapper.querySelector('.notification-card');
    const button = cardWrapper.querySelector('.mark-btn');

    if (card.classList.contains('unread')) {
      card.classList.remove('unread');
      card.classList.add('read');
      button.textContent = 'Mark as Unread';
      localStorage.setItem(`notif-${notificationId}`, 'read');
    } else {
      card.classList.remove('read');
      card.classList.add('unread');
      button.textContent = 'Mark as Read';
      localStorage.removeItem(`notif-${notificationId}`);
    }
  }

  function deleteNotification(notificationId) {
    const notifEl = document.getElementById(`notif-${notificationId}`);
    if (notifEl) {
      notifEl.remove();
      localStorage.removeItem(`notif-${notificationId}`);
    }
  }

  function viewNotification(notificationId, title, message, time) {
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalMessage').textContent = message;
    document.getElementById('modalTime').textContent = time;

    // Mark as read when opened
    const cardWrapper = document.getElementById(`notif-${notificationId}`);
    const card = cardWrapper.querySelector('.notification-card');
    const button = cardWrapper.querySelector('.mark-btn');

    if (card.classList.contains('unread')) {
      card.classList.remove('unread');
      card.classList.add('read');
      button.textContent = 'Mark as Unread';
      localStorage.setItem(`notif-${notificationId}`, 'read');
    }

    const modal = new bootstrap.Modal(document.getElementById('notificationModal'));
    modal.show();
  }

  // Restore read/unread state on load
  window.onload = function () {
    [1, 2, 3].forEach(id => {
      const cardContainer = document.getElementById(`notif-${id}`);
      if (!cardContainer) return;

      const card = cardContainer.querySelector('.notification-card');
      const button = cardContainer.querySelector('.mark-btn');

      if (localStorage.getItem(`notif-${id}`) === 'read') {
        card.classList.remove('unread');
        card.classList.add('read');
        button.textContent = 'Mark as Unread';
      } else {
        card.classList.remove('read');
        card.classList.add('unread');
        button.textContent = 'Mark as Read';
      }
    });
  };
</script>

</body>
</html>
