<?php

namespace Ambulatory\Tests\Feature;

use Ambulatory\HealthFacility;
use Ambulatory\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageHealthFacilityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_not_manage_health_facilities()
    {
        $healthFacility = factory(HealthFacility::class)->create();

        $this->getJson(route('ambulatory.health-facilities'))->assertStatus(401);
        $this->postJson(route('ambulatory.health-facilities'), [])->assertStatus(401);
        $this->getJson(route('ambulatory.health-facilities.show', $healthFacility->id))->assertStatus(401);
        $this->patchJson(route('ambulatory.health-facilities.update', $healthFacility->id), [])->assertStatus(401);
    }

    /** @test */
    public function unauthorized_users_can_not_create_a_new_health_facility()
    {
        $this->signInAsDoctor();
        $this->postJson(route('ambulatory.health-facilities.store'), [])->assertStatus(403);

        $this->signInAsPatient();
        $this->postJson(route('ambulatory.health-facilities.store'), [])->assertStatus(403);
    }

    /** @test */
    public function admin_can_create_a_new_health_facility()
    {
        $this->signInAsAdmin();

        $attrributes = factory(HealthFacility::class)->raw();

        $this->postJson(route('ambulatory.health-facilities.store'), $attrributes)
            ->assertStatus(201)
            ->assertJson($attrributes);

        $this->assertDatabaseHas('ambulatory_health_facilities', $attrributes);
    }

    /** @test */
    public function unauthorized_users_can_not_update_a_health_facility()
    {
        $healthFacility = factory(HealthFacility::class)->create();

        $this->signInAsDoctor();
        $this->patchJson(route('ambulatory.health-facilities.update', $healthFacility->id), [])->assertStatus(403);

        $this->signInAsPatient();
        $this->patchJson(route('ambulatory.health-facilities.update', $healthFacility->id), [])->assertStatus(403);
    }

    /** @test */
    public function admin_can_update_a_health_facility()
    {
        $this->signInAsAdmin();

        $healthFacility = factory(HealthFacility::class)->create();

        $this->patchJson(route('ambulatory.health-facilities.update', $healthFacility->id),
            $attributes = factory(HealthFacility::class)->raw([
                'name' => 'Name Changed',
                'city' => 'City Changed',
            ]))
            ->assertOk()
            ->assertJson($attributes);

        $this->assertNotSame($healthFacility->slug, 'name-changed-city-changed');

        $this->assertDatabaseHas('ambulatory_health_facilities', ['slug' => 'name-changed-city-changed']);
    }
}
