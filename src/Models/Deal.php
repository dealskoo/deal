<?php

namespace Dealskoo\Deal\Models;

use Dealskoo\Admin\Traits\HasSlug;
use Dealskoo\Brand\Traits\HasBrand;
use Dealskoo\Category\Traits\HasCategory;
use Dealskoo\Country\Traits\HasCountry;
use Dealskoo\Favorite\Traits\Favoriteable;
use Dealskoo\Like\Traits\Likeable;
use Dealskoo\Platform\Traits\HasPlatform;
use Dealskoo\Product\Traits\HasProduct;
use Dealskoo\Seller\Traits\HasSeller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Deal extends Model
{
    use HasFactory, SoftDeletes, HasSlug, HasCategory, HasCountry, HasSeller, HasBrand, HasPlatform, HasProduct, Likeable, Favoriteable, Searchable;

    protected $appends = [
        'cover', 'cover_url', 'off'
    ];

    protected $fillable = [
        'title',
        'slug',
        'price',
        'ship_fee',
        'clicks',
        'seller_id',
        'product_id',
        'category_id',
        'country_id',
        'brand_id',
        'platform_id',
        'recommend',
        'big_discount',
        'approved_at',
        'start_at',
        'end_at'
    ];

    protected $casts = [
        'recommend' => 'boolean',
        'big_discount' => 'boolean',
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
        $now = now();
        return $builder->whereNotNull('approved_at')->where('start_at', '<=', $now)->where('end_at', '>=', $now);
    }

    public function scopeBestDeals(Builder $builder)
    {
        $now = now();
        return $builder->whereNotNull('approved_at')->where('start_at', '<=', $now)->where('end_at', '>=', $now)->where('recommend', true);
    }

    public function scopeBigDiscount(Builder $builder)
    {
        $now = now();
        return $builder->whereNotNull('approved_at')->where('start_at', '<=', $now)->where('end_at', '>=', $now)->where('big_discount', true);
    }

    public function scopeFreeShipping(Builder $builder)
    {
        $now = now();
        return $builder->whereNotNull('approved_at')->where('start_at', '<=', $now)->where('end_at', '>=', $now)->where('ship_fee', '=', 0);
    }

    public function scopeLimitedTime(Builder $builder)
    {
        $now = now();
        $end = $now->addDay();
        return $builder->whereNotNull('approved_at')->where('start_at', '<=', $now)->where('end_at', '<=', $end);
    }

    public function scopeZero(Builder $builder)
    {
        $now = now();
        return $builder->whereNotNull('approved_at')->where('start_at', '<=', $now)->where('end_at', '>=', $now)->where('price', '<=', 0.01);
    }

    public function shouldBeSearchable()
    {
        return $this->approved_at ? true : false;
    }

    public function toSearchableArray()
    {
        return $this->only([
            'title',
            'slug',
            'price',
            'ship_fee',
            'clicks',
            'seller_id',
            'product_id',
            'category_id',
            'country_id',
            'brand_id',
            'platform_id',
            'big_discount',
            'start_at',
            'end_at'
        ]);
    }
}
