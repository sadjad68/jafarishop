<?php

namespace App\Http\Controllers;

use App\Providers\ViewServiceProvider;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Contact\DTO\ContactDTO;
use App\Modules\Contact\Http\Requests\ContactRequest;
use App\Modules\Contact\Services\ContactService;

//Todo: ای پی آی جداگلنه از تنظیمات
class UsController extends Controller
{
    public function about()
    {
        return view('pages.about-us.index');
    }

    public function terms()
    {
        return view('pages.terms-and-regulations.index');
    }
    public function contact()
    {
        return view('pages.contact-us.index');
    }
    public function postContact(ContactRequest $request)
    {
       ContactService::create(ContactDTO::fromRequest($request));

            return Redirect::back()->with('success', 'پیام شما با موفقیت ثبت شد');

    }
    public function flushCache()
    {
        Cache::flush();
        ViewServiceProvider::clearCanonicalsCache();
        ViewServiceProvider::clearLayoutCache();

        return redirect()->to('/')->with('success', 'کش با موفقیت پاک شد');
    }


    public function clearLayoutCache()
    {
        ViewServiceProvider::clearLayoutCache();
        ViewServiceProvider::clearCanonicalsCache();

        return redirect()->back()->with('success', 'کش تنظیمات، لایه و کنونیکال با موفقیت پاک شد');
    }
    public function showRobots()
    {
        $url = route('sitemap');
        $content = "User-agent: *
Allow: /
Disallow: /admin
Disallow: /search?*
Disallow: *utm_*
Disallow: /checkout*
Disallow: /register*
Disallow: /cart/*
Disallow: /api/*
Disallow: /panel/*
Disallow: /panell/*
Disallow: /getShoping?*
Sitemap: $url";
        return response($content)
            ->header('Content-Type', 'text/plain');
    }

}
