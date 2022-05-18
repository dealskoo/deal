@extends('seller::layouts.panel')

@section('title', __('deal::deal.add_deal'))
@section('body')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a
                                href="{{ route('seller.dashboard') }}">{{ __('seller::seller.dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('deal::deal.add_deal') }}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ __('deal::deal.add_deal') }}</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('seller.deals.store') }}" method="post">
                        @csrf
                        @if (!empty(session('success')))
                            <div class="alert alert-success">
                                <p class="mb-0">{{ session('success') }}</p>
                            </div>
                        @endif
                        @if (!empty($errors->all()))
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <p class="mb-0">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">{{ __('deal::deal.title') }}</label>
                                <input type="text" class="form-control" id="title" name="title" required
                                    value="{{ old('title') }}" autofocus tabindex="1"
                                    placeholder="{{ __('deal::deal.title_placeholder') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="product_id" class="form-label">{{ __('deal::deal.product') }}</label>
                                <select id="product_id" name="product_id" class="form-control select2" data-toggle="select2"
                                    tabindex="2" required>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">{{ __('deal::deal.price') }}</label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text">{!! request()->country()->currency_symbol !!}</span>
                                    <input type="number" class="form-control" id="price" name="price" required
                                        value="{{ old('price', 0) }}" tabindex="3">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="ship_fee" class="form-label">{{ __('deal::deal.ship_fee') }}</label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text">{!! request()->country()->currency_symbol !!}</span>
                                    <input type="number" class="form-control" id="ship_fee" name="ship_fee" required
                                        value="{{ old('ship_fee', 0) }}" tabindex="4">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="activity_date" class="form-label">{{ __('deal::deal.start_at') }}
                                    - {{ __('deal::deal.end_at') }}</label>
                                <input type="text" class="form-control date" id="activity_date" name="activity_date"
                                    data-toggle="date-picker" value="{{ old('activity_date') }}" required tabindex="5">
                            </div>
                        </div> <!-- end row -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-success mt-2" tabindex="6"><i
                                    class="mdi mdi-content-save"></i> {{ __('seller::seller.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
