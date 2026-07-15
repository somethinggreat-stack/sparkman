-- ============================================================================
--  Sparkman Solutions — WIPE ALL TEST DATA
--
--  ⚠️ DESTRUCTIVE AND IRREVERSIBLE. Back up first:
--     phpMyAdmin > select the DB > "Export" tab > Go
--
--  Clears: leads, payments, subscriptions, clients, webhook_events.
--  Keeps:  table structure, migrations history, admin login (admin lives in .env).
--
--  HOW TO RUN (cPanel > phpMyAdmin > database `fundmen1_jay` > "SQL" tab > Go)
--
--  NOTE: This uses DELETE (not TRUNCATE) on purpose. phpMyAdmin's
--  "Enable foreign key checks" checkbox re-enables FK checks and overrides
--  `SET FOREIGN_KEY_CHECKS = 0`, which makes TRUNCATE fail with:
--      #1701 Cannot truncate a table referenced in a foreign key constraint
--  DELETE in child -> parent order works with that checkbox either way.
-- ============================================================================

-- Children first, then parents.
DELETE FROM `clients`;        -- references payments, leads
DELETE FROM `subscriptions`;  -- references payments
DELETE FROM `payments`;       -- references leads
DELETE FROM `leads`;
DELETE FROM `webhook_events`;

-- DELETE does not reset IDs, so restart the counters at 1.
ALTER TABLE `clients`        AUTO_INCREMENT = 1;
ALTER TABLE `subscriptions`  AUTO_INCREMENT = 1;
ALTER TABLE `payments`       AUTO_INCREMENT = 1;
ALTER TABLE `leads`          AUTO_INCREMENT = 1;
ALTER TABLE `webhook_events` AUTO_INCREMENT = 1;

-- Verify (every count should be 0):
SELECT 'leads' AS table_name, COUNT(*) AS rows_left FROM `leads`
UNION ALL SELECT 'payments',       COUNT(*) FROM `payments`
UNION ALL SELECT 'subscriptions',  COUNT(*) FROM `subscriptions`
UNION ALL SELECT 'clients',        COUNT(*) FROM `clients`
UNION ALL SELECT 'webhook_events', COUNT(*) FROM `webhook_events`;
