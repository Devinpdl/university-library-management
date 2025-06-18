document.addEventListener('DOMContentLoaded', function() {
    // Handle sidebar menu navigation
    const sidebarMenuItems = document.querySelectorAll('.sidebar-menu-item');
    sidebarMenuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const page = this.getAttribute('data-page');
            
            // Remove active class from all menu items
            sidebarMenuItems.forEach(menuItem => menuItem.classList.remove('active'));
            
            // Add active class to clicked menu item
            this.classList.add('active');
            
            // Update page title
            const pageTitle = document.getElementById('pageTitle');
            if (pageTitle) {
                pageTitle.textContent = page.charAt(0).toUpperCase() + page.slice(1);
            }
            
            // Load the page content
            window.location.href = `views/${page}.php`;
        });
    });

    // Handle mobile sidebar toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.classList.toggle('active');
            }
        });
    }

    // Handle logout
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Send logout request
            fetch('api/auth.php?action=logout', {
                method: 'POST',
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'login.php';
                } else {
                    alert('Logout failed. Please try again.');
                }
            })
            .catch(error => {
                console.error('Logout error:', error);
                alert('An error occurred during logout. Please try again.');
            });
        });
    }

    // Handle profile link
    const profileLink = document.querySelector('[data-page="profile"]');
    if (profileLink) {
        profileLink.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = 'views/profile.php';
        });
    }
});