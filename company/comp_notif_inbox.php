<?php
// Fetch notifications for the logged-in company
require __DIR__ . '/../includes/db_connect.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['company_id'])) {
    header("Location: comp_login.php");
    exit();
}

$company_id = $_SESSION['company_id'];

$notification_query = "SELECT notification_id, job_id, message, is_read, created_at 
                       FROM tbl_job_notifications 
                       WHERE company_id = ? 
                       ORDER BY created_at DESC";
$stmt = $conn->prepare($notification_query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$notification_result = $stmt->get_result();
$notifications = $notification_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Separate notifications into unread and read
$unread_notifications = array_filter($notifications, fn($n) => $n['is_read'] == 0);
$read_notifications = array_filter($notifications, fn($n) => $n['is_read'] == 1);
?>

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
    <!-- Unread Notifications -->
    <?php if (!empty($unread_notifications)): ?>
      <h5 class="mb-3">Unread Notifications</h5>
      <?php foreach ($unread_notifications as $notification): ?>
        <div class="col-12" id="notif-<?= $notification['notification_id'] ?>">
          <div class="card notification-card unread p-3" 
               onclick="viewNotification(<?= $notification['notification_id'] ?>, '<?= htmlspecialchars($notification['message']) ?>', '<?= htmlspecialchars(date('F j, Y, g:i a', strtotime($notification['created_at']))) ?>')">
            <div class="d-flex align-items-start justify-content-between">
              <div class="d-flex align-items-start">
                <i class="bi bi-info-circle-fill notification-icon me-3"></i>
                <div>
                  <h5 class="mb-1">Notification</h5>
                  <p class="mb-1"><?= htmlspecialchars($notification['message']) ?></p>
                  <span class="notification-time"><?= htmlspecialchars(date('F j, Y, g:i a', strtotime($notification['created_at']))) ?></span>
                </div>
              </div>
              <div class="action-buttons">
                <button class="btn btn-sm btn-outline-primary mark-btn" onclick="toggleReadStatus(<?= $notification['notification_id'] ?>, <?= $notification['is_read'] ?>, event)">Mark as Read</button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteNotification(<?= $notification['notification_id'] ?>, event)">Delete</button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

    <!-- Read Notifications -->
    <?php if (!empty($read_notifications)): ?>
      <h5 class="mt-4 mb-3">Read Notifications</h5>
      <?php foreach ($read_notifications as $notification): ?>
        <div class="col-12" id="notif-<?= $notification['notification_id'] ?>">
          <div class="card notification-card read p-3" 
               onclick="viewNotification(<?= $notification['notification_id'] ?>, '<?= htmlspecialchars($notification['message']) ?>', '<?= htmlspecialchars(date('F j, Y, g:i a', strtotime($notification['created_at']))) ?>')">
            <div class="d-flex align-items-start justify-content-between">
              <div class="d-flex align-items-start">
                <i class="bi bi-info-circle-fill notification-icon me-3"></i>
                <div>
                  <h5 class="mb-1">Notification</h5>
                  <p class="mb-1"><?= htmlspecialchars($notification['message']) ?></p>
                  <span class="notification-time"><?= htmlspecialchars(date('F j, Y, g:i a', strtotime($notification['created_at']))) ?></span>
                </div>
              </div>
              <div class="action-buttons">
                <button class="btn btn-sm btn-outline-primary mark-btn" onclick="toggleReadStatus(<?= $notification['notification_id'] ?>, <?= $notification['is_read'] ?>, event)">Mark as Unread</button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteNotification(<?= $notification['notification_id'] ?>, event)">Delete</button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

    <!-- No Notifications -->
    <?php if (empty($unread_notifications) && empty($read_notifications)): ?>
      <div class="col-12 text-center text-muted">
        <p>No notifications available.</p>
      </div>
    <?php endif; ?>
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
                <h5 id="modalTitle">Notification</h5>
                <p id="modalMessage" class="mt-2"></p>
                <small id="modalTime" class="text-muted d-block mt-3"></small>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleReadStatus(notificationId, isRead, event) {
        event.stopPropagation();
        const card = document.getElementById(`notif-${notificationId}`).querySelector('.notification-card');
        const button = event.target;

        const newStatus = isRead == 0 ? 1 : 0;
        fetch('../includes/company/mark_notifications_status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ notification_id: notificationId, is_read: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                card.classList.toggle('unread', newStatus == 0);
                card.classList.toggle('read', newStatus == 1);
                button.textContent = `Mark as ${newStatus == 0 ? 'Read' : 'Unread'}`;
                button.setAttribute('onclick', `toggleReadStatus(${notificationId}, ${newStatus}, event)`); // Update the button's onclick
            } else {
                alert('Failed to update notification status.');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function deleteNotification(notificationId, event) {
        event.stopPropagation();
        fetch('../includes/company/delete_notification.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ notification_id: notificationId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`notif-${notificationId}`).remove();
            } else {
                alert('Failed to delete notification.');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function viewNotification(notificationId, message, time) {
        document.getElementById('modalMessage').textContent = message;
        document.getElementById('modalTime').textContent = time;

        const modal = new bootstrap.Modal(document.getElementById('notificationModal'));
        modal.show();

        toggleReadStatus(notificationId, 0, { stopPropagation: () => {} });
    }
</script>

</body>
</html>
