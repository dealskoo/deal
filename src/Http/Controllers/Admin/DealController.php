<?php

namespace Dealskoo\Deal\Http\Controllers\Admin;

use Carbon\Carbon;
use Dealskoo\Admin\Http\Controllers\Controller as AdminController;
use Dealskoo\Admin\Rules\Slug;
use Dealskoo\Deal\Models\Deal;
use Illuminate\Http\Request;

class DealController extends AdminController
{
    public function index(Request $request)
    {
        abort_if(!$request->user()->canDo('deals.index'), 403);
        if ($request->ajax()) {
            return $this->table($request);
        } else {
            return view('deal::admin.deal.index');
        }
    }

    private function table(Request $request)
    {
        $start = $request->input('start', 0);
        $limit = $request->input('length', 10);
        $keyword = $request->input('search.value');
        $columns = ['id', 'title', 'price', 'ship_fee', 'seller_id', 'product_id', 'category_id', 'country_id', 'brand_id', 'platform_id', 'recommend', 'approved_at', 'start_at', 'end_at', 'created_at', 'updated_at'];
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
        $can_view = $request->user()->canDo('deals.show');
        $can_edit = $request->user()->canDo('deals.edit');
        foreach ($deals as $deal) {
            $row = [];
            $row[] = $deal->id;
            $row[] = $deal->title;
            $row[] = $deal->price;
            $row[] = $deal->ship_fee;
            $row[] = $deal->seller->name;
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
            $view_link = '';
            if ($can_view) {
                $view_link = '<a href="' . route('admin.deal.show', $deal) . '" class="action-icon"><i class="mdi mdi-eye"></i></a>';
            }

            $edit_link = '';
            if ($can_edit) {
                $edit_link = '<a href="' . route('admin.deal.edit', $deal) . '" class="action-icon"><i class="mdi mdi-square-edit-outline"></i></a>';
            }
            $row[] = $view_link . $edit_link;
            $rows[] = $row;
        }
        return [
            'draw' => $request->draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $rows
        ];
    }

    public function show(Request $request, $id)
    {
        abort_if(!$request->user()->canDo('deals.show'), 403);
        $deal = Deal::query()->findOrFail($id);
        return view('deal::admin.deal.show', ['deal' => $deal]);
    }

    public function edit(Request $request, $id)
    {
        abort_if(!$request->user()->canDo('deals.edit'), 403);
        $deal = Deal::query()->findOrFail($id);
        return view('deal::admin.deal.edit', ['deal' => $deal]);
    }

    public function update(Request $request, $id)
    {
        abort_if(!$request->user()->canDo('deals.edit'), 403);
        $request->validate([
            'slug' => ['required', new Slug('deals', 'slug', $id, 'id')]
        ]);
        $deal = Deal::query()->findOrFail($id);
        $deal->fill($request->only([
            'slug'
        ]));
        $deal->recommend = $request->boolean('recommend', false);
        $deal->approved_at = $request->boolean('approved', false) ? Carbon::now() : null;
        $deal->save();
        return back()->with('success', __('admin::admin.update_success'));
    }
}
