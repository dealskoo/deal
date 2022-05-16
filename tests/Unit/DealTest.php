<?php

namespace Dealskoo\Deal\Tests\Unit;

use Dealskoo\Deal\Models\Deal;
use Dealskoo\Deal\Tests\TestCase;
use Dealskoo\Image\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class DealTest extends TestCase
{
    use RefreshDatabase;

    public function test_slug()
    {
        $slug = 'Product';
        $deal = Deal::factory()->create();
        $deal->slug = $slug;
        $deal->save();
        $deal->refresh();
        $this->assertEquals($deal->slug, Str::lower($slug));
    }

    public function test_country()
    {
        $deal = Deal::factory()->create();
        $this->assertNotNull($deal->country);
    }

    public function test_category()
    {
        $deal = Deal::factory()->create();
        $this->assertNotNull($deal->category);
    }

    public function test_brand()
    {
        $deal = Deal::factory()->create();
        $this->assertNotNull($deal->brand);
    }

    public function test_platform()
    {
        $deal = Deal::factory()->create();
        $this->assertNotNull($deal->platform);
    }

    public function test_seller()
    {
        $deal = Deal::factory()->create();
        $this->assertNotNull($deal->seller);
    }

    public function test_cover()
    {
        $image = Image::factory()->make();
        $deal = Deal::factory()->create();
        $deal->product->images()->save($image);
        $this->assertNotNull($deal->cover);
    }

    public function test_off()
    {
        $deal = Deal::factory()->create();
        $this->assertLessThan($deal->product->price, $deal->price);
        $this->assertLessThan(100, $deal->off);
    }

    public function test_approved()
    {
        $deal = Deal::factory()->approved()->create();
        $this->assertCount(Deal::approved()->count(), Deal::all());
    }

    public function test_avaiabled()
    {
        $deal = Deal::factory()->avaiabled()->create();
        $this->assertCount(Deal::avaiabled()->count(), Deal::all());
    }

    public function test_bestDeals()
    {
        $deal = Deal::factory()->avaiabled()->recommend()->create();
        $this->assertCount(Deal::bestDeals()->count(), Deal::all());
    }

    public function test_bigDiscount()
    {
        $deal = Deal::factory()->avaiabled()->bigDiscount()->create();
        $this->assertCount(Deal::bigDiscount()->count(), Deal::all());
    }

    public function test_freeShipping()
    {
        $deal = Deal::factory()->avaiabled()->create(['ship_fee' => 0]);
        $this->assertCount(Deal::freeShipping()->count(), Deal::all());
    }

    public function test_limitedTime()
    {
        $deal = Deal::factory()->limitedTime()->create();
        $this->assertCount(Deal::limitedTime()->count(), Deal::all());
    }

    public function test_zero()
    {
        $deal = Deal::factory()->avaiabled()->create(['price' => 0]);
        $this->assertCount(Deal::zero()->count(), Deal::all());
    }
}
