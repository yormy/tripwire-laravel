<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Services\Resolvers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yormy\TripwireLaravel\Actions\Interfaces\UserResolverInterface;

class UserResolver implements UserResolverInterface
{
    public static function getId(Request $request): int|string|null
    {
        return self::get($request)?->id;
    }

    public static function getType(Request $request): ?string
    {
        if (! self::get($request)) {
            return null;
        }

        return get_class(self::get($request));
    }

    public static function getCurrent(): ?Authenticatable
    {
        return Auth::user();
    }

    public static function getRandom(): ?Authenticatable
    {
        $member = self::getMemberClass();

        return $member->first();
    }

    public static function getMemberById(string|int $id): Authenticatable
    {
        $member = self::getMemberClass();

        return $member->where('id', $id)->firstOrFail();
    }

    public static function getAdminById(string|int $id): Authenticatable
    {
        $admin = self::getAdminClass();

        return $admin->where('id', $id)->firstOrFail();
    }

    public static function getMemberByXid(string|int $id): Authenticatable
    {
        $member = self::getMemberClass();

        return $member->where('xid', $id)->firstOrFail();
    }

    public static function getAdminByXid(string|int $id): Authenticatable
    {
        $admin = self::getAdminClass();

        return $admin->where('xid', $id)->firstOrFail();
    }

    public static function getMemberOnXId(string|int $xid): ?Authenticatable
    {
        $member = self::getMemberClass();

        return $member->where('xid', $xid)->first();
    }

    private static function get(Request $request): mixed
    {
        return $request->user();
    }

    private static function getMemberClass(): Model
    {
        $class = config('tripwire.models.member');

        return new $class();
    }

    private static function getAdminClass(): Model
    {
        $class = config('tripwire.models.admin');

        return new $class();
    }
}
