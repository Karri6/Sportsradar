# Sportsradar Coding task 2026 (BE)

**Coding task for the Sportsradar Coding Academy 2026 (Backend)**

## Overview of the project

A sports event calendar application built with PHP, MySQL, and Docker. 

The app lets users view upcoming and past sports events, add new events, and add new sports. The program serves as a mock demo showcasing clean code practices, MVC architecture, and Docker containerization.

**Key Features:**
- View upcoming and past sports events with filtering and sorting
- Add new events with sport, venue, and team details
- Creation of new venues and teams when adding events

## Tech Stack
- **Backend**: PHP 8.2
- **Database**: MySQL 8.0
- **Frontend**: Bootstrap, JavaScript
- **DevOps**: Docker, Docker Compose

## Architecture
For detailed architecture, database schema, and design, see [Architecture Documentation](documentation/architecture.md).

## Prerequisites
- Docker Desktop (Windows/Mac) or Docker Engine + Docker Compose (Linux)

## Set up instructions

### Clone the repository

Clone the repository to a suitable directory, for example: users/user/documents/

```
git clone https://github.com/Karri6/Sportsradar.git

```

### Configure environment variables

Copy the example environment file and adjust if needed:

```
cp .env.example .env
```

The default values in `.env.example` work as they are, can also modify if you want/need custom database credentials.

### Start with configuration
> Make sure Docker & Docker Compose are installed

```
docker-compose up -d
```

This will start three containers:
- `sports_web` - PHP/Apache web server
- `sports_db` - MySQL database
- `sports_phpmyadmin` - Database management interface

### Check if all containers are running

```
docker-compose ps
```

You should see all 3 containers with status "Up":

```
NAME                  STATUS
sports_db             Up
sports_web            Up
sports_phpmyadmin     Up
```

### Access the application

Once all containers are running, you can access:

- **Web Application**: [http://localhost:8080](http://localhost:8080)
- **phpMyAdmin**: [http://localhost:8082](http://localhost:8082)

The database will automatically initialize with the schema and sample data from `db/init.sql` and `db/seed.sql`.

### Stop the application

```
docker-compose down
```

To also remove volumes (reset database):

```
docker-compose down -v
```

## Project Structure

```bash
Sportsradar/
├── db/                     # Database initialization
│   ├── init.sql            # Schema definition
│   └── seed.sql            # Sample data
├── www/                    # Web application
│   ├── backend/            # Backend logic
│   │   ├── config.php      # Configuration constants
│   │   ├── database.php    # Data access layer
│   │   ├── validation.php  # Input validation
│   │   ├── csrf.php        # CSRF token handling
│   │   └── db.php          # Database connection
│   ├── templates/          # View templates
│   ├── js/                 # JavaScript files
│   ├── css/                # Stylesheet
│   └── index.php           # Main entry point
├── documentation/          # Documentation and diagrams
├── docker-compose.yml      # Docker
├── dockerfile              # container config
├── .env.example            # Environment file template
└── README.md               
```

## How to Use

1. **View Events**: Navigate to the Calendar page to see upcoming and past events
2. **Filter Events**: Use the sport filter and sort dropdown to organize events
3. **Add Events**: Click "Add Events" in the navigation to create new events
4. **Manage Sports**: Go to "Sports" page to add new sports and view teams

## Assumptions / Decisions

1. Events Are Two-Team Only, can't list eg. a tournament.
2. Past Events Determined by Date Only, same day but past time still shows event as upcoming.
3. Results can't be added, just view and add events functionality.
4. No framework used, direct code less features.
5. Procedural architecture instead of OOP, small and simple project.
6. Calendar as homepage, as that was the initial assignment.
7. No Confirmation Dialogs, forms submit with no confirmation.
8. No Edit/Delete functionality, events, sports, teams, venues can't be modified or removed after creation.
9. No Pagination, all events load at once.
10. Teams belong to one sport only.
11. Venue is optional, events can be created without a venue.
12. Sportsradar colorscheme like styling.
13. Styling sheet generated almost completely. 
