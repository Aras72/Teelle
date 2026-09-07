<script>
    (() => {
        const key = 'teelle-theme';
        const allowed = ['light', 'dark'];
        let stored = null;

        try {
            stored = window.localStorage.getItem(key);
            if (stored !== null && ! allowed.includes(stored)) {
                window.localStorage.removeItem(key);
                stored = null;
            }
        } catch {
            stored = null;
        }

        const theme = stored ?? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        document.documentElement.dataset.theme = theme;
        document.documentElement.dataset.themeSource = stored ? 'stored' : 'system';
        document.documentElement.style.colorScheme = theme;
        document.documentElement.classList.remove('no-js');
    })();
</script>
