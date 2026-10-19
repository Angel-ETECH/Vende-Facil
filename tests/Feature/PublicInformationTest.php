<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicInformationTest extends TestCase
{
    public function test_help_terms_and_privacy_are_public_and_link_to_support(): void
    {
        config(['vendefacil.support_whatsapp' => '51999999999']);
        foreach (['help', 'terms', 'privacy'] as $name) {
            $this->get(route($name))->assertOk()
                ->assertSee('https://wa.me/51999999999', false)
                ->assertSee(route('terms'), false)->assertSee(route('privacy'), false)
                ->assertSee('<link rel="canonical"', false);
        }
    }

    public function test_plan_information_describes_the_existing_single_payment_model(): void
    {
        $this->get(route('terms'))->assertOk()->assertSee('pago único')->assertSee('sin vencimiento programado');
        $this->get(route('help'))->assertOk()->assertSee('revisión')->assertSee('mensual');
        $this->get(route('register'))->assertOk()->assertSee(route('terms'), false)->assertSee(route('privacy'), false);
    }
}
