function openModal(category, data = {}) {
    document.getElementById('editModal').style.display = 'block';
    document.getElementById('editCategory').value = category;

    // Hide all fields initially
    document.querySelectorAll('.modal-fields').forEach(field => field.style.display = 'none');

    // Show the relevant fields based on the category
    const fields = document.getElementById(category + 'Fields');
    if (fields) {
        fields.style.display = 'block';
    }

    // Populate fields with data if provided
    for (const key in data) {
        if (data.hasOwnProperty(key)) {
            const input = document.getElementById(category + '_' + key);
            if (input) {
                if (input.type === 'checkbox') {
                    input.checked = data[key] === 1;
                } else {
                    input.value = data[key];
                }
            }
        }
    }

    // Set the ID field for editing
    document.getElementById('id').value = data.id || '';
}

function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}

function openPasswordModal() {
    document.getElementById('passwordModal').style.display = 'block';
}

function closePasswordModal() {
    document.getElementById('passwordModal').style.display = 'none';
}

function closeMessageModal() {
    document.getElementById('messageModal').style.display = 'none';
}

function openCareerHistoryListModal() {
    document.getElementById('careerHistoryListModal').style.display = 'block';
}

function closeCareerHistoryListModal() {
    document.getElementById('careerHistoryListModal').style.display = 'none';
}

function openCareerHistoryEditModal(data) {
    closeCareerHistoryListModal();
    openModal('careerhistory', data);
    document.getElementById('careerhistory_Jdescription').value = data.JDescription || '';
}

function openLanguagesListModal() {
    document.getElementById('languagesListModal').style.display = 'block';
}

function closeLanguagesListModal() {
    document.getElementById('languagesListModal').style.display = 'none';
}

function removeLanguage(id) {
    if (confirm('Are you sure you want to remove this language?')) {
        fetch('../includes/employee/emp_delete_profile_entry.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ category: 'languages', id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Language removed successfully');
                location.reload(); // Reload the page to reflect changes
            } else {
                alert('Failed to remove language: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while removing the language.');
        });
    }
}

function openEducationListModal() {
    document.getElementById('educationListModal').style.display = 'block';
}

function closeEducationListModal() {
    document.getElementById('educationListModal').style.display = 'none';
}

function openEducationEditModal(data) {
    closeEducationListModal();
    openModal('education', data);
}

function openAddModal(category) {
    openModal(category);
    document.getElementById('id').value = ''; // Clear the ID field for adding a new entry
    // Clear all input fields
    document.querySelectorAll('.modal-fields input, .modal-fields textarea').forEach(input => {
        if (input.type === 'checkbox') {
            input.checked = false;
        } else {
            input.value = '';
        }
    });
}

function deleteEntry() {
    const category = document.getElementById('editCategory').value;
    const id = document.getElementById('id').value;
    if (id && confirm('Are you sure you want to delete this entry?')) {
        fetch('../includes/employee/emp_delete_profile_entry.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ category, id })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response:', data); // Log the response for debugging
            if (data.success) {
                alert('Entry deleted successfully');
                location.reload(); // Reload the page to reflect changes
            } else {
                alert('Failed to delete entry: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the entry.');
        });
    }
}

function openCvUploadModal() {
    document.getElementById('cvUploadModal').style.display = 'block';
}

function closeCvUploadModal() {
    document.getElementById('cvUploadModal').style.display = 'none';
}

function openCvListModal() {
    document.getElementById('cvListModal').style.display = 'block';
}

function closeCvListModal() {
    document.getElementById('cvListModal').style.display = 'none';
}

function openSkillsListModal() {
    document.getElementById('skillsListModal').style.display = 'block';
}

function closeSkillsListModal() {
    document.getElementById('skillsListModal').style.display = 'none';
}

function removeSkill(id) {
    if (confirm('Are you sure you want to remove this skill?')) {
        fetch('../includes/employee/emp_delete_profile_entry.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ category: 'skills', id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Skill removed successfully');
                location.reload(); // Reload the page to reflect changes
            } else {
                alert('Failed to remove skill: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while removing the skill.');
        });
    }
}

function openCertificationsListModal() {
    document.getElementById('certificationsListModal').style.display = 'block';
}

function closeCertificationsListModal() {
    document.getElementById('certificationsListModal').style.display = 'none';
}

function removeCertification(id) {
    if (confirm('Are you sure you want to remove this certification?')) {
        fetch('../includes/employee/emp_delete_profile_entry.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ category: 'certification', id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Certification removed successfully');
                location.reload(); // Reload the page to reflect changes
            } else {
                alert('Failed to remove certification: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while removing the certification.');
        });
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    var successMessage = document.getElementById('successMessage')?.value || '';
    var errorMessage = document.getElementById('errorMessage')?.value || '';
    if (successMessage || errorMessage) {
        document.getElementById('messageModal').style.display = 'block';
        document.getElementById('messageContent').innerText = successMessage || errorMessage;
    }
});
