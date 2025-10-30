

<div class="content-wrapper">
    <!-- Main Form Section -->
    <div class="events-section add-event-form">
        <h1 class="mb-4">Add New Event</h1>

        <hr class="section-divider">

        <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Event added successfully! 
                <a href="index.php?page=calendar" class="alert-link">View calendar</a>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="index.php?page=add_event" id="addEventForm">
                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
                    
                    <!-- Sport Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Sport *</label>
                        <select name="sport_id" id="sport_id" class="form-select" required>
                            <option value="">-- Select Sport --</option>
                            <?php foreach ($sports as $sport): ?>
                                <option value="<?php echo $sport['sport_id']; ?>">
                                    <?php echo htmlspecialchars($sport['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text text-muted">
                            Want to add a new sport? Visit the <a href="index.php?page=sports">Sports Management</a> page.
                        </small>
                    </div>
                    
                    <!-- Venue Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Venue (Optional)</label>
                        <select name="venue_id" id="venue_id" class="form-select">
                            <option value="">-- No Venue --</option>
                            <?php foreach ($venues as $venue): ?>
                                <option value="<?php echo $venue['venue_id']; ?>">
                                    <?php echo htmlspecialchars($venue['name'] . ' - ' . $venue['city']); ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="new">+ Add New Venue</option>
                        </select>
                        
                        <!-- New Venue Input (hidden by default) -->
                        <div id="new_venue_fields" class="mt-2 hidden-field">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">Create New Venue:</h6>
                                <input type="text" name="new_venue_name" id="new_venue_name" class="form-control mb-2" placeholder="Venue Name">
                                <input type="text" name="new_venue_city" id="new_venue_city" class="form-control mb-2" placeholder="City">
                                <input type="text" name="new_venue_address" class="form-control mb-2" placeholder="Address (optional)">
                                <input type="number" name="new_venue_capacity" class="form-control" placeholder="Capacity (optional)">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Home Team -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Home Team *</label>
                        <select name="home_team_id" id="home_team_id" class="form-select" required>
                            <option value="">-- First select a sport --</option>
                            <?php foreach ($teams as $team): ?>
                                <option value="<?php echo $team['team_id']; ?>"
                                        data-sport-id="<?php echo $team['_sport_id']; ?>"
                                        disabled hidden>
                                    <?php echo htmlspecialchars($team['name']); ?>
                                    <?php if (!empty($team['city'])): ?>
                                        (<?php echo htmlspecialchars($team['city']); ?>)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="new" id="new_home_team_option" disabled hidden>+ Add New Team</option>
                        </select>
                        
                        <!-- New Home Team Input (hidden by default) -->
                        <div id="new_home_team_fields" class="mt-2 hidden-field">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">Create New Home Team:</h6>
                                <input type="text" name="new_home_team_name" id="new_home_team_name" class="form-control mb-2" placeholder="Team Name">
                                <input type="text" name="new_home_team_city" class="form-control" placeholder="City (optional)">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Away Team -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Away Team *</label>
                        <select name="away_team_id" id="away_team_id" class="form-select" required>
                            <option value="">-- First select a sport --</option>
                            <?php foreach ($teams as $team): ?>
                                <option value="<?php echo $team['team_id']; ?>"
                                        data-sport-id="<?php echo $team['_sport_id']; ?>"
                                        disabled hidden>
                                    <?php echo htmlspecialchars($team['name']); ?>
                                    <?php if (!empty($team['city'])): ?>
                                        (<?php echo htmlspecialchars($team['city']); ?>)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="new" id="new_away_team_option" disabled hidden>+ Add New Team</option>
                        </select>
                        
                        <!-- New Away Team Input (hidden by default) -->
                        <div id="new_away_team_fields" class="mt-2 hidden-field">
                            <div class="p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">Create New Away Team:</h6>
                                <input type="text" name="new_away_team_name" id="new_away_team_name" class="form-control mb-2" placeholder="Team Name">
                                <input type="text" name="new_away_team_city" class="form-control" placeholder="City (optional)">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Date & Time -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Event Date *</label>
                            <input type="date" name="event_date" class="form-control" required 
                                   min="<?php echo date('Y-m-d'); ?>"
                                   value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Event Time *</label>
                            <input type="time" name="event_time" class="form-control" required value="18:00">
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Description (Optional)</label>
                        <textarea name="description" class="form-control" rows="3" 
                                  placeholder="E.g., Championship Final, League Round 5, etc."></textarea>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            Add Event
                        </button>
                        <a href="index.php?page=calendar" class="btn btn-secondary btn-lg">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Sidebar Section -->
    <div class="sidebar-section">
        <div class="sidebar-card">
            <h3>Instructions</h3>
            <ul>
                <li>Fields marked with * are required</li>
                <li><strong>Select sport first</strong> to see available teams</li>
                <li>Either select from dropdown OR create new</li>
                <li>Event date cannot be in the past</li>
                <li>Home and away teams must be different</li>
                <li>Visit the <a href="index.php?page=sports">Sports Management</a> page to add new sports.</li>
            </ul>
        </div>
    </div>
</div>

<!-- Load external JavaScript -->
<script src="js/add_event.js"></script>