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

        $search_barang_masuk = $request->get('search_barang_masuk');

        $search_barang_keluar = $request->get('search_barang_keluar');


        if($search_product){
            $products = HistoryProduct::where('update_code','like',"%".$search_product."%")
                ->orWhere('old_code','like',"%".$search_product."%")
                ->orWhere('update_name','like',"%".$search_product."%")
                ->orWhere('old_name','like',"%".$search_product."%")
                ->whereIn("status", [1])
                ->paginate(10, ['*'], 'product_page');
        } else{
            $products = HistoryProduct::whereIn("status", [1])
                ->orderBy('id', 'DESC')
                ->paginate(10, ['*'], 'product_page');
        }
        
        if($search_barang_masuk){
            $barang_masuk = HistoryProduct::where('old_name','like',"%".$search_barang_masuk."%")
                ->orWhere('old_qty','like',"%".$search_barang_masuk."%")
                ->orWhere('update_qty','like',"%".$search_barang_masuk."%")
                ->orWhere('barang_masuk','like',"%".$search_barang_masuk."%")
                ->whereIn("status", [2, 3, 4])
                ->paginate(10, ['*'], 'barang_masuk_page');
        } else{
            $barang_masuk = HistoryProduct::whereIn("status", [2, 3, 4])
                ->orderBy('id', 'DESC')
                ->paginate(10, ['*'], 'barang_masuk_page');
        }
        
        if($search_barang_keluar){
            $barang_keluar = HistoryProduct::where('old_name','like',"%".$search_barang_keluar."%")
                ->orWhere('old_qty','like',"%".$search_barang_keluar."%")
                ->orWhere('update_qty','like',"%".$search_barang_keluar."%")
                ->orWhere('barang_keluar','like',"%".$search_barang_keluar."%")
                ->whereIn("status", [5,6,7])
                ->paginate(10, ['*'], 'barang_keluar_page');
        } else{
            $barang_keluar = HistoryProduct::whereIn("status", [5,6,7])
                ->orderBy('id', 'DESC')
                ->paginate(10, ['*'], 'barang_keluar_page');
        }
        
        // $categories = Category::all();
        return view('admin.history.index', compact('products', 'barang_keluar', 'barang_masuk'));
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
