<?php

namespace Dealskoo\Deal\Tests\Feature\Admin;

use Dealskoo\Admin\Models\Admin;
use Dealskoo\Deal\Models\Deal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dealskoo\Deal\Tests\TestCase;

class DealControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $admin = Admin::factory()->isOwner()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.deals.index'));
        $response->assertStatus(200);
    }

    public function test_table()
    {
        $admin = Admin::factory()->isOwner()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.deals.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        $response->assertJsonPath('recordsTotal', 0);
        $response->assertStatus(200);
    }

    public function test_show()
    {
        $admin = Admin::factory()->isOwner()->create();
        $deal = Deal::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.deals.show', $deal));
        $response->assertStatus(200);
    }

    public function test_edit()
    {
        $admin = Admin::factory()->isOwner()->create();
        $deal = Deal::factory()->create();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.deals.edit', $deal));
        $response->assertStatus(200);
    }

    public function test_update()
    {
        $admin = Admin::factory()->isOwner()->create();
        $deal = Deal::factory()->create();
        $response = $this->actingAs($admin, 'admin')->put(route('admin.deals.update', $deal), [
            'slug' => 'deals',
            'approved' => true
        ]);
        $response->assertStatus(302);
    }
}
