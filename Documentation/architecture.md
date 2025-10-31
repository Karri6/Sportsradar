# Architecture Documentation

How the Sports Calendar application is built and how everything works together.

## System Overview

The application is a simple web app that can be tested by running a Docker container. 
It separates the presentation templates, PHP and the database.

## Technology Stack

### Backend
- **PHP**: Main programming language
- **Apache**: Web server that serves the pages
- **PDO**: How PHP talks to the database

### Database
- **MySQL 8.0**: Relational database

### Frontend
- **Bootstrap**: CSS framework for styling and responsive design
- **JavaScript**: Client-side filtering & form validation
- **HTML**: Page structure

### DevOps
- **Docker**: Containerizes everything so it *should* run and allow testing the same everywhere

## Database Schema

The database has 6 tables that work together to store sports events and their details.

### Entity-Relationship Diagram

![Entity-Relationship Diagram](diagrams/Entity-Relationship-Diagram.png)

*This is the current database structure with all tables, their fields, and how they connect to each other.*

The main flow is: Sports have teams, events need sports, venues, and teams. Teams in an event are bridged in a separate table event teams.
Events also have results which are stored in results table.

### Database Tables

**sports**
- Each sport has a name and optional description
- UNIQUE constraint on name (can't add the same sport twice)

**venues**
- Stores name, city, address, and capacity
- Venues are optional for events 
- Venues are optional, because I noticed too late that I had forgotten to add `NOT NULL` to the events table... "It's not a bug, a feature" or something like that...

**teams**
- Each team belongs to one sport (foreign key to sports)
- UNIQUE constraint on (name, sport) combo (can't have duplicate team names within the same sport)

**events**
- The main table for sports events
- Links to sport, venue, date, time
- Has a status: 'scheduled', 'in_progress', 'completed', 'cancelled'
- Doesn't directly store teams

**event_teams**
- Bridging table for connecting events and teams
- `is_home` field to track home vs away team
- Each event must have exactly 2 teams (one home, one away)

**results**
- Records home_score, away_score, and winner
- Winner can be NULL (means it was a draw/tie)
- ON DELETE CASCADE: if event is deleted, result is too (not used in this app)

### ERD Extension - Future Additions

Next tables that could be included are: Players, Leagues, Player/Team Stats and necessary tables for bridging these to teams and events etc. 

## Application Architecture

**Model** (backend/database.php):
- Functions that fetch data from the database
- Examples: `getAllSports()` & `addEvent()`
- All SQL queries here

**View** (templates/):
- Templates that display data
- Examples: `calendar.php` & `add_event.php`

**Controller** (index.php):
- Routes requests to the right page
- Loads data from Model and passes to View
- Handles form submissions

### Routing

The app uses a simple query parameter router:

- `?page=calendar` → Shows event calendar
- `?page=sports` → Manages sports (Can also add sports here)
- `?page=sport_teams&sport_id=1` → Lists teams for a sport
- `?page=add_event` → Form to add new events


## Security Features

Did not implement anything rigorous, just noted these to point out that security has been considered.

**Some CSRF Protection**

**SQL Injection Prevention**

**Basic XSS Prevention**

**Some input Validation**

**Basic session security**

**Data Sanitization**
- Text inputs are trimmed and HTML tags stripped
- Database uses UTF8MB4 charset

## Known Limitations

Since this is a mock demo, some features are simplified:

- **No user authentication**: Anyone can add/view events
- **No edit/delete**: Can only create, not modify or remove
- **No past event adding**: Can only create future events.
- **Team management placeholder**: Sport teams page is not implemented
- **Unique constraint errors**: Frontend doesn't prevent duplicate names (shows DB error)
