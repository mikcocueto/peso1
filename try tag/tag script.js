const tagContainer = document.getElementById('tag-container');
const tagInput = document.getElementById('tag-input');

// Add tag on button click
function addTag() {
  const tagText = tagInput.value.trim();
  if (!tagText) return;

  const tag = document.createElement('div');
  tag.className = 'tag';
  tag.innerHTML = `
    ${tagText}
    <span class="close-btn" onclick="removeTag(this)">×</span>
  `;

  tagContainer.appendChild(tag);
  tagInput.value = '';
}

// Add tag on "Enter" key press
tagInput.addEventListener('keypress', function (event) {
  if (event.key === 'Enter') {
    addTag();
  }
});

// Remove tag
function removeTag(closeBtn) {
  const tag = closeBtn.parentElement;
  tagContainer.removeChild(tag);
}
