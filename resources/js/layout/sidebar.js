function toggleSubmenu(submenuId) {
    const submenu = document.getElementById(submenuId);
    const isOpen = submenu.style.display === 'block';
    
    // Fecha todos os submenus
    document.querySelectorAll('.submenu').forEach(menu => {
        menu.style.display = 'none';
    });
    
    // Abre/fecha o submenu clicado
    submenu.style.display = isOpen ? 'none' : 'block';
}

// Marca o item ativo no menu
document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;
    const menuItems = document.querySelectorAll('.nav-link, .submenu a');
    
    menuItems.forEach(item => {
        if (item.getAttribute('href') === currentPath) {
            item.classList.add('active');
            // Abre o submenu pai se existir
            const parentSubmenu = item.closest('.submenu');
            if (parentSubmenu) {
                parentSubmenu.style.display = 'block';
            }
        }
    });
}); 