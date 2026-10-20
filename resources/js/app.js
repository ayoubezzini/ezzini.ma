import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import intersect from '@alpinejs/intersect';
import contactForm from './contact-form';
import palette from './palette';

const remember = (key, value) => {
    try {
        localStorage.setItem(key, value);
    } catch {
        // Private browsing: the choice simply won't be remembered.
    }
};

Alpine.plugin(focus);
Alpine.plugin(intersect);

Alpine.store('theme', {
    current: document.documentElement.dataset.theme,

    toggle() {
        this.set(this.current === 'dark' ? 'light' : 'dark');
    },

    set(theme) {
        const root = document.documentElement;

        root.classList.add('theme-switching');
        root.dataset.theme = theme;
        this.current = theme;
        remember('theme', theme);

        // Two frames: the new colors are painted before transitions come back.
        requestAnimationFrame(() => requestAnimationFrame(() => root.classList.remove('theme-switching')));
    },
});

Alpine.store('toast', {
    message: '',
    visible: false,
    timer: null,

    show(message) {
        this.message = message;
        this.visible = true;
        clearTimeout(this.timer);
        this.timer = setTimeout(() => (this.visible = false), 1800);
    },
});

Alpine.store('grid', {
    visible: false,

    toggle() {
        this.visible = !this.visible;
        document.body.classList.toggle('show-grid', this.visible);
    },
});

Alpine.magic('copy', () => async (text, confirmation) => {
    try {
        await navigator.clipboard.writeText(text);
        Alpine.store('toast').show(confirmation);
    } catch {
        window.prompt('', text);
    }
});

Alpine.data('clock', (timeZone, locale) => ({
    time: '',

    init() {
        const format = new Intl.DateTimeFormat(locale, { hour: '2-digit', minute: '2-digit', timeZone });
        const tick = () => (this.time = format.format(new Date()));

        tick();

        // Aligned on the minute, so the clock never lags behind the real one.
        setTimeout(() => {
            tick();
            setInterval(tick, 60_000);
        }, 60_000 - (Date.now() % 60_000));
    },
}));

Alpine.data('header', () => ({
    scrolled: window.scrollY > 4,
    menu: false,

    init() {
        window.addEventListener('scroll', () => (this.scrolled = window.scrollY > 4), { passive: true });
    },

    // The open menu locks the page scroll: close it first, then scroll once the lock is released.
    go(event) {
        const link = event.currentTarget;

        this.menu = false;

        if (link.pathname !== window.location.pathname) {
            return;
        }

        event.preventDefault();

        requestAnimationFrame(() => {
            document.querySelector(link.hash)?.scrollIntoView({ behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' });
            history.replaceState(null, '', link.hash);
        });
    },
}));

Alpine.data('palette', palette);
Alpine.data('contactForm', contactForm);

window.Alpine = Alpine;
Alpine.start();

const revealed = new IntersectionObserver(
    (entries) => {
        entries
            .filter((entry) => entry.isIntersecting)
            .forEach((entry) => {
                entry.target.classList.add('is-revealed');
                revealed.unobserve(entry.target);
            });
    },
    { rootMargin: '0px 0px -8% 0px' },
);

document.querySelectorAll('[data-reveal]').forEach((element) => revealed.observe(element));

document.addEventListener('keydown', (event) => {
    const typing = event.target.closest('input, textarea, select, [contenteditable]');

    if (! typing && event.shiftKey && event.key.toLowerCase() === 'g' && ! event.metaKey && ! event.ctrlKey) {
        Alpine.store('grid').toggle();
    }
});
