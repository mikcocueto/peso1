function handleJobSelection(jobId) {
    if (!jobId) {
        resetCandidateList();
        return;
    }

    // Fetch all candidates first
    fetchAllCandidates(jobId);

    // Then fetch candidates for each status
    const statuses = ['applied', 'awaiting', 'reviewed', 'contacted', 'hired'];
    statuses.forEach(status => {
        fetchCandidatesForStatus(jobId, status);
    });
}

function resetCandidateList() {
    // Reset all tab
    document.getElementById('allCandidatesTable').innerHTML = `
        <tr>
            <td colspan="5" class="text-center py-5">
                <i class="fas fa-briefcase mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                <h4 class="text-muted">Select a job posting to view candidates</h4>
                <p class="text-muted">Choose a job from the dropdown above to see the list of candidates</p>
            </td>
        </tr>
    `;

    // Reset other tabs
    const statuses = ['applied', 'awaiting', 'reviewed', 'contacted', 'hired'];
    statuses.forEach(status => {
        document.getElementById(`${status}List`).innerHTML = `
            <div class="col-12 text-center py-5">
                <i class="fas fa-briefcase mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                <h4 class="text-muted">Select a job posting to view candidates</h4>
                <p class="text-muted">Choose a job from the dropdown above to see the list of candidates</p>
            </div>
        `;
    });
}

function fetchAllCandidates(jobId) {
    fetch(`../includes/company/comp_candidates_fetch.php?job_id=${jobId}&status=all`)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('allCandidatesTable');
            
            if (data.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="fas fa-users mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                            <h4 class="text-muted">No candidates found</h4>
                            <p class="text-muted">There are no candidates for this job posting</p>
                        </td>
                    </tr>
                `;
            } else {
                tableBody.innerHTML = data.map(candidate => `
                    <tr>
                        <td>${candidate.job_title}</td>
                        <td>${candidate.firstName} ${candidate.lastName}</td>
                        <td>${new Date(candidate.application_time).toLocaleString()}</td>
                        <td><span class="badge bg-${getStatusColor(candidate.status)}">${capitalizeFirst(candidate.status)}</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="viewCandidateProfile(${candidate.application_id})">
                                View Profile
                            </button>
                        </td>
                    </tr>
                `).join('');
            }
        })
        .catch(error => {
            console.error('Error fetching all candidates:', error);
            document.getElementById('allCandidatesTable').innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <i class="fas fa-exclamation-circle mb-3" style="font-size: 3rem; color: #dc3545;"></i>
                        <h4 class="text-danger">Error loading candidates</h4>
                        <p class="text-muted">Please try again later</p>
                    </td>
                </tr>
            `;
        });
}

function fetchCandidatesForStatus(jobId, status) {
    fetch(`../includes/company/comp_candidates_fetch.php?job_id=${jobId}&status=${status}`)
        .then(response => response.json())
        .then(data => {
            const candidateList = document.getElementById(`${status}List`);
            
            if (data.length === 0) {
                candidateList.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-users mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                        <h4 class="text-muted">No ${status} candidates</h4>
                        <p class="text-muted">There are no candidates in this status</p>
                    </div>
                `;
            } else {
                candidateList.innerHTML = data.map(candidate => `
                    <div class="col-md-6 col-lg-4">
                        <div class="card candidate-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="candidate-avatar me-3">
                                        <i class="fas fa-user-circle" style="font-size: 2.5rem; color: #6c757d;"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-0">${candidate.firstName} ${candidate.lastName}</h5>
                                        <p class="text-muted mb-0">${candidate.emailAddress}</p>
                                        <small class="text-muted">Applied: ${new Date(candidate.application_time).toLocaleDateString()}</small>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-${getStatusColor(candidate.status)} status-badge">${capitalizeFirst(candidate.status)}</span>
                                    <div>
                                        <span class="badge bg-light text-dark me-2">
                                            <i class="fas fa-file-alt me-1"></i>${candidate.file_count} Files
                                        </span>
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewCandidateProfile(${candidate.application_id})">
                                            View Profile
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        })
        .catch(error => {
            console.error('Error fetching candidates:', error);
            document.getElementById(`${status}List`).innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-exclamation-circle mb-3" style="font-size: 3rem; color: #dc3545;"></i>
                    <h4 class="text-danger">Error loading candidates</h4>
                    <p class="text-muted">Please try again later</p>
                </div>
            `;
        });
}

function getStatusColor(status) {
    const colors = {
        'applied': 'success',
        'awaiting': 'primary',
        'reviewed': 'secondary',
        'contacted': 'info',
        'hired': 'warning',
        'rejected': 'danger'
    };
    return colors[status] || 'secondary';
}

function capitalizeFirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function viewCandidateProfile(applicationId) {
    // This will be implemented when you add the candidate profile modal
    console.log('Viewing profile for application:', applicationId);
}

function toggleProfileDropdown() {
    const dropdown = document.getElementById('profileDropdown');
    dropdown.classList.toggle('show');
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.profile-btn') && !event.target.matches('.profile-btn *')) {
        const dropdowns = document.getElementsByClassName('profile-dropdown-content');
        for (let dropdown of dropdowns) {
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        }
    }
}
