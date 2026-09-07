import './bootstrap';

// DEC-020: approved photographic poster until a realistic interactive asset is accepted.

const storageKey = 'teelle-theme';
const validThemes = new Set(['light', 'dark']);
const media = window.matchMedia('(prefers-color-scheme: dark)');

const readStoredTheme = () => {
    try {
        const stored = window.localStorage.getItem(storageKey);

        if (validThemes.has(stored)) {
            return stored;
        }

        if (stored !== null) {
            window.localStorage.removeItem(storageKey);
        }
    } catch {
        return null;
    }

    return null;
};

const systemTheme = () => (media.matches ? 'dark' : 'light');

const syncThemeControls = (theme) => {
    document.querySelectorAll('[data-theme-toggle]').forEach((toggle) => {
        const nextTheme = theme === 'dark' ? 'light' : 'dark';
        const label = nextTheme === 'dark' ? 'حالت تیره' : 'حالت روشن';

        toggle.setAttribute('aria-label', `تغییر به ${label}`);
        toggle.setAttribute('aria-pressed', String(theme === 'dark'));

        const text = toggle.querySelector('[data-theme-label]');
        if (text) {
            text.textContent = label;
        }
    });
};

const applyTheme = (theme, source = 'system') => {
    document.documentElement.dataset.theme = theme;
    document.documentElement.dataset.themeSource = source;
    document.documentElement.style.colorScheme = theme;

    const themeMeta = document.querySelector('meta[name="theme-color"]');
    if (themeMeta) {
        themeMeta.content = theme === 'dark' ? '#062a32' : '#fffaf0';
    }

    syncThemeControls(theme);
};

const persistTheme = (theme) => {
    try {
        window.localStorage.setItem(storageKey, theme);
    } catch {
        // The selected theme still applies for the current document.
    }
};

const storedTheme = readStoredTheme();
applyTheme(storedTheme ?? systemTheme(), storedTheme ? 'stored' : 'system');

document.querySelectorAll('[data-theme-toggle]').forEach((toggle) => {
    toggle.addEventListener('click', () => {
        const nextTheme = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
        persistTheme(nextTheme);
        applyTheme(nextTheme, 'stored');
    });
});

media.addEventListener('change', () => {
    if (readStoredTheme() === null) {
        applyTheme(systemTheme(), 'system');
    }
});
