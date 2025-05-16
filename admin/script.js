
    function toggleSidebar() {
        let sidebar = document.querySelector('.sidebar');
        let content = document.querySelector('.main-content');
        
        sidebar.classList.toggle('closed');  // Toggle sidebar visibility
        content.classList.toggle('shifted'); // Move content when sidebar closes
    }

