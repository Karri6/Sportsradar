# Sportsradar Coding task 2026 (BE)
Coding task for the Sportsradar Coding Academy 2026 (Backend)

The project is a sports event calendar that allows events to be added and categorized based on
sports.

## Overview of the project


## Prerequisites
- Docker Desktop (Windows/Mac) or Docker Engine + Docker Compose (Linux)

## Set up instructions

### Clone the repository

Clone the repository to a suitable directory, for example: users/user/documents/

```
git clone https://github.com/Karri6/Sportsradar.git

```

### Start with  configuration
> Make sure Docker & Docker Compose are installed

```
docker-compose up -d
```

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

## Assumptions / Decisions

