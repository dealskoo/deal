<?php

namespace Dealskoo\Deal\Tests\Feature\Seller;

use Carbon\Carbon;
use Dealskoo\Country\Models\Country;
use Dealskoo\Deal\Models\Deal;
use Dealskoo\Product\Models\Product;
use Dealskoo\Seller\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dealskoo\Deal\Tests\TestCase;
use Illuminate\Support\Arr;

class DealControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $seller = Seller::factory()->create();
        $response = $this->actingAs($seller, 'seller')->get(route('seller.deals.index'));
        $response->assertStatus(200);
    }

    public function test_table()
    {
        $seller = Seller::factory()->create();
        $response = $this->actingAs($seller, 'seller')->get(route('seller.deals.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
        $response->assertJsonPath('recordsTotal', 0);
        $response->assertStatus(200);
    }

    public function test_create()
    {
        $country = Country::factory()->create(['alpha2' => 'US']);
        $seller = Seller::factory()->create();
        $response = $this->actingAs($seller, 'seller')->get(route('seller.deals.create'));
        $response->assertStatus(200);
    }

    public function test_store()
    {
        $seller = Seller::factory()->create();
        $product = Product::factory()->approved()->create(['seller_id' => $seller->id]);
        $deal = Deal::factory()->make(['seller_id' => $seller->id, 'product_id' => $product->id]);
        $response = $this->actingAs($seller, 'seller')->post(route('seller.deals.store'), Arr::collapse([$deal->only([
            'title',
            'price',
            'product_id',
            'ship_fee'
        ]), ['activity_date' => Carbon::parse($deal->start_at)->format('m/d/Y') . ' - ' . Carbon::parse($deal->end_at)->format('m/d/Y')]]));
        $response->assertStatus(302);
    }

    public function test_edit()
    {
        $country = Country::factory()->create(['alpha2' => 'US']);
        $seller = Seller::factory()->create();
        $deal = Deal::factory()->create(['seller_id' => $seller->id, 'country_id' => $country->id]);
        $response = $this->actingAs($seller, 'seller')->get(route('seller.deals.edit', $deal));
        $response->assertStatus(200);
    }

    public function test_update()
    {
        $seller = Seller::factory()->create();
        $deal = Deal::factory()->create(['seller_id' => $seller->id]);
        $deal1 = Deal::factory()->make();
        $response = $this->actingAs($seller, 'seller')->put(route('seller.deals.update', $deal), $deal1->only([
            'title',
            'price',
            'product_id',
            'ship_fee'
        ]));
        $response->assertStatus(302);
    }

    public function test_destroy()
    {
        $seller = Seller::factory()->create();
        $deal = Deal::factory()->create(['seller_id' => $seller->id]);
        $response = $this->actingAs($seller, 'seller')->delete(route('seller.deals.destroy', $deal));
        $response->assertStatus(200);
    }
}
