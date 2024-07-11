-- mysql/init/init.sql

-- Create additional databases
CREATE DATABASE IF NOT EXISTS bwcamp;

-- Create additional users
CREATE USER IF NOT EXISTS 'bwcamp'@'%' IDENTIFIED BY 'bwcamp';
GRANT ALL PRIVILEGES ON bwcamp.* TO 'bwcamp'@'%';

-- You can add more database and user creation statements here

FLUSH PRIVILEGES;