# Customization and Extension - todo



## Changing the ruleset
When you change the ruleset it is good to manually run all tests again to test if their are no suddenly false positives or false negatives added.

Run the following command to test your config and changes to make sure all test and checkers remain working
best is to have your application
Test extensive to run a whole list of possible attack vectors to see if they are all blocked

Suggestion:
- phpunit env set to use sqllite in memory

Or
- use a real database
- set tripwire debugMode to true
  so you can see what input was triggered by what rule

```
./vendor/bin/phpunit ./vendor/yormy/tripwire-laravel/src/Tests --testdox --testsuite Main
./vendor/bin/phpunit ./vendor/yormy/tripwire-laravel/src/Tests --testdox --testsuite Extensive
```

# Customizing the database
```bash
php artisan vendor:publish --provider="Yormy\TripwireLaravel\TripwireServiceProvider" --tag="migrations"
```

# Customizing the translations
```bash
php artisan vendor:publish --provider="Yormy\TripwireLaravel\TripwireServiceProvider" --tag="translations"
```

# Customizing the config
```bash
php artisan vendor:publish --provider="Yormy\TripwireLaravel\TripwireServiceProvider" --tag="config"
```

# Customizing the views
```bash
php artisan vendor:publish --provider="Yormy\TripwireLaravel\TripwireServiceProvider" --tag="views"
```



# Extend action classes
# Encrypt user data
