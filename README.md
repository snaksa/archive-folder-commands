# Bash and PHP commands to archive files in folder older than N days
## Bash script
### Params:
* `-f` - folder to be archived (required)
* `-d` - find files older than N days (default 30)
* `-a` - name of the newly created archive (default current executing directory or /home directory when executig through cron)
### Example
```
./archive.sh -f /home/john/Downloads -d 10 -a /home/john/backups/friday-backup
```

## PHP script
### Installing
The script is using Symfony's Finder component so you need to install it through composer:
```
composer install
```
### Params:
* `-f` - folder to be archived (required)
* `-d` - find files older than N days (default 30)
* `-a` - name of the newly created archive (default /home directory of the executing user)
* `-h` - show help menu
### Example
```
php archive.php -f /home/john/Downloads -d 10 -a /home/john/backups/friday-backup
```

## Using cron
The following cronjobs execute the scripts every first day of the month at 2:30
```
30 2 1 * * /path-to-command/archive.sh -f /home/john/Downloads -d 10 -a /home/john/backups/friday-backup
30 2 1 * * php /path-to-command/archive.php -f /home/john/Downloads -d 10 -a /home/john/backups/friday-backup

```
