/**
 * Premium Calculator Logic
 * Handles currency selection, modal toggling, and swap functionality.
 */
document.addEventListener('DOMContentLoaded', () => {

    const modal = document.getElementById('currency-modal');
    const currencyList = document.getElementById('currency-list');
    const searchInput = document.getElementById('currency-search');
    const countries = window.countries || [];

    let activeRow = null;

    // ── Modal Toggle ─────────────────────────────────────────────────────────
    function openModal(row) {
        activeRow = row;
        modal.classList.remove('hidden');
        renderList(countries);
        searchInput.focus();
    }

    function closeModal() {
        modal.classList.add('hidden');
        activeRow = null;
        searchInput.value = '';
    }

    // Close on backdrop click or Close button
    document.querySelectorAll('.modal-close').forEach(el => {
        el.addEventListener('click', closeModal);
    });

    // ── List Rendering ───────────────────────────────────────────────────────
    function renderList(list) {
        currencyList.innerHTML = '';
        list.forEach(c => {
            const item = document.createElement('div');
            item.className = 'flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer transition-colors group';
            // Flag logic: some countries have multiple currencies, but we use code for flags
            const flagCode = c.code.toLowerCase();

            item.innerHTML = `
                <img class="w-8 h-8 rounded-full object-cover border-2 border-white dark:border-slate-700 shadow-sm" 
                     src="https://flagcdn.com/48x36/${flagCode}.png" 
                     alt="${c.currency}">
                <div>
                    <span class="block font-bold text-slate-800 dark:text-white text-sm group-hover:text-primary transition-colors">${c.currency}</span>
                    <span class="text-[10px] text-slate-500 dark:text-slate-400 font-medium uppercase tracking-tight">${c.name}</span>
                </div>
            `;
            item.addEventListener('click', () => selectCurrency(c));
            currencyList.appendChild(item);
        });
    }

    function selectCurrency(c) {
        if (!activeRow) return;
        const type = activeRow.dataset.type; // 'from' or 'to'
        const flag = document.getElementById(`${type}-flag`);
        const name = document.getElementById(`${type}-name`);
        const hiddenInput = document.getElementById(`hidden-${type}`);

        flag.src = `https://flagcdn.com/48x36/${c.code.toLowerCase()}.png`;
        name.textContent = c.currency;
        if (hiddenInput) hiddenInput.value = c.currency;

        closeModal();

        // Auto-submit form on selection
        const form = document.getElementById('calc-form');
        if (form) form.submit();
    }

    // ── Search ───────────────────────────────────────────────────────────────
    searchInput.addEventListener('input', () => {
        const val = searchInput.value.toLowerCase();
        const filtered = countries.filter(c =>
            c.name.toLowerCase().includes(val) ||
            c.currency.toLowerCase().includes(val)
        );
        renderList(filtered);
    });

    // ── Interactivity ────────────────────────────────────────────────────────
    document.querySelectorAll('.currency-row').forEach(row => {
        row.addEventListener('click', () => openModal(row));
    });

    // Real-time amount update on display
    const amountInput = document.getElementById('calc-amount');
    const fromAmountDisplay = document.getElementById('from-amount-display');
    if (amountInput && fromAmountDisplay) {
        amountInput.addEventListener('input', () => {
            fromAmountDisplay.textContent = amountInput.value || '0';
        });
    }

    const swapBtn = document.getElementById('swap-btn');
    if (swapBtn) {
        swapBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();

            const fromFlag = document.getElementById('from-flag');
            const fromName = document.getElementById('from-name');
            const hiddenFrom = document.getElementById('hidden-from');

            const toFlag = document.getElementById('to-flag');
            const toName = document.getElementById('to-name');
            const hiddenTo = document.getElementById('hidden-to');

            // Swap visual states
            const tempSrc = fromFlag.src;
            fromFlag.src = toFlag.src;
            toFlag.src = tempSrc;

            const tempName = fromName.textContent;
            fromName.textContent = toName.textContent;
            toName.textContent = tempName;

            // Swap hidden values
            const tempVal = hiddenFrom.value;
            hiddenFrom.value = hiddenTo.value;
            hiddenTo.value = tempVal;

            // Auto-submit form on swap
            const form = document.getElementById('calc-form');
            if (form) form.submit();
        });
    }

});
