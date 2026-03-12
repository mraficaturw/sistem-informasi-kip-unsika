// Bootstrap JS
import * as bootstrap from 'bootstrap';

// Make bootstrap available globally
window.bootstrap = bootstrap;

// Axios
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// CSRF Token
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

// =============================================
// DOM Ready
// =============================================
document.addEventListener('DOMContentLoaded', function () {

    // --- Auto-dismiss alerts after 5 seconds ---
    const alerts = document.querySelectorAll('.alert-dismissible.auto-dismiss');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }, 5000);
    });

    // --- Navbar scroll behavior ---
    const navbar = document.getElementById('mainNavbar');
    if (navbar && navbar.classList.contains('transparent')) {
        const handleNavbarScroll = () => {
            if (window.scrollY > 80) {
                navbar.classList.remove('transparent');
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
                navbar.classList.add('transparent');
            }
        };
        window.addEventListener('scroll', handleNavbarScroll, { passive: true });
        handleNavbarScroll(); // Initial check
    }

    // --- Back to Top button ---
    const backToTop = document.getElementById('backToTop');
    if (backToTop) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 400) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        }, { passive: true });

        backToTop.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // --- Counter animation ---
    const counters = document.querySelectorAll('.stat-number[data-count]');
    if (counters.length > 0) {
        let counterAnimated = false;

        const animateCounters = () => {
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-count'));
                if (target === 0) {
                    counter.textContent = '0';
                    return;
                }

                const duration = 2000;
                const step = target / (duration / 16);
                let current = 0;

                const updateCounter = () => {
                    current += step;
                    if (current < target) {
                        counter.textContent = Math.floor(current).toLocaleString('id-ID');
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target.toLocaleString('id-ID');
                    }
                };

                requestAnimationFrame(updateCounter);
            });
        };

        const statsSection = document.getElementById('stats');
        if (statsSection) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !counterAnimated) {
                        counterAnimated = true;
                        animateCounters();
                    }
                });
            }, { threshold: 0.3 });
            observer.observe(statsSection);
        }
    }

    // --- News carousel horizontal scroll ---
    const scrollWrapper = document.getElementById('newsScrollWrapper');
    const scrollLeftBtn = document.getElementById('newsScrollLeft');
    const scrollRightBtn = document.getElementById('newsScrollRight');

    if (scrollWrapper && scrollLeftBtn && scrollRightBtn) {
        const scrollAmount = 370;

        scrollLeftBtn.addEventListener('click', () => {
            scrollWrapper.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        });

        scrollRightBtn.addEventListener('click', () => {
            scrollWrapper.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        });
    }

});
