import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const menuButton = document.querySelector('[data-menu-button]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');

    menuButton?.addEventListener('click', () => {
        const isOpen = menuButton.getAttribute('aria-expanded') === 'true';
        menuButton.setAttribute('aria-expanded', String(! isOpen));
        mobileMenu?.classList.toggle('hidden', isOpen);
    });
});
