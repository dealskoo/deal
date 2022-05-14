<?php

namespace Dealskoo\Deal\Models;

use Carbon\Carbon;
use Dealskoo\Admin\Traits\HasSlug;
use Dealskoo\Brand\Traits\HasBrand;
use Dealskoo\Category\Traits\HasCategory;
use Dealskoo\Country\Traits\HasCountry;
use Dealskoo\Platform\Traits\HasPlatform;
use Dealskoo\Product\Traits\HasProduct;
use Dealskoo\Seller\Traits\HasSeller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{
    use HasFactory, SoftDeletes, HasSlug, HasCategory, HasCountry, HasSeller, HasBrand, HasPlatform, HasProduct;

    protected $appends = [
        'cover', 'cover_url', 'off'
    ];

    protected $fillable = [
        'title',
        'slug',
        'price',
        'ship_fee',
        'seller_id',
        'product_id',
        'category_id',
        'country_id',
        'brand_id',
        'platform_id',
        'recommend',
        'approved_at',
        'start_at',
        'end_at'
    ];

    protected $casts = [
        'recommend' => 'boolean',
        'approved_at' => 'datetime',
        'start_at' => 'datetime',
        'end_at' => 'datetime'
    ];

    public function getCoverAttribute()
    {
        return $this->product->cover;
    }

    public function getCoverUrlAttribute()
    {
        return $this->product->cover_url;
    }

    public function getOffAttribute()
    {
        return round((1 - ($this->price / $this->product->price)) * 100);
    }

    public function scopeApproved(Builder $builder)
    {
        return $builder->whereNotNull('approved_at');
    }

    public function scopeAvaiabled(Builder $builder)
    {
        $now = Carbon::now();
        return $builder->whereNotNull('approved_at')->where('start_at', '<=', $now)->where('end_at', '>=', $now);
    }

    public function scopeBestDeals(Builder $builder)
    {
        $now = Carbon::now();
        return $builder->whereNotNull('approved_at')->where('start_at', '<=', $now)->where('end_at', '>=', $now)->where('recommend', true);
    }

    public function scopeBigDiscount(Builder $builder)
    {
        $now = Carbon::now();
        $price = $this->product->price / 4;
        return $builder->whereNotNull('approved_at')->where('start_at', '<=', $now)->where('end_at', '>=', $now)->where('price', '<=', $price);
    }

    public function scopeFreeShipping(Builder $builder)
    {
        $now = Carbon::now();
        return $builder->whereNotNull('approved_at')->where('start_at', '<=', $now)->where('end_at', '>=', $now)->where('ship_fee', '=', 0);
    }

    public function scopeLimitedTime(Builder $builder)
    {
        $now = Carbon::now();
        $end = $now->addDay();
        return $builder->whereNotNull('approved_at')->where('start_at', '<=', $now)->where('end_at', '<=', $end);
    }

    public function scopeZone(Builder $builder)
    {
        $now = Carbon::now();
        return $builder->whereNotNull('approved_at')->where('start_at', '<=', $now)->where('end_at', '>=', $now)->where('price', '<=', 0.01);
    }
}
