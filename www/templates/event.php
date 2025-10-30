<div class="content-wrapper">
    <!-- Event Details Section -->
    <div class="events-section">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="index.php?page=calendar" class="btn btn-secondary btn-back">
                ← Back to Calendar
            </a>
        </div>

        <!-- Sport Badge -->
        <div class="mb-3">
            <span class="badge bg-primary sport-badge-large">
                <?php echo htmlspecialchars($event['sport_name']); ?>
            </span>
        </div>

        <!-- Event Header -->
        <h1 class="mb-2 page-title">
            <?php echo htmlspecialchars($event['home_team']); ?>
            <span class="text-muted mx-2">vs</span>
            <?php echo htmlspecialchars($event['away_team']); ?>
        </h1>

        <!-- Date and Time -->
        <h4 class="mb-3 text-muted">
            <?php echo $event['formatted_date']; ?> @ <?php echo $event['formatted_time']; ?>
        </h4>

        <hr class="section-divider">

        <!-- Status Badge -->
        <div class="mb-4">
            <?php if ($event['status'] == 'completed' && !empty($event['winner_name'])): ?>
                <span class="badge bg-success badge-large">
                    Winner: <?php echo htmlspecialchars($event['winner_name']); ?>
                </span>
            <?php elseif ($event['status'] == 'completed' && empty($event['winner_name'])): ?>
                <span class="badge bg-secondary badge-large">Draw</span>
            <?php else: ?>
                <span class="badge bg-info badge-large">Scheduled</span>
            <?php endif; ?>
        </div>

        <!-- Event Details Card -->
        <div class="card mb-4 card-styled">
            <div class="card-header card-header-primary">
                <h5 class="mb-0 card-title-bold">Event Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Home Team -->
                    <div class="col-md-5">
                        <h5 class="team-section-title">Home Team</h5>
                        <p class="mb-1"><strong>Team:</strong> <?php echo htmlspecialchars($event['home_team']); ?></p>
                        <?php if (!empty($event['home_team_city'])): ?>
                            <p class="mb-1"><strong>City:</strong> <?php echo htmlspecialchars($event['home_team_city']); ?></p>
                        <?php endif; ?>
                        <?php if ($event['status'] == 'completed' && isset($event['home_score'])): ?>
                            <p class="score-large">
                                <?php echo $event['home_score']; ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- VS Divider -->
                    <div class="col-md-2 text-center d-flex align-items-center justify-content-center">
                        <h2 class="text-muted vs-text">VS</h2>
                    </div>

                    <!-- Away Team -->
                    <div class="col-md-5">
                        <h5 class="team-section-title">Away Team</h5>
                        <p class="mb-1"><strong>Team:</strong> <?php echo htmlspecialchars($event['away_team']); ?></p>
                        <?php if (!empty($event['away_team_city'])): ?>
                            <p class="mb-1"><strong>City:</strong> <?php echo htmlspecialchars($event['away_team_city']); ?></p>
                        <?php endif; ?>
                        <?php if ($event['status'] == 'completed' && isset($event['away_score'])): ?>
                            <p class="score-large">
                                <?php echo $event['away_score']; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Additional Info -->
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="info-section-header">Sport</h6>
                        <p><?php echo htmlspecialchars($event['sport_name']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="info-section-header">Date & Time</h6>
                        <p><?php echo $event['formatted_date']; ?> at <?php echo $event['formatted_time']; ?></p>
                    </div>
                </div>

                <?php if (!empty($event['description'])): ?>
                    <div class="mt-3">
                        <h6 class="info-section-header">Description</h6>
                        <p><?php echo htmlspecialchars($event['description']); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Venue Card -->
        <?php if (!empty($event['venue_name'])): ?>
            <div class="card card-styled">
                <div class="card-header card-header-red">
                    <h5 class="mb-0 card-title-bold">Venue Information</h5>
                </div>
                <div class="card-body">
                    <h5 class="venue-title">
                        <?php echo htmlspecialchars($event['venue_name']); ?>
                    </h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <?php if (!empty($event['venue_city'])): ?>
                                <p class="mb-1"><strong>City:</strong> <?php echo htmlspecialchars($event['venue_city']); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($event['venue_address'])): ?>
                                <p class="mb-1"><strong>Address:</strong> <?php echo htmlspecialchars($event['venue_address']); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($event['venue_capacity'])): ?>
                                <p class="mb-0"><strong>Capacity:</strong> <?php echo number_format($event['venue_capacity']); ?> seats</p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <!-- Mock Map Placeholder -->
                            <div class="map-placeholder">
                                <span class="map-placeholder-text">Map placeholder</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar Section -->
    <div class="sidebar-section">
        <!-- Buy Tickets Card -->
        <?php if ($event['status'] == 'scheduled'): ?>
            <div class="sidebar-card-tickets">
                <h3>Get Tickets</h3>
                <a href="#" class="btn btn-light w-100 btn-buy-tickets">
                    Buy Tickets
                </a>
                <p class="small-print">
                    Secure your seats now!<br>
                </p>
            </div>
        <?php endif; ?>

        <!-- Event Info Card -->
        <div class="sidebar-card">
            <h3>Quick Info</h3>
            <ul class="info-list">
                <li>
                    <strong>Event ID:</strong> #<?php echo $event['event_id']; ?>
                </li>
                <li>
                    <strong>Sport:</strong> <?php echo htmlspecialchars($event['sport_name']); ?>
                </li>
                <li>
                    <strong>Status:</strong>
                    <span class="badge <?php echo $event['status'] == 'completed' ? 'bg-secondary' : 'bg-info'; ?>">
                        <?php echo ucfirst($event['status']); ?>
                    </span>
                </li>
                <?php if (!empty($event['venue_name'])): ?>
                    <li>
                        <strong>Venue:</strong> <?php echo htmlspecialchars($event['venue_name']); ?>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Share/Actions Placeholder -->
        <div class="sidebar-card-share mt-4">
            <h3>Share Event</h3>
            <p class="text-muted mb-3">Tell your friends about this event!</p>
            <div class="d-grid gap-2">
                <button class="btn btn-outline-primary btn-share">
                    Share on Social Media
                </button>
                <button class="btn btn-outline-secondary btn-share">
                    Copy Link
                </button>
            </div>
        </div>
    </div>
</div>