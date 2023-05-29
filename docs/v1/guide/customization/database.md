# Customizing the database
```bash
php artisan vendor:publish --provider="Yormy\TripwireLaravel\TripwireServiceProvider" --tag="migrations"
```

    ->databaseTables(
        'tripwire_logs',
        'tripwire_blocks'
    )
