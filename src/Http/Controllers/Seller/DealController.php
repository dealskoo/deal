<?php

namespace Dealskoo\Deal\Http\Controllers\Seller;

use Dealskoo\Deal\Models\Deal;
use Dealskoo\Product\Models\Product;
use Dealskoo\Seller\Http\Controllers\Controller as SellerController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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
        $columns = ['id', 'title', 'price', 'ship_fee', 'clicks', 'product_id', 'category_id', 'country_id', 'brand_id', 'platform_id', 'recommend', 'big_discount', 'approved_at', 'start_at', 'end_at', 'created_at', 'updated_at'];
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
            $row[] = Str::words($deal->title, 5, '...') . ' <span class="badge bg-success">' . $deal->off . '% ' . __('Off') . '</span>';
            $row[] = $deal->country->currency_symbol . $deal->price . ' <del>' . $deal->country->currency_symbol . $deal->product->price . '</del>';
            $row[] = $deal->country->currency_symbol . $deal->ship_fee;
            $row[] = $deal->clicks;
            $row[] = $deal->product->name;
            $row[] = $deal->category->name;
            $row[] = $deal->country->name;
            $row[] = $deal->brand ? $deal->brand->name : '';
            $row[] = $deal->platform ? $deal->platform->name : '';
            $row[] = $deal->recommend;
            $row[] = $deal->big_discount;
            $row[] = $deal->approved_at != null ? $deal->approved_at->format('Y-m-d H:i:s') : null;
            $row[] = $deal->start_at != null ? $deal->start_at->format('Y-m-d') : null;
            $row[] = $deal->end_at != null ? $deal->end_at->format('Y-m-d') : null;
            $row[] = $deal->created_at->format('Y-m-d H:i:s');
            $row[] = $deal->updated_at->format('Y-m-d H:i:s');
            $edit_link = '';
            $destroy_link = '';
            if ($deal->approved_at == null) {
                $edit_link = '<a href="' . route('seller.deals.edit', $deal) . '" class="action-icon"><i class="mdi mdi-square-edit-outline"></i></a>';
                $destroy_link = '<a href="javascript:void(0);" class="action-icon delete-btn" data-table="deals_table" data-url="' . route('seller.deals.destroy', $deal) . '"> <i class="mdi mdi-delete"></i></a>';
            }
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
        $products = Product::approved()->where('seller_id', $request->user()->id)->get();
        return view('deal::seller.deal.create', ['products' => $products]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string'],
            'product_id' => ['required', 'exists:products,id'],
            'price' => ['required', 'numeric'],
            'ship_fee' => ['required', 'numeric'],
            'activity_date' => ['required', 'string']
        ]);
        $between = explode(' - ', $request->input('activity_date'));
        $start = date('Y-m-d', strtotime($between[0]));
        $end = date('Y-m-d', strtotime($between[1]));
        $product = Product::approved()->where('seller_id', $request->user()->id)->findOrFail($request->input('product_id'));
        $deal = new Deal(Arr::collapse([$request->only([
            'title', 'product_id', 'price', 'ship_fee'
        ]), $product->only([
            'seller_id', 'category_id', 'country_id', 'brand_id', 'platform_id'
        ]), ['start_at' => $start, 'end_at' => $end]]));
        $deal->save();
        return redirect(route('seller.deals.index'));
    }

    public function edit(Request $request, $id)
    {
        $deal = Deal::where('seller_id', $request->user()->id)->findOrFail($id);
        $products = Product::approved()->where('seller_id', $request->user()->id)->get();
        return view('deal::seller.deal.edit', ['deal' => $deal, 'products' => $products]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => ['required', 'string'],
            'product_id' => ['required', 'exists:products,id'],
            'price' => ['required', 'numeric'],
            'ship_fee' => ['required', 'numeric'],
            'activity_date' => ['required', 'string']
        ]);
        $between = explode(' - ', $request->input('activity_date'));
        $start = date('Y-m-d', strtotime($between[0]));
        $end = date('Y-m-d', strtotime($between[1]));
        $product = Product::approved()->where('seller_id', $request->user()->id)->findOrFail($request->input('product_id'));
        $deal = Deal::where('seller_id', $request->user()->id)->findOrFail($id);
        $deal->fill(Arr::collapse([$request->only([
            'title', 'product_id', 'price', 'ship_fee'
        ]), $product->only([
            'seller_id', 'category_id', 'country_id', 'brand_id', 'platform_id'
        ]), ['start_at' => $start, 'end_at' => $end]]));
        $deal->save();
        return redirect(route('seller.deals.index'));
    }

    public function destroy(Request $request, $id)
    {
        return ['status' => Deal::where('seller_id', $request->user()->id)->where('approved_at', null)->where('id', $id)->delete()];
    }
}
