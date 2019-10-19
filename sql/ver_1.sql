ALTER TABLE games ADD starts_at DATETIME NULL;
ALTER TABLE games MODIFY COLUMN starts_at DATETIME AFTER players_unavailable;


UPDATE `games` a SET
a.starts_at = concat(date(a.updated_at + INTERVAL 4 - weekday(a.updated_at) DAY), ' 20:00:00'),
a.created_at = DATE(DATE_SUB(date(a.updated_at + INTERVAL 4 - weekday(a.updated_at) DAY), INTERVAL 7 DAY));