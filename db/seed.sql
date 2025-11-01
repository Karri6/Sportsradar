USE sportscalendar;

INSERT INTO sports (name, description) VALUES
('Soccer', 'Team sport played between two teams of eleven players with a spherical ball.'),
('Football', 'American football played with an oval ball on a rectangular field.'),
('Ice Hockey', 'Fast-paced game played on ice between two teams of skaters.');

INSERT INTO venues (name, city, address, capacity) VALUES
('Old Trafford', 'Manchester', 'Sir Matt Busby Way, Manchester M16 0RA, UK', 74310),
('Anfield', 'Liverpool', 'Anfield Road, Liverpool L4 0TH, UK', 53394),
('Stamford Bridge', 'London', 'Fulham Road, London SW6 1HS, UK', 40341),
('Lincoln Financial Field', 'Philadelphia', '1 Lincoln Financial Field Way, Philadelphia, PA', 69596),
('Rogers Place', 'Edmonton', '10220 104 Ave NW, Edmonton, AB', 18500),
('MetLife Stadium', 'East Rutherford', '1 MetLife Stadium Dr, East Rutherford, NJ', 82500),
('AT&T Stadium', 'Arlington', 'One AT&T Way, Arlington, TX', 80000),
('Scotiabank Arena', 'Toronto', '40 Bay St, Toronto, ON', 18800);

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

-- Events: Mix of past (with/without results) and future
INSERT INTO events (_sport_id, _venue_id, event_date, event_time, description, status) VALUES
-- Past events WITH results (4 completed, including 1 draw)
(1, 1, '2025-09-15', '18:00:00', 'Premier League Matchday 33', 'completed'),
(2, 4, '2025-10-08', '20:00:00', 'NFL Week 5', 'completed'),
(3, 5, '2025-10-12', '19:00:00', 'NHL Regular Season', 'completed'),
(1, 2, '2025-10-20', '15:00:00', 'Premier League Derby Match', 'completed'),

-- Past event WITHOUT results (1 scheduled - will show "Awaiting results")
(2, 7, '2025-10-31', '19:30:00', 'NFL Halloween Special', 'scheduled'),

-- Future events (5 upcoming between Nov 25 - Dec 15)
(1, 3, '2025-11-28', '17:30:00', 'Premier League London Derby', 'scheduled'),
(3, 8, '2025-12-01', '19:00:00', 'NHL Battle of Ontario', 'scheduled'),
(2, 6, '2025-12-08', '21:00:00', 'NFL Week 14', 'scheduled'),
(1, 1, '2025-12-12', '20:00:00', 'Premier League Evening Clash', 'scheduled'),
(3, 5, '2025-12-15', '18:30:00', 'NHL Winter Showdown', 'scheduled');

-- Event Teams (home/away assignments)
INSERT INTO event_teams (_event_id, _team_id, is_home) VALUES
-- Event 1-4: Past events WITH results
(1, 1, TRUE), (1, 2, FALSE),           -- Man Utd vs Liverpool (completed)
(2, 5, TRUE), (2, 6, FALSE),           -- Eagles vs Cowboys (completed)
(3, 9, TRUE), (3, 10, FALSE),          -- Oilers vs Maple Leafs (completed)
(4, 2, TRUE), (4, 3, FALSE),           -- Liverpool vs Chelsea (completed - draw)

-- Event 5: Past event WITHOUT results (Awaiting results)
(5, 7, TRUE), (5, 8, FALSE),           -- Giants vs Chiefs (awaiting)

-- Event 6-10: Future events
(6, 3, TRUE), (6, 4, FALSE),           -- Chelsea vs Arsenal (future)
(7, 10, TRUE), (7, 11, FALSE),         -- Maple Leafs vs Canadiens (future)
(8, 6, TRUE), (8, 5, FALSE),           -- Cowboys vs Eagles (future)
(9, 1, TRUE), (9, 4, FALSE),           -- Man Utd vs Arsenal (future)
(10, 9, TRUE), (10, 12, FALSE);        -- Oilers vs Bruins (future)

-- Results for past events (only events 1-4 have results, event 5 is awaiting)
INSERT INTO results (_event_id, _winner_id, home_score, away_score, notes) VALUES
(1, 1, 3, 1, 'Manchester United dominated the second half with 2 late goals.'),
(2, 5, 31, 24, 'Eagles defense intercepted 3 passes in crucial victory.'),
(3, 9, 4, 2, 'Oilers sealed victory with 2 goals in the final period.'),
(4, NULL, 2, 2, 'Thrilling derby ended in a draw with late equalizer.');

SELECT 'Seed data inserted successfully!' AS Status;
