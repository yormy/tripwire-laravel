# Tripwire - Web Appplication Firewall

Tripwire definitions:

::: tip Definition One
a wire stretched close to the ground, working a trap, explosion, or alarm when disturbed and serving to detect or prevent people or animals entering an area.
:::

::: tip Definition Two
a comparatively weak military force employed as a first line of defence, engagement with which will trigger the intervention of stronger forces.
:::

## Overview
Tripwire-laravel is a package designed to early catch attempts to hack your site and stop those people right away.
It is designed to sit in front of every request (or the ones you specify) to listen to what is coming in and determine if that is good or not.
If it is an attempt to hack you site (ie Sqli, xss etc) then this attempt with lots of details will be logged.
If it happens multiple times the ip/user will be blocked from sending any request.
When a request is stopped you can specify what to return, a view, exception, just continue or whatever

This block is only temporarily and will be removed after a few seconds. However if the same user/ip continues their attempts and gets blocked again the time will increase exponentially.
When they are blocked a few times they will never be able to send requests.
When a user is blocked you can specify what to return, a view, exception etc.

Every type of attack has a certain attackScore. When the total of attackScores for this user within a certain timeframe exceeds a punishSCore then block will be added

There are MANY different configuration options, just check out the config for more details and explanations.

You can always see what attacks where causing a specific block

There are a few concepts:
* **Log** : Every request that is recognized as a hack attempt is logged regardless it blocks the rest of the request or not.
* **Block**: A block prevents a certain user or Ip from accessing your site. As long a the block is valid no requests will continue to your site.
* **Wire**: A checked that parses the request to see it if violates certain rules. If a wire is triggered it is considered as a hack attempt
* **AttackScore**: Every wire has a attackScore (either specified or default), the higher the score the more severe and certain you are that this is a malicious request.
* **Punish**: When the user attempts too many times, the user is blocked (or punished).

## Goal
To stop attackers from reaching your site and to block them as soon as possible
Tripwire-Laravel is an easy yet comprehensive and extendable way to add a security layer around laravel.
All in order to prevent hackers getting into your system and frustrates the heck out of them while trying

::: danger Limitation
Tripwire is not intented to validate or sanitize input, and also no substitute for proper coding. Your site should be well protected against different types of attack without tripwire in the first place. Tripwire will just add another layer or protection
:::

## Frontend
This package does not contain a frontend for managing blocked states or logged events.
I am currently working on a separate package to function as a frontend
