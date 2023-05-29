# Throttle Hit
:::tip Goal
When a user tries to send a request too many times than specified the rate limit/throttle prevents from further requests.
Every time the throttle prevents the request this wire is triggered.

**TIP** do not set this attackScore too high, as this could happen to normal users.
:::

## Additional Installation
The following wires need the [ExceptionInspector](../../advanced/setup/exceptions) to be setup

In the config you can specify which pages are excluded or included
