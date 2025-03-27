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