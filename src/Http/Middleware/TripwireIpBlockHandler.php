<?php

namespace Yormy\TripwireLaravel\Http\Middleware;


use Yormy\TripwireLaravel\Exceptions\RequestChecksumFailedException;
use Yormy\TripwireLaravel\Repositories\BlockRepository;
use Yormy\TripwireLaravel\Services\HashService;
use Closure;
use Illuminate\Http\Request;
use Yormy\TripwireLaravel\Services\IpAddress;

class TripwireIpBlockHandler
{
    /**
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $ipAddress = IpAddress::get($request);

        $blockRepository = new BlockRepository();
        $message = $blockRepository->isIpBlocked($ipAddress);
        dd($message);

        // then respond
        return  $next($request);
    }
}
