/**
 * Admin Panel Custom JavaScript
 */

document.addEventListener('DOMContentLoaded', () => {
  // Toggle Sidebar on Mobile
  const sidebarToggle = document.getElementById('sidebarToggle');
  const adminSidebar = document.querySelector('.admin-sidebar');

  if (sidebarToggle && adminSidebar) {
    sidebarToggle.addEventListener('click', () => {
      adminSidebar.classList.toggle('show');
    });
  }

  // Auto generate URL slug from title field
  const titleInput = document.getElementById('titleInput');
  const slugInput = document.getElementById('slugInput');

  if (titleInput && slugInput) {
    titleInput.addEventListener('input', (e) => {
      if (!slugInput.hasAttribute('data-manual')) {
        slugInput.value = e.target.value
          .toLowerCase()
          .replace(/[^\w\s-]/g, '')
          .replace(/[\s_-]+/g, '-')
          .replace(/^-+|-+$/g, '');
      }
    });

    slugInput.addEventListener('input', () => {
      slugInput.setAttribute('data-manual', 'true');
    });
  }

  // Delete Confirmation Helper
  const deleteBtns = document.querySelectorAll('.btn-confirm-delete');
  deleteBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
      if (!confirm('Are you sure you want to permanently delete this record? This action cannot be undone.')) {
        e.preventDefault();
      }
    });
  });

  // Simple Live Table Search
  const tableSearchInput = document.getElementById('tableSearchInput');
  if (tableSearchInput) {
    tableSearchInput.addEventListener('keyup', (e) => {
      const query = e.target.value.toLowerCase();
      const rows = document.querySelectorAll('.table-admin tbody tr');
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
      });
    });
  }
});
