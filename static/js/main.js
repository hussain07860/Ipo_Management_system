// Auto-dismiss alerts
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // Password visibility toggle
    const passwordToggles = document.querySelectorAll('.password-toggle');
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const wrapper = this.closest('.form-input-wrapper');
            const input = wrapper.querySelector('input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Test login buttons - FIXED
    const testLoginButtons = document.querySelectorAll('[data-email], [data-username]');
    testLoginButtons.forEach(button => {
        button.addEventListener('click', function() {
            const email = this.dataset.email;
            const username = this.dataset.username;
            const password = this.dataset.password;

            if (email) {
                const emailInput = document.querySelector('input[name="email"]');
                if (emailInput) emailInput.value = email;
            }
            if (username) {
                const usernameInput = document.querySelector('input[name="username"]');
                if (usernameInput) usernameInput.value = username;
            }
            if (password) {
                const passwordInput = document.querySelector('input[name="password"]');
                if (passwordInput) passwordInput.value = password;
            }
        });
    });

    // Password strength checker
    const passwordInputs = document.querySelectorAll('input[name="password"]');
    passwordInputs.forEach(input => {
        // Only add validation on register page
        if (window.location.pathname.includes('register')) {
            const strengthBar = document.createElement('div');
            strengthBar.className = 'password-strength';
            input.parentElement.appendChild(strengthBar);

            const requirements = document.createElement('ul');
            requirements.className = 'password-requirements';
            requirements.innerHTML = `
                <li id="req-length">At least 12 characters</li>
                <li id="req-upper">One uppercase letter</li>
                <li id="req-lower">One lowercase letter</li>
                <li id="req-number">One number</li>
                <li id="req-special">One special character</li>
            `;
            input.parentElement.appendChild(requirements);

            input.addEventListener('input', function() {
                const value = this.value;
                let strength = 0;

                // Check length
                const hasLength = value.length >= 12;
                document.getElementById('req-length').className = hasLength ? 'valid' : 'invalid';
                if (hasLength) strength++;

                // Check uppercase
                const hasUpper = /[A-Z]/.test(value);
                document.getElementById('req-upper').className = hasUpper ? 'valid' : 'invalid';
                if (hasUpper) strength++;

                // Check lowercase
                const hasLower = /[a-z]/.test(value);
                document.getElementById('req-lower').className = hasLower ? 'valid' : 'invalid';
                if (hasLower) strength++;

                // Check number
                const hasNumber = /[0-9]/.test(value);
                document.getElementById('req-number').className = hasNumber ? 'valid' : 'invalid';
                if (hasNumber) strength++;

                // Check special character
                const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(value);
                document.getElementById('req-special').className = hasSpecial ? 'valid' : 'invalid';
                if (hasSpecial) strength++;

                // Update strength bar
                strengthBar.className = 'password-strength';
                if (strength <= 2) {
                    strengthBar.classList.add('weak');
                } else if (strength <= 4) {
                    strengthBar.classList.add('medium');
                } else {
                    strengthBar.classList.add('strong');
                }

                // Validate form
                const isValid = hasLength && hasUpper && hasLower && hasNumber && hasSpecial;
                this.setCustomValidity(isValid ? '' : 'Password does not meet requirements');
            });
        }
    });

    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Number input validation
    const numberInputs = document.querySelectorAll('input[type="number"]');
    numberInputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.value < 0) this.value = 0;
        });
    });

    // Confirm before approve
    const approveButtons = document.querySelectorAll('form[action*="approve"] button[type="submit"]');
    approveButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const form = this.closest('form');
            const shares = form.querySelector('input[name="shares"]').value;
            if (!confirm(`Approve ${shares} shares?`)) {
                e.preventDefault();
            }
        });
    });

    // Animate cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.card-custom, .stat-card, .ipo-card').forEach(card => {
        observer.observe(card);
    });
});


// ==================== ADVANCED ANIMATIONS ====================

// Number Counter Animation
function animateValue(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        const value = Math.floor(progress * (end - start) + start);
        element.textContent = '₹' + value.toLocaleString('en-IN');
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

// Animate stat card values on page load
document.addEventListener('DOMContentLoaded', function() {
    const statValues = document.querySelectorAll('.stat-card-value');
    statValues.forEach(stat => {
        const text = stat.textContent;
        const match = text.match(/[\d,]+/);
        if (match) {
            const value = parseInt(match[0].replace(/,/g, ''));
            if (!isNaN(value) && value > 100) {
                stat.textContent = '₹0';
                setTimeout(() => {
                    animateValue(stat, 0, value, 1500);
                }, 300);
            }
        }
    });
});

// Intersection Observer for scroll animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe all cards
document.querySelectorAll('.stat-card, .ipo-card, .table-card').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
    observer.observe(card);
});

