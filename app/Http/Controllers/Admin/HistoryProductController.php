<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\HistoryProduct;
use App\Http\Controllers\Controller;

class HistoryProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $search_product = $request->get('search_product');


        if($search_product){
            $products = HistoryProduct::where('category_id','like',"%".$search_product."%")
                ->orWhereHas('belong_product', function ($query) use ($search_product){
                    $query->where('name', 'like', '%'.$search_product.'%')->orWhere('product_code','like',"%".$search_product."%");
                })
                ->paginate(10);
        } else{
            $products = HistoryProduct::orderBy('id', 'DESC')
                ->paginate(10);
        }
        
        // $categories = Category::all();
        return view('admin.history.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(HistoryProduct $historyProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HistoryProduct $historyProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HistoryProduct $historyProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HistoryProduct $historyProduct)
    {
        //
    }
}
