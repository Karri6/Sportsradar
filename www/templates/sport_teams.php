<div class="content-wrapper">
    <div class="events-section">
        <div class="mb-4">
            <a href="index.php?page=sports" class="btn btn-secondary btn-back">
                ← Back to Sports
            </a>
        </div>

        <div class="mb-4">
            <span class="sport-badge"><?php echo htmlspecialchars($sport['name']); ?></span>
            <h1 class="mt-3 mb-2"><?php echo htmlspecialchars($sport['name']); ?> Teams</h1>
            <?php if (!empty($sport['description'])): ?>
                <p class="text-muted mb-0 page-subtitle"><?php echo htmlspecialchars($sport['description']); ?></p>
            <?php endif; ?>
        </div>

        <hr class="section-divider">
        
        <div class="card mt-4 card-styled">
            <div class="card-header card-header-primary">
                <h5 class="mb-0 card-title-bold">Teams in <?php echo htmlspecialchars($sport['name']); ?></h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">Team listing functionality could be implemented here...</p>
                <div class="mt-4 p-3 info-box">
                    <h6 class="info-box-title">Preview:</h6>
                    <p class="text-muted mb-0 text-small">
                        Teams could be displayed in a list format similar to the sports page, with each team showing:
                    </p>
                    <ul class="mt-2 mb-0 text-muted text-small">
                        <li>Team name and city</li>
                        <li>Number of players</li>
                        <li>Recent match statistics</li>
                        <li>Quick action buttons (eg. View Details)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
