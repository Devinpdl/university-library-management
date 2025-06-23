document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const menuItems = document.querySelectorAll('.sidebar-menu-item');
    const userDropdown = document.getElementById('userDropdown');
    const themeToggle = document.getElementById('themeToggle');
    const logoutBtn = document.getElementById('logoutBtn');
    
    // Initialize Bootstrap dropdowns
    if (typeof $ !== 'undefined') {
        // Direct initialization of dropdown without delay
        $('.dropdown-toggle').dropdown();
        
        // Force manual initialization for user dropdown
        $('#userDropdown').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const parent = $(this).parent();
            const menu = parent.find('.dropdown-menu');
            
            // Toggle dropdown visibility
            $('.dropdown-menu.show').not(menu).removeClass('show');
            $('.dropdown.show').not(parent).removeClass('show');
            
            parent.toggleClass('show');
            menu.toggleClass('show');
        });
        
        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('.dropdown-menu.show').removeClass('show');
                $('.dropdown.show').removeClass('show');
            }
        });
    }
    
    // Update page title based on active menu
    const activeMenu = document.querySelector('.sidebar-menu-item.active');
    if (activeMenu) {
        const pageTitle = document.getElementById('pageTitle');
        if (pageTitle) {
            pageTitle.textContent = activeMenu.textContent.trim();
        }
    }
    
    // Sidebar toggle functionality
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            sidebar.classList.toggle('show');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !sidebarToggle.contains(e.target) && 
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
            }
        });
    }
    
    // Theme toggle functionality
    if (themeToggle) {
        themeToggle.addEventListener('click', function(e) {
            e.preventDefault();
            document.body.classList.toggle('dark-theme');
            localStorage.setItem('dark-theme', document.body.classList.contains('dark-theme'));
        });
        
        // Apply saved theme preference
        if (localStorage.getItem('dark-theme') === 'true') {
            document.body.classList.add('dark-theme');
        }
    }
    
    // Logout functionality
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = '/university-library-management/api/auth.php?action=logout';
        });
    }
});