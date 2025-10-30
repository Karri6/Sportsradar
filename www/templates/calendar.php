<div class="content-wrapper">
    <!-- Events Section -->
    <div class="events-section">
        <h1 class="mb-4">Sports Events Calendar</h1>

        <!-- Filter and Sort Controls -->
        <div class="row mb-4">
            <div class="col-md-4">
                <label for="sportFilter" class="form-label">Filter by Sport</label>
                <select id="sportFilter" class="form-select" onchange="applyFilters()">
                    <option value="">All Sports</option>
                    <?php foreach ($sports as $sport): ?>
                        <option value="<?php echo $sport['sport_id']; ?>"
                            <?php echo ($currentSport == $sport['sport_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($sport['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="sortBy" class="form-label">Sort By</label>
                <select id="sortBy" class="form-select" onchange="applyFilters()">
                    <option value="date" <?php echo ($currentSort == 'date') ? 'selected' : ''; ?>>Date</option>
                    <option value="sport" <?php echo ($currentSort == 'sport') ? 'selected' : ''; ?>>Sport</option>
                </select>
            </div>
        </div>

        <!-- Navigation tabs -->
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link <?php echo ($currentView == 'upcoming') ? 'active' : ''; ?>"
                   href="<?php echo buildUrl('calendar', ['view' => 'upcoming', 'sport' => $currentSport, 'sort' => $currentSort]); ?>">Upcoming</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($currentView == 'past') ? 'active' : ''; ?>"
                   href="<?php echo buildUrl('calendar', ['view' => 'past', 'sport' => $currentSport, 'sort' => $currentSort]); ?>">Past Results</a>
            </li>
        </ul>

        <?php if (empty($events)): ?>
            <div class="alert alert-info">
                <strong>No events found.</strong>
            </div>
        <?php else: ?>
            <div class="events-list">
                <?php foreach ($events as $event): ?>
                    <div class="event-card">
                        <!-- Sport badge -->
                        <span class="badge bg-primary sport-badge"><?php echo htmlspecialchars($event['sport_name']); ?></span>
                        
                        <!-- Date and time -->
                        <h5 class="card-title mt-3">
                            <?php echo $event['formatted_date']; ?>
                            <span class="text-muted"> @ <?php echo $event['formatted_time']; ?></span>
                        </h5>
                        
                        <!-- Teams -->
                        <h4 class="my-3">
                            <?php echo htmlspecialchars($event['home_team']); ?>
                            
                            <?php if ($event['status'] == 'completed' && isset($event['home_score'])): ?>
                                <!-- Show score for completed events -->
                                <span class="score mx-2"><?php echo $event['home_score'] . ' : ' . $event['away_score']; ?></span>
                            <?php else: ?>
                                <!-- Show 'vs' for upcoming events -->
                                <span class="text-muted mx-2">vs</span>
                            <?php endif; ?>
                            
                            <?php echo htmlspecialchars($event['away_team']); ?>
                        </h4>
                        
                        <!-- Venue -->
                        <?php if (!empty($event['venue_name'])): ?>
                            <p class="text-muted mb-2">
                                <?php echo htmlspecialchars($event['venue_name']); ?>
                                <?php if (!empty($event['venue_city'])): ?>
                                    , <?php echo htmlspecialchars($event['venue_city']); ?>
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                        
                        <!-- Description -->
                        <?php if (!empty($event['description'])): ?>
                            <p class="mb-2"><small><?php echo htmlspecialchars($event['description']); ?></small></p>
                        <?php endif; ?>
                        
                        <!-- Winner badge for completed events -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <?php if ($event['status'] == 'completed' && !empty($event['winner_name'])): ?>
                                    <span class="badge bg-success">Winner: <?php echo htmlspecialchars($event['winner_name']); ?></span>
                                <?php elseif ($event['status'] == 'completed' && empty($event['winner_name'])): ?>
                                    <span class="badge bg-secondary">Draw</span>
                                <?php else: ?>
                                    <span class="badge bg-info">Scheduled</span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <a href="index.php?page=event&event_id=<?php echo $event['event_id']; ?>"
                                   class="btn btn-sm btn-primary"
                                   style="background-color: var(--sr-dark-blue); border-color: var(--sr-dark-blue); font-weight: 700; padding: 0.5rem 1rem;">
                                    View Details →
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="sidebar-section">
        <div class="sidebar-card">
            <h3>Placeholder Aside</h3>
            <p>Could display:
                <ul>
                    <li>latest sports news</li>
                    <li>sponsored content</li>
                    <li>live score updates</li>
                    <li>etc.</li>
                </ul>
            </p>

            <p>
                For reviewer:<br>
                Idea was to add a bit feeling<br>
                and a structural outlook of how<br>
                 a potential page could look like.
            </p>
        </div>
    </div>
</div>

<!-- Load calendar JavaScript -->
<script src="js/calendar.js"></script>