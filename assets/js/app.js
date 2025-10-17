// Main JavaScript file for Delhi Modern Living

// Utility functions
const Utils = {
    // Show toast notification
    showToast: function(message, type = 'info', duration = 5000) {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <div class="flex items-center justify-between">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Show toast
        setTimeout(() => toast.classList.add('show'), 100);
        
        // Auto remove
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, duration);
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
    },
    
    // Validate email
    isValidEmail: function(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    },
    
    // Validate phone number (Indian format)
    isValidPhone: function(phone) {
        const phoneRegex = /^[6-9]\d{9}$/;
        return phoneRegex.test(phone.replace(/\D/g, ''));
    }
};

// Cart functionality
const Cart = {
    // Add item to cart
    addItem: function(roomId, startDate, endDate, monthsCount, csrfToken) {
        // Validate inputs
        if (!roomId || !startDate || !endDate || !monthsCount || !csrfToken) {
            Utils.showToast('Missing required information', 'error');
            return Promise.resolve({ success: false, message: 'Invalid input' });
        }

        const BASE_PATH = (typeof window !== 'undefined' && window.BASE_PATH) ? window.BASE_PATH : '/demo-pg-01-main';
        const formData = new FormData();
        formData.append('room_id', roomId);
        formData.append('start_date', startDate);
        formData.append('end_date', endDate);
        formData.append('months_count', monthsCount);
        formData.append('csrf_token', csrfToken);

        return fetch(`${BASE_PATH}/cart/add`, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                this.updateCartCount(data.cart_count);
                Utils.showToast(data.message || 'Item added to cart', 'success');
            } else {
                Utils.showToast(data.message || 'Failed to add item', 'error');
            }
            return data;
        })
        .catch(error => {
            console.error('Cart error:', error);
            Utils.showToast('Failed to add item to cart. Please try again.', 'error');
            return { success: false, message: error.message };
        });
    },
    
    // Remove item from cart
    removeItem: function(roomId, csrfToken) {
        if (!roomId || !csrfToken) {
            Utils.showToast('Missing required information', 'error');
            return Promise.resolve({ success: false, message: 'Invalid input' });
        }

        const BASE_PATH = (typeof window !== 'undefined' && window.BASE_PATH) ? window.BASE_PATH : '/demo-pg-01-main';
        const formData = new FormData();
        formData.append('room_id', roomId);
        formData.append('csrf_token', csrfToken);

        return fetch(`${BASE_PATH}/cart/remove`, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                this.updateCartCount(data.cart_count);
                Utils.showToast(data.message || 'Item removed from cart', 'success');
            } else {
                Utils.showToast(data.message || 'Failed to remove item', 'error');
            }
            return data;
        })
        .catch(error => {
            console.error('Cart error:', error);
            Utils.showToast('Failed to remove item. Please try again.', 'error');
            return { success: false, message: error.message };
        });
    },
    
    // Update cart count in navigation
    updateCartCount: function(count) {
        const cartCountElement = document.getElementById('cart-count');
        if (cartCountElement) {
            if (count > 0) {
                cartCountElement.textContent = count;
                cartCountElement.classList.remove('hidden');
            } else {
                cartCountElement.classList.add('hidden');
            }
        }
    }
};

// Form validation
const FormValidator = {
    // Validate form
    validate: function(form, rules) {
        const errors = {};
        
        for (const [field, rule] of Object.entries(rules)) {
            const input = form.querySelector(`[name="${field}"]`);
            if (!input) continue;
            
            const value = input.value.trim();
            const ruleList = rule.split('|');
            
            for (const singleRule of ruleList) {
                if (singleRule === 'required' && !value) {
                    errors[field] = `${this.getFieldLabel(field)} is required`;
                    break;
                }
                
                if (singleRule.startsWith('min:')) {
                    const minLength = parseInt(singleRule.split(':')[1]);
                    if (value.length < minLength) {
                        errors[field] = `${this.getFieldLabel(field)} must be at least ${minLength} characters`;
                        break;
                    }
                }
                
                if (singleRule === 'email' && value && !Utils.isValidEmail(value)) {
                    errors[field] = `${this.getFieldLabel(field)} must be a valid email`;
                    break;
                }
                
                if (singleRule === 'phone' && value && !Utils.isValidPhone(value)) {
                    errors[field] = `${this.getFieldLabel(field)} must be a valid phone number`;
                    break;
                }
            }
        }
        
        return errors;
    },
    
    // Get field label
    getFieldLabel: function(field) {
        return field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    },
    
    // Show validation errors
    showErrors: function(form, errors) {
        // Clear previous errors
        form.querySelectorAll('.error-message').forEach(el => el.remove());
        form.querySelectorAll('.border-red-500').forEach(el => {
            el.classList.remove('border-red-500');
            el.classList.add('border-gray-300');
        });
        
        // Show new errors
        for (const [field, message] of Object.entries(errors)) {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.remove('border-gray-300');
                input.classList.add('border-red-500');
                
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message text-red-600 text-sm mt-1';
                errorDiv.textContent = message;
                
                input.parentNode.appendChild(errorDiv);
            }
        }
    }
};

// Image gallery functionality
const ImageGallery = {
    init: function() {
        const thumbnails = document.querySelectorAll('.thumbnail-grid img');
        const mainImage = document.getElementById('main-image');
        
        if (!mainImage || !thumbnails.length) return;
        
        thumbnails.forEach((thumbnail, index) => {
            thumbnail.addEventListener('click', () => {
                this.changeMainImage(thumbnail, index, thumbnails, mainImage);
            });
        });
    },
    
    changeMainImage: function(thumbnail, index, thumbnails, mainImage) {
        // Update main image
        mainImage.src = thumbnail.src;
        
        // Update thumbnail selection
        thumbnails.forEach((thumb, i) => {
            if (i === index) {
                thumb.classList.add('ring-2', 'ring-primary-500');
            } else {
                thumb.classList.remove('ring-2', 'ring-primary-500');
            }
        });
    }
};

// Search functionality
const Search = {
    init: function() {
        const searchInput = document.getElementById('search-input');
        if (!searchInput) return;
        
        const debouncedSearch = Utils.debounce(this.performSearch.bind(this), 300);
        searchInput.addEventListener('input', debouncedSearch);
    },
    
    performSearch: function(event) {
        const query = event.target.value.trim();
        if (query.length < 2) return;
        
        // Implement search logic here
        console.log('Searching for:', query);
    }
};

// Smooth scrolling for anchor links
const SmoothScroll = {
    init: function() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }
};

// Lazy loading for images
const LazyLoad = {
    init: function() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        observer.unobserve(img);
                    }
                });
            });
            
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }
};

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize components
    ImageGallery.init();
    Search.init();
    SmoothScroll.init();
    LazyLoad.init();
    
    // Set minimum date for date inputs to today
    document.querySelectorAll('input[type="date"]').forEach(input => {
        if (!input.min) {
            input.min = new Date().toISOString().split('T')[0];
        }
    });
    
    // Auto-hide flash messages
    setTimeout(() => {
        document.querySelectorAll('#flash-message').forEach(msg => {
            msg.style.display = 'none';
        });
    }, 5000);
    
    // Form submission with loading state
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                
                // Re-enable after 10 seconds as fallback
                setTimeout(() => {
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                }, 10000);
            }
        });
    });
});

// Export for global use
window.Utils = Utils;
window.Cart = Cart;
window.FormValidator = FormValidator;
