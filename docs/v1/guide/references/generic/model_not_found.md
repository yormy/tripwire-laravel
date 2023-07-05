# Model Not Found
:::tip Goal
Normally a user clicks through your app from page to page and should never see a Model Not found.
This typically happens when a hacker tries to access resources by modifying the url to see if they have access.
A model is accessed which is not available. This could be an attempt of direct-object-access which failed. A normal user should never see this and thus this might be an indication
:::

## Additional Installation
:::tip Installation
The following wires need the [ExceptionInspector](../../advanced/setup/exceptions) to be setup
:::

## Enabled
Enable or disable this wire

<!--@include: ./_methods.md-->

<!--@include: ./_attackscore.md-->

## Tripwires
Specify which models you want to include /exclude the tripwires

## Example
The following example will trip for every model not found, except the ```DoNotReportMyModel``` class
```php
    ->tripwires([
        MissingModelConfig::make()->except([
            DoNotReportMyModel::class,
        ]),
    ]);
```
