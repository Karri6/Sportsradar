USE sportscalendar;

INSERT INTO sports (name, description) VALUES
('Soccer', 'Team sport played between two teams of eleven players with a spherical ball.'),
('Football', 'American football played with an oval ball on a rectangular field.'),
('Ice Hockey', 'Fast-paced game played on ice between two teams of skaters.');

INSERT INTO venues (name, city, address, capacity) VALUES
('Old Trafford', 'Manchester', 'Sir Matt Busby Way, Manchester M16 0RA, UK', 74310),
('Lincoln Financial Field', 'Philadelphia', '1 Lincoln Financial Field Way, Philadelphia, PA', 69596),
('Rogers Place', 'Edmonton', '10220 104 Ave NW, Edmonton, AB', 18500),
('MetLife Stadium', 'East Rutherford', '1 MetLife Stadium Dr, East Rutherford, NJ', 82500);

INSERT INTO teams (_sport_id, name, city) VALUES
-- Soccer (sport_id = 1)
(1, 'Manchester United', 'Manchester'),
(1, 'Liverpool FC', 'Liverpool'),
(1, 'Chelsea FC', 'London'),
(1, 'Arsenal FC', 'London'),

-- Football (sport_id = 2)
(2, 'Philadelphia Eagles', 'Philadelphia'),
(2, 'Dallas Cowboys', 'Dallas'),
(2, 'New York Giants', 'New York'),
(2, 'Kansas City Chiefs', 'Kansas City'),

-- Ice Hockey (sport_id = 3)
(3, 'Edmonton Oilers', 'Edmonton'),
(3, 'Toronto Maple Leafs', 'Toronto'),
(3, 'Montreal Canadiens', 'Montreal'),
(3, 'Boston Bruins', 'Boston');

-- Some arbitrary mmade up events
INSERT INTO events (_sport_id, _venue_id, event_date, event_time, description, status) VALUES
(1, 1, '2025-09-15', '18:00:00', 'Premier League Matchday 33', 'completed'),
(1, 1, '2025-12-10', '17:30:00', 'Premier League Matchday 21', 'scheduled'),

(2, 2, '2025-09-08', '20:00:00', 'NFL Week 1', 'completed'),
(2, 4, '2025-12-12', '21:00:00', 'NFL Conference Championship', 'scheduled'),

(3, 3, '2025-03-05', '19:00:00', 'NHL Regular Season', 'completed'),
(3, 3, '2025-12-20', '19:30:00', 'NHL Regular Season', 'scheduled');

-- Soccer events
INSERT INTO event_teams (_event_id, _team_id, is_home) VALUES
(1, 1, TRUE), (1, 2, FALSE),
(2, 3, TRUE), (2, 4, FALSE);

-- Football
INSERT INTO event_teams (_event_id, _team_id, is_home) VALUES
(3, 5, TRUE), (3, 6, FALSE),
(4, 7, TRUE), (4, 8, FALSE);

-- Ice Hockey
INSERT INTO event_teams (_event_id, _team_id, is_home) VALUES
(5, 9, TRUE), (5, 10, FALSE),
(6, 11, TRUE), (6, 12, FALSE);

-- Results for the past events
INSERT INTO results (_event_id, _winner_id, home_score, away_score, notes) VALUES
(1, 1, 3, 1, 'Manchester United dominated the second half.'),
(3, 5, 28, 17, 'Eagles won their season opener.'),
(5, 9, 4, 2, 'Oilers sealed victory with 2 goals in the last period.');

SELECT 'Seed data inserted successfully!' AS Status;
