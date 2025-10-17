// Admin Dashboard JavaScript

// Admin utility functions
const AdminUtils = {
    // Show admin notification
    showNotification: function(message, type = 'info', duration = 5000) {
        const notification = document.createElement('div');
        notification.className = `admin-notification ${type}`;
        notification.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-${this.getNotificationIcon(type)} text-lg"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">${message}</p>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" 
                            class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Show notification
        setTimeout(() => notification.classList.add('show'), 100);
        
        // Auto remove
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, duration);
    },
    
    getNotificationIcon: function(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    },
    
    // Confirm dialog
    confirm: function(message, callback) {
        if (window.confirm(message)) {
            callback();
        }
    },
    
    // Loading state
    setLoading: function(element, loading = true) {
        if (loading) {
            element.classList.add('admin-loading');
            element.disabled = true;
        } else {
            element.classList.remove('admin-loading');
            element.disabled = false;
        }
    },
    
    // Format currency
    formatCurrency: function(amount) {
        return new Intl.NumberFormat('en-IN', {
            style: 'currency',
            currency: 'INR',
            minimumFractionDigits: 0
        }).format(amount);
    },
    
    // Format date
    formatDate: function(date, options = {}) {
        const defaultOptions = {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        };
        return new Intl.DateTimeFormat('en-IN', {...defaultOptions, ...options}).format(new Date(date));
    },
    
    // Debounce function
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
};

// Admin table functionality
const AdminTable = {
    // Initialize sortable tables
    initSortable: function() {
        document.querySelectorAll('.sortable th').forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', () => {
                this.sortTable(header);
            });
        });
    },
    
    sortTable: function(header) {
        const table = header.closest('table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const columnIndex = Array.from(header.parentNode.children).indexOf(header);
        const isAscending = !header.classList.contains('sort-asc');
        
        // Clear existing sort classes
        header.parentNode.querySelectorAll('th').forEach(th => {
            th.classList.remove('sort-asc', 'sort-desc');
        });
        
        // Add new sort class
        header.classList.add(isAscending ? 'sort-asc' : 'sort-desc');
        
        // Sort rows
        rows.sort((a, b) => {
            const aValue = a.cells[columnIndex].textContent.trim();
            const bValue = b.cells[columnIndex].textContent.trim();
            
            // Try to parse as numbers
            const aNum = parseFloat(aValue.replace(/[^\d.-]/g, ''));
            const bNum = parseFloat(bValue.replace(/[^\d.-]/g, ''));
            
            if (!isNaN(aNum) && !isNaN(bNum)) {
                return isAscending ? aNum - bNum : bNum - aNum;
            }
            
            // String comparison
            return isAscending ? 
                aValue.localeCompare(bValue) : 
                bValue.localeCompare(aValue);
        });
        
        // Reorder rows in DOM
        rows.forEach(row => tbody.appendChild(row));
    },
    
    // Initialize row selection
    initRowSelection: function() {
        const selectAllCheckbox = document.getElementById('select-all');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', () => {
                rowCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
                this.updateBulkActions();
            });
        }
        
        rowCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                this.updateBulkActions();
                this.updateSelectAll();
            });
        });
    },
    
    updateBulkActions: function() {
        const selectedRows = document.querySelectorAll('.row-checkbox:checked');
        const bulkActions = document.getElementById('bulk-actions');
        
        if (bulkActions) {
            if (selectedRows.length > 0) {
                bulkActions.classList.remove('hidden');
                bulkActions.querySelector('.selected-count').textContent = selectedRows.length;
            } else {
                bulkActions.classList.add('hidden');
            }
        }
    },
    
    updateSelectAll: function() {
        const selectAllCheckbox = document.getElementById('select-all');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = checkedBoxes.length === rowCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < rowCheckboxes.length;
        }
    }
};

