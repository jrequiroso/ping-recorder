# ping-recorder
A 5-minute ping recorder I made because I needed to show stats to PLDT. Made for Windows only.

# Installation
1. Pull Pinger
2. Create a database named `pinger`
3. Import pinger.sql into the database

# How to use
1. Open `localhost/pinger/main.php` in a new tab. It will refresh every 5 minutes and record ping
2. Open `localhost/pinger/` in a new tab to view stats.

# Todo:
1. Make version for Mac
2. When opening page without a date argument, get latest date from db or show error that there are no stats to show for the day.
