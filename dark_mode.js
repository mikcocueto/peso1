// dark-mode.js
document.addEventListener('DOMContentLoaded', function () {
    const darkModeToggle = document.getElementById('darkModeToggle');
    const isDark = localStorage.getItem('darkMode') === 'true';
  
    // Apply dark mode if previously saved in localStorage
    if (isDark) document.body.classList.add('dark-mode');
    darkModeToggle.checked = isDark;
  
    darkModeToggle.addEventListener('change', function () {
      document.body.classList.toggle('dark-mode');
      localStorage.setItem('darkMode', darkModeToggle.checked);
    });
  });
  