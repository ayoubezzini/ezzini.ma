const normalize = (text) =>
    text
        .toLowerCase()
        .normalize('NFD')
        .replace(/[̀-ͯ]/g, '');

/** Every query word must start a word of the item (accent-insensitive): "dl cv" finds "Download résumé (PDF)". */
const matches = (item, query) => {
    const words = normalize(`${item.label} ${item.keywords ?? ''}`).split(/[^a-z0-9⌘]+/);

    return normalize(query)
        .split(/\s+/)
        .filter(Boolean)
        .every((part) => words.some((word) => word.startsWith(part)));
};

export default (items, groups) => ({
    open: false,
    query: '',
    active: 0,
    items,
    groups,
    isMac: /Mac|iPhone|iPad/.test(navigator.platform),

    get results() {
        return this.query.trim() === '' ? this.items : this.items.filter((item) => matches(item, this.query));
    },

    get grouped() {
        return Object.entries(this.groups)
            .map(([key, label]) => ({ key, label, items: this.results.filter((item) => item.group === key) }))
            .filter((group) => group.items.length > 0);
    },

    init() {
        this.$watch('query', () => (this.active = 0));

        window.addEventListener('keydown', (event) => {
            const typing = event.target.closest('input, textarea, select, [contenteditable]');

            if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 'k') {
                event.preventDefault();
                this.toggle();

                return;
            }

            if (! typing && ! this.open && event.key === '/') {
                event.preventDefault();
                this.show();
            }
        });
    },

    toggle() {
        this.open ? this.hide() : this.show();
    },

    show() {
        this.query = '';
        this.active = 0;
        this.open = true;

        this.$nextTick(() => this.$refs.input.focus());
    },

    hide() {
        this.open = false;
    },

    move(step) {
        const count = this.results.length;

        if (count === 0) {
            return;
        }

        this.active = (this.active + step + count) % count;

        this.$nextTick(() => this.$refs.list.querySelector('[aria-selected="true"]')?.scrollIntoView({ block: 'nearest' }));
    },

    indexOf(item) {
        return this.results.indexOf(item);
    },

    run(item = this.results[this.active]) {
        if (! item) {
            return;
        }

        this.hide();

        if (item.href) {
            item.external ? window.open(item.href, '_blank', 'noopener') : (window.location.href = item.href);

            return;
        }

        const actions = {
            copy: () => this.$copy(item.value, item.confirmation),
            theme: () => this.$store.theme.toggle(),
            grid: () => this.$store.grid.toggle(),
        };

        actions[item.action]?.();
    },
});
