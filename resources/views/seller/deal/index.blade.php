@extends('seller::layouts.panel')

@section('title',__('deal::deal.deals_list'))
@section('body')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a
                                href="{{ route('seller.dashboard') }}">{{ __('seller::seller.dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('deal::deal.deals_list') }}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ __('deal::deal.deals_list') }}</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-12">
                            <a href="{{ route('seller.deals.create') }}" class="btn btn-danger mb-2"><i
                                    class="mdi mdi-plus-circle me-2"></i> {{ __('deal::deal.add_deal') }}
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="deals_table" class="table table-centered w-100 dt-responsive nowrap">
                            <thead class="table-light">
                            <tr>
                                <th>{{ __('deal::deal.id') }}</th>
                                <th>{{ __('deal::deal.title') }}</th>
                                <th>{{ __('deal::deal.price') }}</th>
                                <th>{{ __('deal::deal.ship_fee') }}</th>
                                <th>{{ __('deal::deal.product') }}</th>
                                <th>{{ __('deal::deal.category') }}</th>
                                <th>{{ __('deal::deal.country') }}</th>
                                <th>{{ __('deal::deal.brand') }}</th>
                                <th>{{ __('deal::deal.platform') }}</th>
                                <th>{{ __('deal::deal.recommend') }}</th>
                                <th>{{ __('deal::deal.big_discount') }}</th>
                                <th>{{ __('deal::deal.approved_at') }}</th>
                                <th>{{ __('deal::deal.start_at') }}</th>
                                <th>{{ __('deal::deal.end_at') }}</th>
                                <th>{{ __('deal::deal.created_at') }}</th>
                                <th>{{ __('deal::deal.updated_at') }}</th>
                                <th>{{ __('deal::deal.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(function () {
            let table = $('#deals_table').dataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('seller.deals.index') }}",
                "language": language,
                "pageLength": pageLength,
                "columns": [
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': true},
                    {'orderable': false},
                ],
                "order": [[0, "desc"]],
                "drawCallback": function () {
                    $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
                    $('#deals_table tr td:nth-child(17)').addClass('table-action');
                    delete_listener();
                }
            });
            table.on('childRow.dt', function (e, row) {
                delete_listener();
            });
        });
    </script>
@endsection
