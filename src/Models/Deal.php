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
        'cover', 'cover_url'
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

    }

    public function scopeBigDiscount(Builder $builder)
    {

    }

    public function scopeFreeShipping(Builder $builder)
    {

    }

    public function scopeLimitedTime(Builder $builder)
    {

    }

    public function scopeZone(Builder $builder)
    {

    }
}
