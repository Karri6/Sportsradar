<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                Sports Calendar
            </a>
        </div>
    </nav>
    
    <!-- Page Navigation Bar -->
    <div class="secondary-nav">
        <a class="nav-link" href="index.php?page=calendar">Calendar</a>
        <a class="nav-link" href="index.php?page=sports">Sports</a>
        <a class="nav-link" href="index.php?page=add_event">Add Events</a>
    </div>
    
    <div class="main-container">