<?php

namespace Tests\Unit;

use App\Services\Legacy\LegacyMapper;
use PHPUnit\Framework\TestCase;

class LegacyMapperTest extends TestCase
{
    public function test_order_status_mapping(): void
    {
        $this->assertTrue(LegacyMapper::isBasketOrder(1));
        $this->assertFalse(LegacyMapper::isBasketOrder(2));
        $this->assertSame('paying', LegacyMapper::orderStatus(2));
        $this->assertSame('paid', LegacyMapper::orderStatus(3));
        $this->assertSame('paid', LegacyMapper::orderStatus(4));
        $this->assertSame(5, LegacyMapper::shippingStatusId(4));
        $this->assertNull(LegacyMapper::shippingStatusId(3));
        $this->assertSame('unpaid', LegacyMapper::orderStatus(5));
    }

    public function test_discount_type_mapping(): void
    {
        $this->assertSame('percent', LegacyMapper::discountType(1));
        $this->assertSame('percent', LegacyMapper::discountType('1'));
        $this->assertSame('cash', LegacyMapper::discountType(2));
        $this->assertSame('cash', LegacyMapper::discountType('cash'));
    }

    public function test_seo_morph_mapping(): void
    {
        $this->assertSame(LegacyMapper::PRODUCT, LegacyMapper::seoMorph('App\\Models\\Product'));
        $this->assertSame(LegacyMapper::BLOG, LegacyMapper::seoMorph('App\\\\Models\\\\Post'));
        $this->assertNull(LegacyMapper::seoMorph('App\\Models\\Brand'));
        $this->assertNull(LegacyMapper::seoMorph(null));
    }

    public function test_latest_price_uses_newest_live_row_then_fallback(): void
    {
        $price = LegacyMapper::latestPrice([
            ['id' => 1, 'price' => 1000, 'deleted_at' => null],
            ['id' => 4, 'price' => 2500, 'deleted_at' => '2024-01-01 00:00:00'],
            ['id' => 3, 'price' => 1800, 'deleted_at' => null],
        ], 900);

        $this->assertSame('1800', $price);
        $this->assertSame('900', LegacyMapper::latestPrice([
            ['id' => 2, 'price' => 0, 'deleted_at' => null],
        ], '900'));
        $this->assertNull(LegacyMapper::latestPrice([], null));
        $this->assertSame(1, LegacyMapper::stockForPrice('1800'));
        $this->assertSame(0, LegacyMapper::stockForPrice(null));
    }

    public function test_service_order_maps_to_request_fields(): void
    {
        $this->assertSame('service-9', LegacyMapper::serviceUrl(9, 'روغن موتور'));
        $this->assertSame('ثبت شده', LegacyMapper::serviceOrderStatusLabel(1));
        $this->assertSame('انجام شده', LegacyMapper::serviceOrderStatusLabel(2));
        $this->assertFalse(LegacyMapper::serviceRequestIsRead(1));
        $this->assertTrue(LegacyMapper::serviceRequestIsRead(2));
        $this->assertSame(
            "خدمت: هدلایت\nوضعیت: ثبت شده\nکد تراکنش: t1\nکد پیگیری: r1\nکار انجام‌شده: نصب",
            LegacyMapper::serviceRequestDescription('هدلایت', 1, 't1', 'r1', 'نصب')
        );
    }

    public function test_legacy_settings_phone_and_links(): void
    {
        $this->assertSame(
            ['02133943509', '09121238054'],
            LegacyMapper::phoneNumbers('۳۳۹۴۳۵۰۹ - ۰۲۱  - ۰۹۱۲۱۲۳۸۰۵۴')
        );
        $this->assertSame('تاپیک کالا', LegacyMapper::siteNameFromSeoTitle('خرید آنلاین| تاپیک کالا'));
        $this->assertSame('/products', LegacyMapper::siteLink('http://www.topickala.ir/products'));
        $this->assertNull(LegacyMapper::siteLink('/tmp/phpTZuyVn'));
        $this->assertSame('https://wa.me/989366183382', LegacyMapper::siteLink('https://wa.me/989366183382'));
        $this->assertSame('9366183382', LegacyMapper::whatsappNumber('https://wa.me/989366183382'));
    }

    public function test_redirect_paths_keep_old_shop_routes(): void
    {
        $this->assertSame([
            ['old' => 'categories/4', 'new' => 'oil'],
        ], LegacyMapper::categoryRedirects(4, '/oil'));

        $this->assertSame([
            ['old' => 'products/12', 'new' => 'oil/lamp'],
        ], LegacyMapper::productRedirects(12, 'lamp', 'oil'));

        $this->assertSame([
            ['old' => 'post/guide', 'new' => 'article/news/guide'],
        ], LegacyMapper::postRedirects(8, 'guide', 'news'));

        $this->assertSame([
            ['old' => 'brand/behran', 'new' => 'brands/3'],
        ], LegacyMapper::brandRedirects(3, 'https://topickala.ir/behran'));
    }
}
