$('#editJobModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var jobId = button.data('job-id');

    // Fetch job details using AJAX
    $.ajax({
        url: '../includes/company/comp_get_job_details.php',
        type: 'GET',
        data: { job_id: jobId },
        success: function (data) {
            var job = JSON.parse(data);
            $('#editJobId').val(job.job_id);
            $('#editJobTitle').val(job.title);
            $('#editJobDescription').val(job.description);
            $('#editJobRequirements').val(job.requirements);
            $('#editJobType').val(job.employment_type);
            $('#editJobLocation').val(job.location);
            $('#editJobSalaryMin').val(job.salary_min);
            $('#editJobSalaryMax').val(job.salary_max);
            $('#editJobCurrency').val(job.currency);
            $('#editJobCategory').val(job.category_id);
            $('#editJobExpiryDate').val(job.expiry_date);

            // Set the selected employment type
            $('#editJobType').val(job.employment_type);

            // Set the expiry date in the correct format
            $('#editJobExpiryDate').val(new Date(job.expiry_date).toISOString().split('T')[0]);
        }
    });
});

$('#editJobForm').on('submit', function (event) {
    event.preventDefault();

    // Update job details using AJAX
    $.ajax({
        url: '../includes/company/comp_update_job_details.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function (response) {
            alert('Job details updated successfully!');
            location.reload();
        }
    });
});

