// Enhanced Main JavaScript with Professional Animations
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap components
    initBootstrapComponents();
    
    // Initialize navigation
    initNavigation();
    
    // Initialize animations
    initAnimations();
    
    // Initialize interactive features
    initInteractiveFeatures();
    
    // Handle window resize for sidebar
    initResponsiveHandling();
    
    // Prevent horizontal scroll
    preventHorizontalScroll();
});

function initBootstrapComponents() {
    // Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    });
}

function initNavigation() {
    const sidebar = document.getElementById('sidebar');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileOverlay = document.getElementById('mobileOverlay');
    const bottomNavItems = document.querySelectorAll('.bottom-nav-item');

    // Mobile menu toggle
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            sidebar.classList.toggle('mobile-open');
            if (mobileOverlay) {
                mobileOverlay.classList.toggle('active');
            }
            document.body.style.overflow = sidebar.classList.contains('mobile-open') ? 'hidden' : '';
        });
    }

    // Mobile overlay click to close sidebar
    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', function() {
            sidebar.classList.remove('mobile-open');
            mobileOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    }

    // Bottom navigation active state
    bottomNavItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const targetUrl = this.getAttribute('href') || this.getAttribute('data-target');
            
            if (targetUrl) {
                // Add bounce animation
                this.style.animation = 'bounce 0.5s ease';
                setTimeout(() => {
                    this.style.animation = '';
                    window.location.href = targetUrl;
                }, 300);
            }
        });
    });
}

function initResponsiveHandling() {
    // Handle window resize to ensure sidebar is visible on desktop
    window.addEventListener('resize', function() {
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        
        if (window.innerWidth >= 769) {
            // Desktop - ensure sidebar is visible
            sidebar.classList.remove('mobile-open');
            if (mobileOverlay) {
                mobileOverlay.classList.remove('active');
            }
            document.body.style.overflow = '';
        } else {
            // Mobile - ensure sidebar is hidden by default
            sidebar.classList.remove('mobile-open');
            if (mobileOverlay) {
                mobileOverlay.classList.remove('active');
            }
        }
        
        // Re-check for horizontal scroll issues
        preventHorizontalScroll();
    });
}

function preventHorizontalScroll() {
    // Prevent horizontal scrolling
    document.body.style.overflowX = 'hidden';
    document.documentElement.style.overflowX = 'hidden';
    
    // Ensure all containers don't cause horizontal scroll
    const containers = document.querySelectorAll('.container, .container-fluid, .row, .card, .table-responsive');
    containers.forEach(container => {
        container.style.maxWidth = '100%';
        container.style.overflowX = 'hidden';
    });
}

function initAnimations() {
    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                
                // Add specific animations based on element type
                if (entry.target.classList.contains('stat-card')) {
                    entry.target.style.animation = 'fadeInUp 0.6s ease-out';
                }
            }
        });
    }, observerOptions);

    // Observe elements for animation
    document.querySelectorAll('.stat-card, .card, .table tbody tr').forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        observer.observe(el);
    });

    // Animate progress bars
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0';
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });
}

function initInteractiveFeatures() {
    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Search functionality with debounce
    initSearchFunctionality();

    // Export functionality
    initExportFunctionality();

    // Form validation enhancements
    initFormValidation();

    // Real-time notifications
    initNotifications();
}

function initSearchFunctionality() {
    let searchTimeout;
    const searchInputs = document.querySelectorAll('[data-search]');
    
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const searchTerm = this.value.toLowerCase();
                const table = this.closest('.card').querySelector('table');
                
                if (table) {
                    const rows = table.querySelectorAll('tbody tr');
                    rows.forEach((row, index) => {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            row.style.display = '';
                            row.style.animation = `fadeInUp 0.3s ease ${index * 0.05}s`;
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }
            }, 300);
        });
    });
}

function initExportFunctionality() {
    const exportButtons = document.querySelectorAll('[data-export]');
    exportButtons.forEach(button => {
        button.addEventListener('click', function() {
            const format = this.dataset.export;
            const table = this.closest('.card').querySelector('table');
            
            if (table && format === 'csv') {
                exportTableToCSV(table, 'export.csv');
                
                // Add success feedback
                this.innerHTML = '<i class="bi bi-check-lg"></i> Exported!';
                setTimeout(() => {
                    this.innerHTML = '<i class="bi bi-download"></i> Export';
                }, 2000);
            }
        });
    });
}

function initFormValidation() {
    const forms = document.querySelectorAll('form[novalidate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                
                // Add shake animation to invalid fields
                const invalidFields = this.querySelectorAll(':invalid');
                invalidFields.forEach(field => {
                    field.classList.add('is-invalid');
                    field.style.animation = 'shake 0.5s ease-in-out';
                    setTimeout(() => {
                        field.style.animation = '';
                    }, 500);
                });
            }
            
            this.classList.add('was-validated');
        });
    });
}

function initNotifications() {
    function checkNotifications() {
        // Simulate real-time updates
        const notificationBadge = document.getElementById('notificationBadge');
        if (notificationBadge && Math.random() > 0.7) {
            const count = parseInt(notificationBadge.textContent) + 1;
            notificationBadge.textContent = count;
            notificationBadge.classList.add('animate-pulse');
            
            setTimeout(() => {
                notificationBadge.classList.remove('animate-pulse');
            }, 2000);
        }
    }

    // Check for notifications every 30 seconds
    setInterval(checkNotifications, 30000);
}

// Utility Functions
function exportTableToCSV(table, filename) {
    const csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = [], cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length; j++) {
            let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ');
            data = data.replace(/"/g, '""');
            row.push('"' + data + '"');
        }
        
        csv.push(row.join(','));
    }
    
    // Download CSV file
    const csvFile = new Blob([csv.join('\n')], {type: 'text/csv'});
    const downloadLink = document.createElement('a');
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// Global loading indicator
function showLoading() {
    const spinner = document.getElementById('loadingSpinner');
    if (spinner) spinner.style.display = 'block';
}

function hideLoading() {
    const spinner = document.getElementById('loadingSpinner');
    if (spinner) spinner.style.display = 'none';
}

// Add shake animation to CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20%, 60% { transform: translateX(-5px); }
        40%, 80% { transform: translateX(5px); }
    }
`;
document.head.appendChild(style);

// Show loading on form submissions
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            showLoading();
        });
    });
});

// Initialize on load
window.addEventListener('load', function() {
    preventHorizontalScroll();
});