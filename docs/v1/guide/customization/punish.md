# Customizing the punish rules
A punishment is when a block is added based on multiple rejections of a wire.
Depending on the total sum of all violations by that user/ip there is a block added

### Punish Score
You need to set the trigger score (ie 1000)
So when the sum of all the violations is 1000 or more, then the block is added.
Hence 4 violations of 250 will trigger the block
or 2 of 250 and 5 less severe of 100 will also trigger the block

### Timeframe
Specify the timeframe of which the score is calculated in minures
Calculate all violations in the last day  = 60 *24

### Penalty time
Specify the block time in seconds
note this will log increase on every violation that leads to a block
the first block will be for 5 seconds, de second for 25, the 3rd block is about 2 min, the 5th block is almost an hour,
6th block is 10 days, 7th block is 54 days ...

```php
    ->punish(1000, 60 * 24, 5)
```

