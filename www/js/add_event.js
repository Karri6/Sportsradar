/**
 * Add Event Form - Simplified JavaScript
 * Client-side filtering only (no AJAX)
 */

document.addEventListener('DOMContentLoaded', function() {
    
    const sportSelect = document.getElementById('sport_id');

    const venueSelect = document.getElementById('venue_id');
    const newVenueFields = document.getElementById('new_venue_fields');
    const newVenueNameInput = document.getElementById('new_venue_name');
    const newVenueCityInput = document.getElementById('new_venue_city');
    
    const homeTeamSelect = document.getElementById('home_team_id');
    const newHomeTeamFields = document.getElementById('new_home_team_fields');
    const newHomeTeamNameInput = document.getElementById('new_home_team_name');
    const newHomeTeamOption = document.getElementById('new_home_team_option');
    
    const awayTeamSelect = document.getElementById('away_team_id');
    const newAwayTeamFields = document.getElementById('new_away_team_fields');
    const newAwayTeamNameInput = document.getElementById('new_away_team_name');
    const newAwayTeamOption = document.getElementById('new_away_team_option');
    
    const form = document.getElementById('addEventForm');
    
    sportSelect.addEventListener('change', function() {
        const sportId = this.value;

        if (sportId === '') {
            filterTeams('');
        } else {
            filterTeams(sportId);
        }
    });
    
    function filterTeams(sportId) {
        const homeOptions = homeTeamSelect.querySelectorAll('option[data-sport-id]');
        const awayOptions = awayTeamSelect.querySelectorAll('option[data-sport-id]');

        if (sportId === '') {
            // No sport selected hide all teams
            homeOptions.forEach(opt => {
                opt.disabled = true;
                opt.hidden = true;
            });
            awayOptions.forEach(opt => {
                opt.disabled = true;
                opt.hidden = true;
            });
            newHomeTeamOption.disabled = true;
            newHomeTeamOption.hidden = true;
            newAwayTeamOption.disabled = true;
            newAwayTeamOption.hidden = true;

            homeTeamSelect.value = '';
            awayTeamSelect.value = '';

        } else {
            // Show teams matching the sport
            homeOptions.forEach(opt => {
                if (opt.getAttribute('data-sport-id') === sportId) {
                    opt.disabled = false;
                    opt.hidden = false;
                } else {
                    opt.disabled = true;
                    opt.hidden = true;
                }
            });

            awayOptions.forEach(opt => {
                if (opt.getAttribute('data-sport-id') === sportId) {
                    opt.disabled = false;
                    opt.hidden = false;
                } else {
                    opt.disabled = true;
                    opt.hidden = true;
                }
            });

            // Show "Add New Team" option
            newHomeTeamOption.disabled = false;
            newHomeTeamOption.hidden = false;
            newAwayTeamOption.disabled = false;
            newAwayTeamOption.hidden = false;

            // Reset selection
            homeTeamSelect.value = '';
            awayTeamSelect.value = '';
        }
    }

    venueSelect.addEventListener('change', function() {
        if (this.value === 'new') {
            newVenueFields.style.display = 'block';
            newVenueNameInput.required = true;
            newVenueCityInput.required = true;
        } else {
            newVenueFields.style.display = 'none';
            newVenueNameInput.required = false;
            newVenueCityInput.required = false;
        }
    });
    
    homeTeamSelect.addEventListener('change', function() {
        if (this.value === 'new') {
            newHomeTeamFields.style.display = 'block';
            newHomeTeamNameInput.required = true;
        } else {
            newHomeTeamFields.style.display = 'none';
            newHomeTeamNameInput.required = false;
        }
    });
    
    awayTeamSelect.addEventListener('change', function() {
        if (this.value === 'new') {
            newAwayTeamFields.style.display = 'block';
            newAwayTeamNameInput.required = true;
        } else {
            newAwayTeamFields.style.display = 'none';
            newAwayTeamNameInput.required = false;
        }
    });
    
    function showError(message) {
        // Remove any existing error alerts
        const existingAlert = document.querySelector('.validation-error-alert');
        if (existingAlert) {
            existingAlert.remove();
        }

        // Create new alert
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger alert-dismissible fade show validation-error-alert';
        alertDiv.style.cssText = 'background-color: #f8d7da; border-left: 4px solid var(--sr-red); color: #721c24; border-radius: 8px; margin-bottom: 1.5rem;';
        alertDiv.innerHTML = `
            <strong>Validation Error:</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        form.insertBefore(alertDiv, form.firstChild);
        alertDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        setTimeout(() => {
            if (alertDiv && alertDiv.parentNode) {
                alertDiv.classList.remove('show');
                setTimeout(() => alertDiv.remove(), 150);
            }
        }, 8000);
    }

    form.addEventListener('submit', function(e) {
        const homeTeamId = homeTeamSelect.value;
        const awayTeamId = awayTeamSelect.value;

        // Check if same team selected for both
        if (homeTeamId && awayTeamId &&
            homeTeamId === awayTeamId &&
            homeTeamId !== 'new') {

            e.preventDefault();
            showError('Home and away teams cannot be the same!');
            return false;
        }

        // Check if sport is selected when creating new teams
        const sportId = sportSelect.value;
        if ((homeTeamId === 'new' || awayTeamId === 'new') && sportId === '') {
            e.preventDefault();
            showError('Please select a sport before creating new teams!');
            return false;
        }
    });

});