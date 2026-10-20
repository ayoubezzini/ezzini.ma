let turnstileLoading = null;

/** Turnstile is only downloaded when the form scrolls into view: no third-party request for visitors who never get there. */
const loadTurnstile = () => {
    turnstileLoading ??= new Promise((resolve, reject) => {
        const script = document.createElement('script');

        script.src = 'https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit';
        script.async = true;
        script.onload = () => resolve(window.turnstile);
        script.onerror = reject;

        document.head.append(script);
    });

    return turnstileLoading;
};

export default ({ endpoint, siteKey, locale, messages }) => ({
    state: 'idle',
    feedback: '',
    errors: {},
    token: '',
    widget: null,

    async prepare() {
        if (! siteKey || this.widget !== null) {
            return;
        }

        try {
            const turnstile = await loadTurnstile();

            this.widget = turnstile.render(this.$refs.turnstile, {
                sitekey: siteKey,
                action: 'contact',
                language: locale,
                theme: document.documentElement.dataset.theme === 'dark' ? 'dark' : 'light',
                size: 'flexible',
                callback: (token) => (this.token = token),
                'expired-callback': () => (this.token = ''),
            });
        } catch {
            this.widget = null;
        }
    },

    error(field) {
        return this.errors[field]?.[0] ?? '';
    },

    async submit() {
        if (this.state === 'sending') {
            return;
        }

        this.state = 'sending';
        this.errors = {};
        this.feedback = '';

        const data = Object.fromEntries(new FormData(this.$el));

        data['cf-turnstile-response'] = this.token;

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                body: JSON.stringify(data),
            });

            const body = await response.json().catch(() => ({}));

            if (response.ok) {
                this.state = 'sent';
                this.feedback = body.message;
                this.$el.reset();

                return;
            }

            this.state = 'failed';
            this.errors = body.errors ?? {};
            this.feedback = response.status === 429 ? messages.throttled : (body.errors ? '' : (body.message ?? messages.generic));

            this.$nextTick(() => this.$el.querySelector('[aria-invalid="true"]')?.focus());
        } catch {
            this.state = 'failed';
            this.feedback = messages.generic;
        } finally {
            if (this.widget !== null && this.state !== 'sent') {
                window.turnstile.reset(this.widget);
                this.token = '';
            }
        }
    },
});
