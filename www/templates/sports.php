<div class="content-wrapper">
    <!-- Sports List Section -->
    <div class="events-section">
        <h1 class="mb-4 page-title">Sports Management</h1>
        <p class="text-muted page-subtitle">Manage all sports in the system</p>

        <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show alert-success-styled" role="alert">
                <strong>Success!</strong> Sport added successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show alert-danger-styled" role="alert">
                <strong>Error:</strong>
                <ul class="mb-0 mt-2">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <hr class="section-divider">

        <div class="row">
            <!-- Sports List -->
            <div class="col-lg-7">
                <h3 class="mb-3 section-title">All Sports</h3>

                <?php if (empty($sports)): ?>
                    <div class="alert alert-info alert-info-styled" role="alert">
                        <strong>No sports found.</strong> Add your first sport using the form on the right.
                    </div>
                <?php else: ?>
                    <div class="list-group">
                        <?php foreach ($sports as $sport): ?>
                            <a href="index.php?page=sport_teams&sport_id=<?php echo $sport['sport_id']; ?>"
                               class="list-group-item list-group-item-action sport-list-item">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1 list-item-title"><?php echo htmlspecialchars($sport['name']); ?></h5>
                                        <?php if (!empty($sport['description'])): ?>
                                            <p class="mb-0 text-muted small">
                                                <?php echo htmlspecialchars($sport['description']); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <span class="badge rounded-pill badge-action">View Teams →</span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Add Sport Form -->
            <div class="col-lg-5">
                <div class="card card-styled card-sticky">
                    <div class="card-body">
                        <h4 class="card-title mb-4 card-title-bold">Add New Sport</h4>

                        <form method="POST" action="index.php?page=sports">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">

                            <div class="mb-3">
                                <label for="sport_name" class="form-label fw-bold form-label-bold">Sport Name *</label>
                                <input type="text"
                                       class="form-control"
                                       id="sport_name"
                                       name="name"
                                       placeholder="e.g., Basketball, Tennis, Rugby"
                                       required
                                       maxlength="100">
                            </div>

                            <div class="mb-4">
                                <label for="sport_description" class="form-label fw-bold form-label-bold">Description</label>
                                <textarea class="form-control"
                                          id="sport_description"
                                          name="description"
                                          rows="4"
                                          placeholder="Brief description of the sport (optional)"
                                          maxlength="500"></textarea>
                                <small class="form-text text-muted">Optional - max 500 characters</small>
                            </div>

                            <button type="submit" class="btn btn-primary-dark w-100">
                                <i class="bi bi-plus-circle"></i> Add Sport
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
