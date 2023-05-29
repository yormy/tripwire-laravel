## Definitions

### Log
::: tip Definition: Log
Every request that is recognized as a hack attempt is logged regardless it blocks the rest of the request or not.
:::

### Block
::: tip Definition: Block
A block prevents a certain user or Ip from accessing your site. As long a the block is valid no requests will continue to your site.
This block is only temporarily and will be removed after a few seconds. However if the same user/ip continues their attempts and gets blocked again the time will increase exponentially.
A block can be based on a ip address, user id, and or browser-fingerprint (if supplied by your frontend)
:::

###
::: tip Definition: Reject
When a request is suspicious it is rejected and this could lead eventually to a block
:::


### Wire
::: tip Definition: Wire
A checked that parses the request to see it if violates certain rules. If a wire is triggered it is considered as a hack attempt
:::

### Honeypot Wire
::: tip Definition: Honeypot wire
A honeypot is a security mechanism that creates a virtual trap to lure attackers. When Tripwire recognizes that certain illegal fields are filled in, then we know this is not a normal user and an action will be taken
:::

### AttackScore
::: tip Definition: AttackScore
Every wire has a attackScore (either specified or default), the higher the score the more severe and certain you are that this is a malicious request.
:::

### Punish
::: tip Definition: Punish
When the user attempts too many times, the user is blocked (or punished).
:::