// Smooth scroll for anchor links
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
function createRipple(event) {
    const button = event.currentTarget;
    const ripple = document.createElement('span');
    const diameter = Math.max(button.clientWidth, button.clientHeight);
    const radius = diameter / 2;

    ripple.style.width = ripple.style.height = `${diameter}px`;
    ripple.style.left = `${event.clientX - button.offsetLeft - radius}px`;
    ripple.style.top = `${event.clientY - button.offsetTop - radius}px`;
    ripple.classList.add('ripple');

    const rippleElement = button.getElementsByClassName('ripple')[0];
    if (rippleElement) {
        rippleElement.remove();
    }

    button.appendChild(ripple);
}

// Add ripple CSS
const style = document.createElement('style');
style.textContent = `
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Apply ripple to all buttons
document.querySelectorAll('.btn-primary, .btn-secondary, .btn-apply, .btn-icon').forEach(button => {
    button.style.position = 'relative';
    button.style.overflow = 'hidden';
    button.addEventListener('click', createRipple);
});

// Add ripple effect to buttons
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn && !submitBtn.disabled) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            submitBtn.style.opacity = '0.7';
        }
    });
});

// Typing effect for page titles
function typeWriter(element, text, speed = 50) {
    let i = 0;
    element.textContent = '';
    function type() {
        if (i < text.length) {
            element.textContent += text.charAt(i);
            i++;
            setTimeout(type, speed);
        }
    }
    type();
}

// Apply typing effect to main titles on first load
if (!sessionStorage.getItem('titleAnimated')) {
    const mainTitle = document.querySelector('.page-title');
    if (mainTitle) {
        const originalText = mainTitle.textContent;
        typeWriter(mainTitle, originalText, 50);
        sessionStorage.setItem('titleAnimated', 'true');
    }
}

// Shake animation for invalid inputs
document.querySelectorAll('input').forEach(input => {
    input.addEventListener('invalid', function() {
        this.classList.add('shake');
        setTimeout(() => {
            this.classList.remove('shake');
        }, 500);
    });
});

// Add shake animation CSS
const shakeStyle = document.createElement('style');
shakeStyle.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    .shake {
        animation: shake 0.5s;
        border-color: var(--accent-red) !important;
    }
`;
document.head.appendChild(shakeStyle);

// Progress bar for page loading
window.addEventListener('load', function() {
    const progressBar = document.createElement('div');
    progressBar.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 0;
        height: 3px;
        background: linear-gradient(90deg, #4c6fff, #00d4aa);
        z-index: 99999;
        transition: width 0.3s ease;
    `;
    document.body.appendChild(progressBar);

    let width = 0;
    const interval = setInterval(() => {
        width += 10;
        progressBar.style.width = width + '%';
        if (width >= 100) {
            clearInterval(interval);
            setTimeout(() => {
                progressBar.style.opacity = '0';
                setTimeout(() => progressBar.remove(), 300);
            }, 200);
        }
    }, 30);
});

// Tooltip functionality
document.querySelectorAll('[data-tooltip]').forEach(element => {
    element.addEventListener('mouseenter', function(e) {
        const tooltip = document.createElement('div');
        tooltip.className = 'custom-tooltip';
        tooltip.textContent = this.getAttribute('data-tooltip');
        tooltip.style.cssText = `
            position: absolute;
            background: var(--bg-card);
            color: var(--text-primary);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            z-index: 10000;
            pointer-events: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            border: 1px solid var(--border-color);
            animation: fadeIn 0.2s ease;
        `;
        document.body.appendChild(tooltip);

        const rect = this.getBoundingClientRect();
        tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
        tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';

        this._tooltip = tooltip;
    });

    element.addEventListener('mouseleave', function() {
        if (this._tooltip) {
            this._tooltip.remove();
            this._tooltip = null;
        }
    });
});

// Confetti effect for successful actions
function createConfetti() {
    const colors = ['#4c6fff', '#00d4aa', '#ffa502', '#ff4757'];
    for (let i = 0; i < 50; i++) {
        const confetti = document.createElement('div');
        confetti.style.cssText = `
            position: fixed;
            width: 10px;
            height: 10px;
            background: ${colors[Math.floor(Math.random() * colors.length)]};
            left: ${Math.random() * 100}%;
            top: -10px;
            opacity: 1;
            transform: rotate(${Math.random() * 360}deg);
            pointer-events: none;
            z-index: 99999;
        `;
        document.body.appendChild(confetti);

        const duration = Math.random() * 3 + 2;
        const xMovement = (Math.random() - 0.5) * 200;
        
        confetti.animate([
            { transform: `translate(0, 0) rotate(0deg)`, opacity: 1 },
            { transform: `translate(${xMovement}px, ${window.innerHeight}px) rotate(${Math.random() * 720}deg)`, opacity: 0 }
        ], {
            duration: duration * 1000,
            easing: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)'
        }).onfinish = () => confetti.remove();
    }
}

// Trigger confetti on successful form submissions
const successAlerts = document.querySelectorAll('.alert.success');
if (successAlerts.length > 0) {
    createConfetti();
}
