import '../sass/app.scss';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// Splash screen loader logic for page transitions
window.addEventListener('DOMContentLoaded', function() {
    const loader = document.getElementById('page-loader');
    if (loader) loader.classList.add('hide');
    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', function(e) {
            if (
                this.hostname === window.location.hostname &&
                this.getAttribute('href') &&
                !this.getAttribute('href').startsWith('#') &&
                !this.hasAttribute('target')
            ) {
                loader.classList.remove('hide');
            }
        });
    });
});
window.addEventListener('pageshow', function() {
    const loader = document.getElementById('page-loader');
    if (loader) loader.classList.add('hide');
});

window.updateCartCount = function(count) {
    const el = document.getElementById('cart-count');
    if (typeof count !== 'undefined') {
        if (el) el.textContent = count;
    } else {
        // fallback: fetch from server
        fetch('/cart/count')
            .then(res => res.json())
            .then(data => {
                if (data.cart_count !== undefined && el) {
                    el.textContent = data.cart_count;
                }
            });
    }
};
