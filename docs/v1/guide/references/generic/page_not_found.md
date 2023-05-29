# Page Not Found
:::tip Goal
Normally a user clicks through your app from page to page and should never see a 404 Page Not found. 
Landing on a non-existing page could be an indication of someone prying on your system.
:::

## Additional Installation
:::tip Installation
The following wires need the [ExceptionInspector](../../advanced/setup/exceptions) to be setup
:::

In the config you can specify which pages are excluded or included

## Enabled
Enable or disable this wire

<!--@include: ../wires/_attackscore.md-->

<!--@include: ../wires/_urls.md-->

# Example
Record all page not founds, except those that start with ```admin```
```php
    ->urls(
        UrlsConfig::make()
            ->except([
                'admin/*'
                ]
            ));
```
