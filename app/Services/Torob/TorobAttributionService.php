<?php

namespace App\Services\Torob;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Modules\Order\Entities\Basket;

class TorobAttributionService
{
    const COOKIE_NAME = 'torob_clid';

    private static function lifetimeHours(): int
    {
        return max(1, (int) config('torob.attribution_lifetime_hours', 168));
    }

    private static function lifetimeMinutes(): int
    {
        return self::lifetimeHours() * 60;
    }

    public static function handle(Request $request)
    {
        if ($request->is('torob_api/*')) {
            return;
        }

        if (self::isTorobEntry($request)) {
            self::store(trim((string) $request->query('torob_clid')));
        } else {
            self::expireIfNeeded();
        }

        self::syncBasket();
    }

    public static function syncBasket()
    {
        $clid = self::getValidClid();
        $basket = Basket::authUser()->orderBy('id', 'DESC')->first();

        if (!$basket) {
            return;
        }

        if ($clid === null) {
            if ($basket->torob_clid != null) {
                $basket->update(['torob_clid' => null]);
            }
            return;
        }

        if ($basket->torob_clid != $clid) {
            $basket->update(['torob_clid' => $clid]);
        }
    }

    public static function getForOrder(Basket $basket)
    {
        self::expireIfNeeded();
        $clid = self::getValidClid();

        if ($clid == null) {
            return null;
        }

        if ($basket->torob_clid != $clid) {
            $basket->update(['torob_clid' => $clid]);
        }

        return $clid;
    }

    private static function isTorobEntry(Request $request)
    {
        $clid = trim((string) $request->query('torob_clid'));

        return $clid != '';
    }

    private static function store($clid)
    {
        $expiresAt = Carbon::now()->addHours(self::lifetimeHours())->timestamp;

        Cookie::queue(cookie(
            self::COOKIE_NAME,
            json_encode(['clid' => $clid, 'expires_at' => $expiresAt]),
            self::lifetimeMinutes(),
            '/',
            null,
            request()->isSecure(),
            true
        ));
    }

    private static function getValidClid()
    {
        $payload = self::readCookie();

        if ($payload == null || self::isExpired($payload)) {
            return null;
        }

        return @$payload['clid'];
    }

    private static function expireIfNeeded()
    {
        $payload = self::readCookie();

        if ($payload == null) {
            return;
        }

        if (self::isExpired($payload)) {
            Cookie::queue(Cookie::forget(self::COOKIE_NAME));
            self::clearBasket();
        }
    }

    private static function readCookie()
    {
        $raw = request()->cookie(self::COOKIE_NAME);

        if ($raw == null || $raw == '') {
            return null;
        }

        $data = json_decode($raw, true);

        if (!is_array($data) || empty($data['clid'])) {
            return null;
        }

        return $data;
    }

    private static function isExpired($payload)
    {
        return Carbon::now()->timestamp >= intval(@$payload['expires_at']);
    }

    private static function clearBasket()
    {
        $basket = Basket::authUser()->orderBy('id', 'DESC')->first();

        if ($basket && $basket->torob_clid != null) {
            $basket->update(['torob_clid' => null]);
        }
    }
}
