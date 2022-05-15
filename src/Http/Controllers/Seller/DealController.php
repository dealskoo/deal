<?php

namespace Dealskoo\Deal\Http\Controllers\Seller;

use Carbon\Carbon;
use Dealskoo\Deal\Models\Deal;
use Dealskoo\Product\Models\Product;
use Dealskoo\Seller\Http\Controllers\Controller as SellerController;
use Illuminate\Http\Request;

class DealController extends SellerController
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->table($request);
        } else {
            return view('deal::seller.deal.index');
        }
    }

    private function table(Request $request)
    {
        $start = $request->input('start', 0);
        $limit = $request->input('length', 10);
        $keyword = $request->input('search.value');
        $columns = ['id', 'title', 'price', 'ship_fee', 'product_id', 'category_id', 'country_id', 'brand_id', 'platform_id', 'recommend', 'approved_at', 'start_at', 'end_at', 'created_at', 'updated_at'];
        $column = $columns[$request->input('order.0.column', 0)];
        $desc = $request->input('order.0.dir', 'desc');
        $query = Deal::query();
        if ($keyword) {
            $query->where('title', 'like', '%' . $keyword . '%');
            $query->orWhere('slug', 'like', '%' . $keyword . '%');
        }
        $query->orderBy($column, $desc);
        $count = $query->count();
        $deals = $query->skip($start)->take($limit)->get();
        $rows = [];
        foreach ($deals as $deal) {
            $row = [];
            $row[] = $deal->id;
            $row[] = $deal->title;
            $row[] = $deal->price;
            $row[] = $deal->ship_fee;
            $row[] = $deal->product->name;
            $row[] = $deal->category->name;
            $row[] = $deal->country->name;
            $row[] = $deal->brand ? $deal->brand->name : '';
            $row[] = $deal->platform ? $deal->platform->name : '';
            $row[] = $deal->recommend;
            $row[] = $deal->approved_at != null ? Carbon::parse($deal->approved_at)->format('Y-m-d H:i:s') : null;
            $row[] = $deal->start_at != null ? Carbon::parse($deal->start_at)->format('Y-m-d H:i:s') : null;
            $row[] = $deal->end_at != null ? Carbon::parse($deal->end_at)->format('Y-m-d H:i:s') : null;
            $row[] = Carbon::parse($deal->created_at)->format('Y-m-d H:i:s');
            $row[] = Carbon::parse($deal->updated_at)->format('Y-m-d H:i:s');
            $edit_link = '<a href="' . route('seller.deal.edit', $deal) . '" class="action-icon"><i class="mdi mdi-square-edit-outline"></i></a>';
            $destroy_link = '<a href="javascript:void(0);" class="action-icon delete-btn" data-table="deals_table" data-url="' . route('seller.deals.destroy', $deal) . '"> <i class="mdi mdi-delete"></i></a>';
            $row[] = $edit_link . $destroy_link;
            $rows[] = $row;
        }
        return [
            'draw' => $request->draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $rows
        ];
    }

    public function create(Request $request)
    {
        $products = Product::approved()->where('seller_id', $request->user()->id);
        return view('deal::seller.deal.create', ['products' => $products]);
    }

    public function store(Request $request)
    {

    }

    public function edit(Request $request, $id)
    {

    }

    public function update(Request $request, $id)
    {

    }

    public function destroy(Request $request, $id)
    {

    }
}
