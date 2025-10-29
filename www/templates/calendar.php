<div class="content-wrapper">
    <!-- Events Section -->
    <div class="events-section">
        <h1 class="mb-4">Sports Events Calendar</h1>

        <div class="filter-buttons mb-4">
            <button class="filter-btn" onclick="alert('Select Sport filter - functionality to be implemented')">
                Select Sport
            </button>
            <button class="filter-btn" onclick="alert('Sort filter - functionality to be implemented')">
                Sort
            </button>
        </div>

        <!-- Navigation tabs -->
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link <?php echo (!isset($_GET['view']) || $_GET['view'] == 'all') ? 'active' : ''; ?>" 
                   href="index.php?page=calendar&view=all">All Events</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (isset($_GET['view']) && $_GET['view'] == 'upcoming') ? 'active' : ''; ?>" 
                   href="index.php?page=calendar&view=upcoming">Upcoming</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (isset($_GET['view']) && $_GET['view'] == 'past') ? 'active' : ''; ?>" 
                   href="index.php?page=calendar&view=past">Past Results</a>
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
                            <?php echo getDayOfWeek($event['event_date']) . ', ' . formatDate($event['event_date']); ?>
                            <span class="text-muted"> @ <?php echo formatTime($event['event_time']); ?></span>
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
                        <?php if ($event['status'] == 'completed' && !empty($event['winner_name'])): ?>
                            <span class="badge bg-success">Winner: <?php echo htmlspecialchars($event['winner_name']); ?></span>
                        <?php elseif ($event['status'] == 'completed' && empty($event['winner_name'])): ?>
                            <span class="badge bg-secondary">Draw</span>
                        <?php else: ?>
                            <span class="badge bg-info">Scheduled</span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="sidebar-section">
        <div class="sidebar-card">
            <h3>Possible Aside</h3>
            <p>Could display:
                <ul>
                    <li>latest sports news</li>
                    <li>sposored content</li>
                    <li>live score updates</li>
                    <li>etc.</li>
                </ul>
            </p>

            <p>
                Note for reviewer:<br>
                This is a placeholder.<br>
                The idea is to add a bit feeling and a structural outlook of how a potential page could look like.
            </p>
        </div>
    </div>
</div>