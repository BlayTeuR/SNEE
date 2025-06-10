const spinner = document.getElementById('global-spinner');

export function showSpinner() {
    spinner.classList.remove('hidden');
}

export function hideSpinner() {
    spinner.classList.add('hidden');
}

/**
 * Wrapper global pour fetch qui affiche et masque le spinner automatiquement
 * @param {string} url
 * @param {object} options
 * @returns {Promise<Response>}
 */
export async function fetchWithSpinner(url, options = {}) {
    showSpinner();
    try {
        const response = await fetch(url, options);
        return response;
    } finally {
        hideSpinner();
    }
}

const originalFetch = window.fetch;

window.fetch = async function(...args) {
    showSpinner();
    try {
        const response = await originalFetch(...args);
        return response;
    } finally {
        hideSpinner();
    }
}
