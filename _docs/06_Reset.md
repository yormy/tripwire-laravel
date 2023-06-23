# Resetting keys
Sometimes you need an ability to reset the block and logs. One of the use cases is that you are blocked yourself, or that you want security researches pentest your system.
One way of doing that is to disable Tripwire, but a much better way is to give them a reset key.
This url they can access to remove all blocks/logs with their ip.
So when they are blocked, they can access this url to release the block

In the config you can specify if they are soft or hard deleted.

Blocks that have the persistent flag set will not be removed. These flags need to be cleared before the blocks can be removed.
The use case is that sometimes you want to keep certain ips to remain blocked no matter what.
Admin first need to unpersist a block, and only then it can be deleted.

The reset-urls are all signed


```
TRIPWIRE_RESET_ENABLED=true
TRIPWIRE_RESET_LINK_EXPIRATION_MINUTES=60*24*30
```
