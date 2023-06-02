# TODO
requestsource to static functions
user + ip address to changable function in config

Blocks before user is known
Blocks after user is known
BlockFingerpinthandler + BlockUserHAndler
//            'referrer' => substr($this->request->server('HTTP_REFERER'), 0, 191) ?: 'NULL',
//            'request' => substr($input, 0, config('firewall.log.max_request_size')),

listen to login events and take actiosn

Make all config, dataobjects

field encryption


'abort' => env('FIREWALL_BLOCK_ABORT', false), // true or false, or make this a code ? or message
refactor helper->public function isInput($name, $middleware = null)
# Management:
Way of reset for hackers, how
-Signed-dated url per user
-record resets
-how to generate / give out ?
$table->string('xid')->unique(); // customizable ? // still neeeded ?

# Unit tests

# Documentation
