## Attack score
This is this wire severity, the higher the number the more severe. All attackScores will be summarized and if it exceeds the PunishScore the block will be activated.
Set this to a number that reflects the severity.
* A very high number will immediately block the user/ip
* A low number will only block if there are many requests

:::tip
**sqli** and **xss** are very common attack vectors with high confidence detection. You should set those to a very high number
:::
