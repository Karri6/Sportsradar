/**
 * Calendar page filtering functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    // Attach event listeners to filter elements
    const sportFilter = document.getElementById('sportFilter');
    const sortBy = document.getElementById('sortBy');

    if (sportFilter) {
        sportFilter.addEventListener('change', applyFilters);
    }

    if (sortBy) {
        sortBy.addEventListener('change', applyFilters);
    }
});

/**
 * Apply selected filters and navigate to updated URL
 */
function applyFilters() {
    const sport = document.getElementById('sportFilter').value;
    const sort = document.getElementById('sortBy').value;
    const viewElement = document.querySelector('.nav-tabs .nav-link.active');

    // Extract view from active tab (default to 'upcoming')
    let view = 'upcoming';
    if (viewElement) {
        const href = viewElement.getAttribute('href');
        const urlParams = new URLSearchParams(href.split('?')[1]);
        view = urlParams.get('view') || 'upcoming';
    }

    // Build URL with parameters
    let url = 'index.php?page=calendar&view=' + encodeURIComponent(view);
    if (sport) {
        url += '&sport=' + encodeURIComponent(sport);
    }
    if (sort) {
        url += '&sort=' + encodeURIComponent(sort);
    }

    window.location.href = url;
}