// Admin form functionality
const AdminForm = {
    // Initialize form validation
    initValidation: function() {
        document.querySelectorAll('form[data-validate]').forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                }
            });
        });
    },
    
    validateForm: function(form) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                this.showFieldError(field, 'This field is required');
                isValid = false;
            } else {
                this.clearFieldError(field);
            }
        });
        
        // Email validation
        const emailFields = form.querySelectorAll('input[type="email"]');
        emailFields.forEach(field => {
            if (field.value && !this.isValidEmail(field.value)) {
                this.showFieldError(field, 'Please enter a valid email address');
                isValid = false;
            }
        });
        
        return isValid;
    },
    
    showFieldError: function(field, message) {
        field.classList.add('error');
        
        let errorElement = field.parentNode.querySelector('.admin-form-error');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'admin-form-error';
            field.parentNode.appendChild(errorElement);
        }
        errorElement.textContent = message;
    },
    
    clearFieldError: function(field) {
        field.classList.remove('error');
        const errorElement = field.parentNode.querySelector('.admin-form-error');
        if (errorElement) {
            errorElement.remove();
        }
    },
    
    isValidEmail: function(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
};

// Admin charts functionality
const AdminCharts = {
    // Initialize all charts
    init: function() {
        this.initRevenueChart();
        this.initUserGrowthChart();
        this.initBookingStatusChart();
    },
    
    initRevenueChart: function() {
        const ctx = document.getElementById('revenueChart');
        if (!ctx) return;
        
        // This would be populated with real data from the server
        const data = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Revenue',
                data: [12000, 19000, 15000, 25000, 22000, 30000],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        };
        
        new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₹' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Revenue: ₹' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    },
    
    initUserGrowthChart: function() {
        const ctx = document.getElementById('userGrowthChart');
        if (!ctx) return;
        
        const data = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'New Users',
                data: [65, 78, 90, 81, 95, 120],
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                borderColor: 'rgb(16, 185, 129)',
                borderWidth: 1
            }]
        };
        
        new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    },
    
    initBookingStatusChart: function() {
        const ctx = document.getElementById('bookingStatusChart');
        if (!ctx) return;
        
        const data = {
            labels: ['Confirmed', 'Pending', 'Cancelled'],
            datasets: [{
                data: [75, 15, 10],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderColor: [
                    'rgb(16, 185, 129)',
                    'rgb(245, 158, 11)',
                    'rgb(239, 68, 68)'
                ],
                borderWidth: 1
            }]
        };
        
        new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
};

// Admin search functionality
const AdminSearch = {
    init: function() {
        const searchInputs = document.querySelectorAll('.admin-search');
        searchInputs.forEach(input => {
            const debouncedSearch = AdminUtils.debounce(this.performSearch.bind(this), 300);
            input.addEventListener('input', debouncedSearch);
        });
    },
    
    performSearch: function(event) {
        const query = event.target.value.toLowerCase();
        const targetTable = event.target.dataset.target;
        
        if (targetTable) {
            const table = document.getElementById(targetTable);
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    }
};

// Initialize admin functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all admin components
    AdminTable.initSortable();
    AdminTable.initRowSelection();
    AdminForm.initValidation();
    AdminSearch.init();
    
    // Initialize charts if Chart.js is available
    if (typeof Chart !== 'undefined') {
        AdminCharts.init();
    }
    
    // Auto-hide flash messages
    setTimeout(() => {
        document.querySelectorAll('.admin-flash-message').forEach(msg => {
            msg.style.display = 'none';
        });
    }, 5000);
    
    // Initialize tooltips
    document.querySelectorAll('[data-tooltip]').forEach(element => {
        element.title = element.dataset.tooltip;
    });
    
    // Handle AJAX forms
    document.querySelectorAll('form[data-ajax]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            
            AdminUtils.setLoading(submitBtn, true);
            
            fetch(this.action, {
                method: this.method,
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                AdminUtils.setLoading(submitBtn, false);
                
                if (data.success) {
                    AdminUtils.showNotification(data.message, 'success');
                    if (data.redirect) {
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1000);
                    }
                } else {
                    AdminUtils.showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                AdminUtils.setLoading(submitBtn, false);
                AdminUtils.showNotification('An error occurred. Please try again.', 'error');
                console.error('Error:', error);
            });
        });
    });
});

// Export for global use
window.AdminUtils = AdminUtils;
window.AdminTable = AdminTable;
window.AdminForm = AdminForm;
window.AdminCharts = AdminCharts;
