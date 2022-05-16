@extends('admin::layouts.panel')

@section('title',__('deal::deal.edit_deal'))
@section('body')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a
                                href="{{ route('admin.dashboard') }}">{{ __('admin::admin.dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('deal::deal.edit_deal') }}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ __('deal::deal.edit_deal') }}</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.deals.update',$deal) }}" method="post">
                        @csrf
                        @method('PUT')
                        @if(!empty(session('success')))
                            <div class="alert alert-success">
                                <p class="mb-0">{{ session('success') }}</p>
                            </div>
                        @endif
                        @if(!empty($errors->all()))
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <p class="mb-0">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">{{ __('deal::deal.title') }}</label>
                                <input type="text" class="form-control" id="title" name="title" required
                                       value="{{ old('title',$deal->title) }}"
                                       placeholder="{{ __('deal::deal.title_placeholder') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="product_id"
                                       class="form-label">{{ __('deal::deal.product') }}</label>
                                <input type="text" class="form-control" id="title" name="title" readonly
                                       value="{{ old('title',$deal->product->name) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="slug" class="form-label">{{ __('deal::deal.slug') }}</label>
                                <input type="text" class="form-control" id="slug" name="slug" required
                                       value="{{ old('title',$deal->slug) }}" autofocus tabindex="1"
                                       placeholder="{{ __('deal::deal.slug_placeholder') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="country_id"
                                       class="form-label">{{ __('deal::deal.country') }}</label>
                                <input type="text" class="form-control" id="country_id" name="country_id" readonly
                                       value="{{ $deal->country->name }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category_id"
                                       class="form-label">{{ __('deal::deal.category') }}</label>
                                <input type="text" class="form-control" id="category_id" name="category_id" readonly
                                       value="{{ $deal->category->name }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="seller_id"
                                       class="form-label">{{ __('deal::deal.seller') }}</label>
                                <input type="text" class="form-control" id="seller_id" name="seller_id" readonly
                                       value="{{ $deal->seller->name }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="brand_id"
                                       class="form-label">{{ __('deal::deal.brand') }}</label>
                                <input type="text" class="form-control" id="brand_id" name="brand_id" readonly
                                       value="{{ $deal->brand->name }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="platform_id"
                                       class="form-label">{{ __('deal::deal.platform') }}</label>
                                <input type="text" class="form-control" id="platform_id" name="platform_id" readonly
                                       value="{{ $deal->platform->name }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="product_price"
                                       class="form-label">{{ __('deal::deal.product_price') }}</label>
                                <input type="number" class="form-control" id="product_price" name="product_price"
                                       readonly
                                       value="{{ $deal->product->price }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">{{ __('deal::deal.price') }}</label>
                                <input type="number" class="form-control" id="price" name="price" readonly
                                       value="{{ old('price',$deal->price) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="ship_fee" class="form-label">{{ __('deal::deal.ship_fee') }}</label>
                                <input type="number" class="form-control" id="ship_fee" name="ship_fee" readonly
                                       value="{{ $deal->ship_fee }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="activity_date" class="form-label">{{ __('deal::deal.start_at') }}
                                    - {{ __('deal::deal.end_at') }}</label>
                                <input type="text" class="form-control date" id="activity_date" name="activity_date"
                                       value="{{ \Carbon\Carbon::parse($deal->start_at)->format('m/d/Y').' - '.\Carbon\Carbon::parse($deal->end_at)->format('m/d/Y') }}"
                                       readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="approved" name="approved"
                                           tabindex="1"
                                           value="1" {{ $deal->approved_at?'checked':'' }}>
                                    <label for="approved"
                                           class="form-check-label">{{ __('deal::deal.approved') }}</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="recommend" name="recommend"
                                           tabindex="1"
                                           value="1" {{ $deal->recommend?'checked':'' }}>
                                    <label for="recommend"
                                           class="form-check-label">{{ __('deal::deal.recommend') }}</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="big_discount" name="big_discount"
                                           tabindex="1"
                                           value="1" {{ $deal->big_discount?'checked':'' }}>
                                    <label for="big_discount"
                                           class="form-check-label">{{ __('deal::deal.big_discount') }}</label>
                                </div>
                            </div>
                        </div> <!-- end row -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-success mt-2" tabindex="6"><i
                                    class="mdi mdi-content-save"></i> {{ __('admin::admin.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
