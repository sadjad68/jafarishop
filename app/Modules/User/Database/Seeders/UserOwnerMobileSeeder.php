<?php

namespace App\Modules\User\Database\Seeders;

use App\Library\SiteHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Modules\User\Entities\User;
use App\Modules\User\Entities\UserType;

class UserOwnerMobileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userType = UserType::orderBy('id', 'ASC')
            ->where('type', 'Admin')
            ->whereHas('user')
            ->firstOrFail();

        $user = User::where('id', $userType->user_id)->firstOrFail();

        if (! blank($user->mobile)) {
            return;
        }

        $site = SiteHelper::getInformation();
        if (! $site || blank($site['template_url'] ?? null)) {
            return;
        }

        $siteUrl = $site['template_url'];

        $response = Http::withoutVerifying()->get('https://www.hamgaman.com/website-owner-mobile', [
            'url' => $siteUrl,
        ]);

        if (! $response->successful()) {
            return;
        }

        $ownerMobile = $response->json('owner_mobile');
        if (blank($ownerMobile)) {
            return;
        }

        $user->update([
            'mobile' => $ownerMobile,
        ]);
    }
}
