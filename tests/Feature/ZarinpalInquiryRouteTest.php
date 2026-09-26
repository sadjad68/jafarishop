<?php

namespace Tests\Feature;

use Tests\TestCase;

class ZarinpalInquiryRouteTest extends TestCase
{
    public function test_inquire_route_rejects_invalid_token(): void
    {
        $this->get('/inquire-zarinpal-payments/invalid-token')
            ->assertStatus(403);
    }
}