function updateJobStatus(selectElement) {
    var jobId = $(selectElement).data('job-id');
    var status = $(selectElement).val();

    $.ajax({
        url: '../includes/company/comp_update_job_status.php',
        type: 'POST',
        data: { job_id: jobId, status: status },
        success: function (response) {
            alert('Job status updated successfully!');
            location.reload();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const activeTab = document.querySelector('.tab.active').getAttribute('data-tab');
    switchTab(activeTab);
});

function switchTab(tabId) {
    document.querySelectorAll('.content').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.tab').forEach(button => button.classList.remove('active'));

    var targetTab = document.getElementById(tabId);
    if (targetTab) {
        targetTab.classList.add('active');
        document.querySelector(`.tab[data-tab="${tabId}"]`).classList.add('active');
    }

    // Hide job-filters section when in Dashboard or Job Tab
    if (tabId === 'dashboard' || tabId === 'jobs') {
        document.getElementById('job-filters').classList.add('hidden');
    } else {
        document.getElementById('job-filters').classList.remove('hidden');
    }
}

function fetchCandidates(jobId) {
    if (!jobId) {
        document.getElementById('candidatesTableBody').innerHTML = '<tr><td colspan="4" class="text-center">Select a job to view candidates.</td></tr>';
        return;
    }

    // Fetch candidates using AJAX
    $.ajax({
        url: '../includes/company/comp_get_candidates.php',
        type: 'GET',
        data: { job_id: jobId },
        success: function (response) {
            const candidates = JSON.parse(response);
            const tableBody = document.getElementById('candidatesTableBody');
            tableBody.innerHTML = '';

            if (candidates.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="4" class="text-center">No candidates found for this job.</td></tr>';
            } else {
                candidates.forEach(candidate => {
                    const row = `
                        <tr>
                            <td>${candidate.firstName} ${candidate.lastName}</td>
                            <td>${candidate.emailAddress}</td>
                            <td>${candidate.application_time}</td>
                            <td>${candidate.status}</td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            }
        }
    });
}

function searchJobs() {
    const searchInput = document.getElementById('searchInput').value;

    $.ajax({
        url: '../includes/company/comp_search_jobs.php',
        type: 'GET',
        data: { search: searchInput },
        success: function (response) {
            document.getElementById('jobResults').innerHTML = response;
        }
    });
}

function toggleDropdown() {
    const dropdown = document.querySelector('.dropdown');
    dropdown.classList.toggle('show');
}

// Close the dropdown if clicked outside
document.addEventListener('click', function (event) {
    const dropdown = document.querySelector('.dropdown');
    if (!dropdown.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});

//timer
function updateTime() {
    const options = { timeZone: 'Asia/Manila', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
    const currentTime = new Date().toLocaleTimeString('en-US', options);
    document.getElementById('currentTime').textContent = currentTime;
}
setInterval(updateTime, 1000);
updateTime();

//search script
            let currentSortOrder = window.initialSortOrder || 'desc';
            let currentSearchQuery = window.initialSearchQuery || '';

            function updateSort() {
                const sortBy = document.getElementById('sortBy').value;
                fetchJobs(sortBy, currentSortOrder, currentSearchQuery);
            }

            function toggleSortOrder() {
                // Toggle the current sort order
                currentSortOrder = currentSortOrder === 'desc' ? 'asc' : 'desc';

                // Update the sortOrderIndicator text
                document.getElementById('sortOrderIndicator').textContent = currentSortOrder === 'desc' ? 'Descending' : 'Ascending';

                // Fetch and update the table with the new sort order
                fetchJobs(document.getElementById('sortBy').value, currentSortOrder, currentSearchQuery);
            }

            function updateJobStatusColors() {
                const statusDropdowns = document.querySelectorAll('.job-status-dropdown');
                statusDropdowns.forEach(dropdown => {
                    const selectedOption = dropdown.options[dropdown.selectedIndex];
                    const status = selectedOption.value;
                    let color;
                    
                    switch(status) {
                        case 'active':
                            color = '#28a745';
                            break;
                        case 'paused':
                            color = '#ffc107';
                            break;
                        case 'inactive':
                            color = '#dc3545';
                            break;
                    }
                    
                    dropdown.style.color = color;
                    selectedOption.style.color = color;
                });
            }

            // Call the function when the page loads
            document.addEventListener('DOMContentLoaded', updateJobStatusColors);

            // Update the fetchJobs function to maintain colors
            function fetchJobs(sortBy, sortOrder, searchQuery) {
                // Fetch sorted and filtered job data from the backend
                fetch(`../includes/company/comp_dashboard_fetch_jobs.php?sort_by=${sortBy}&sort_order=${sortOrder}&search=${searchQuery}`)
                    .then(response => response.text())
                    .then(html => {
                        // Update the jobResults table body with the new data
                        document.getElementById('jobResults').innerHTML = html;

                        // Add a small delay to ensure the DOM is updated
                        setTimeout(updateJobStatusColors, 100);
                    })
                    .catch(error => console.error('Error:', error));
            }

            // Add event listener for search input
            document.getElementById('searchInput').addEventListener('keyup', function(event) {
                if (event.key === 'Enter') {
                    searchJobs();
                }
            });

            // Add event listener for status dropdown changes
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('job-status-dropdown')) {
                    updateJobStatusColors();
                }
            });

//notification shyt
            function toggleNotification() {
                const notificationContent = document.getElementById('notificationContent');
                notificationContent.classList.toggle('show');
            }

            // Close notification dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const notificationDropdown = document.querySelector('.notification-dropdown');
                const notificationContent = document.getElementById('notificationContent');
                
                if (!notificationDropdown.contains(event.target) && notificationContent.classList.contains('show')) {
                    notificationContent.classList.remove('show');
                }
            });

            // Mark all notifications as read
            document.querySelector('.mark-all-read').addEventListener('click', function() {
                const unreadNotifications = document.querySelectorAll('.notification-item.unread');
                unreadNotifications.forEach(notification => {
                    notification.classList.remove('unread');
                });
            });

            // Mark individual notification as read when clicked
            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function() {
                    this.classList.remove('unread');
                });
            });

//message read mark

 function toggleMessage() {
                const messageContent = document.getElementById('messageContent');
                messageContent.classList.toggle('show');
            }

            // Close message dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const messageDropdown = document.querySelector('.message-dropdown');
                const messageContent = document.getElementById('messageContent');
                
                if (!messageDropdown.contains(event.target) && messageContent.classList.contains('show')) {
                    messageContent.classList.remove('show');
                }
            });

            // Mark all messages as read
            document.querySelectorAll('.mark-all-read').forEach(button => {
                button.addEventListener('click', function() {
                    const container = this.closest('.message-content, .notification-content');
                    const unreadItems = container.querySelectorAll('.unread');
                    unreadItems.forEach(item => {
                        item.classList.remove('unread');
                    });
                    
                    // Update badge count
                    if (container.classList.contains('message-content')) {
                        const messageBadge = document.querySelector('.message-badge');
                        messageBadge.textContent = '0';
                        messageBadge.style.display = 'none';
                    } else {
                        const notificationBadge = document.querySelector('.notification-badge');
                        notificationBadge.textContent = '0';
                        notificationBadge.style.display = 'none';
                    }
                });
            });

            // Mark individual message as read when clicked
            document.querySelectorAll('.message-item').forEach(item => {
                item.addEventListener('click', function() {
                    this.classList.remove('unread');
                    // Update badge count
                    const messageBadge = document.querySelector('.message-badge');
                    const currentCount = parseInt(messageBadge.textContent);
                    if (currentCount > 1) {
                        messageBadge.textContent = (currentCount - 1).toString();
                    } else {
                        messageBadge.style.display = 'none';
                    }
                });
            });

            //message read mark
            function toggleMessage() {
                const messageContent = document.getElementById('messageContent');
                messageContent.classList.toggle('show');
            }

            // Close message dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const messageDropdown = document.querySelector('.message-dropdown');
                const messageContent = document.getElementById('messageContent');
                
                if (!messageDropdown.contains(event.target) && messageContent.classList.contains('show')) {
                    messageContent.classList.remove('show');
                }
            });

            // Mark all messages as read
            document.querySelectorAll('.mark-all-read').forEach(button => {
                button.addEventListener('click', function() {
                    const container = this.closest('.message-content, .notification-content');
                    const unreadItems = container.querySelectorAll('.unread');
                    unreadItems.forEach(item => {
                        item.classList.remove('unread');
                    });
                    
                    // Update badge count
                    if (container.classList.contains('message-content')) {
                        const messageBadge = document.querySelector('.message-badge');
                        messageBadge.textContent = '0';
                        messageBadge.style.display = 'none';
                    } else {
                        const notificationBadge = document.querySelector('.notification-badge');
                        notificationBadge.textContent = '0';
                        notificationBadge.style.display = 'none';
                    }
                });
            });

            // Mark individual message as read when clicked
            document.querySelectorAll('.message-item').forEach(item => {
                item.addEventListener('click', function() {
                    this.classList.remove('unread');
                    // Update badge count
                    const messageBadge = document.querySelector('.message-badge');
                    const currentCount = parseInt(messageBadge.textContent);
                    if (currentCount > 1) {
                        messageBadge.textContent = (currentCount - 1).toString();
                    } else {
                        messageBadge.style.display = 'none';
                    }
                });
            });