/**
 * Dark Mode Persistence
 * Checks localStorage and applies 'dark' class to prevent flickering.
 */
(function () {
    const theme = localStorage.getItem('theme');
    const userPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    if (theme === 'dark' || (!theme && userPrefersDark)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
})();
