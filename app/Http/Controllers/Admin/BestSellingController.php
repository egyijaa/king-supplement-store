<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class BestSellingController extends Controller
{
    public function index(Request $request){
         //barang terlaris
         $fromDate = $request->get('from_date');
         $toDate = $request->get('to_date');

         $transactions = Transaction::count();

        //jika ada data penjualan tampilkan
        if ($transactions > 0) {
            $totalProduct = []; 
            $nameProduct = []; 
            $codeProduct = [];
            $priceProduct = [];
            $category = [];
            if ($fromDate || $toDate) {
                if (empty($fromDate) || empty($toDate)) {
                    $productTransactions = Product::with(['productTransactions', 'category'])
                        ->get()
                        ->sortByDesc(function($product) {
                            // Hitung total quantity setiap produk
                            return $product->productTransactions->sum('quantity');
                        }); 
                    toast('Harap Rentang Tanggal Keduanya diisi!')->autoClose(2000)->hideCloseButton();
                }
                else if ($fromDate > $toDate) {
                    $productTransactions = Product::with(['productTransactions', 'category'])
                        ->get()
                        ->sortByDesc(function($product) {
                            // Hitung total quantity setiap produk
                            return $product->productTransactions->sum('quantity');
                        }); 
                    toast('Tanggal (Hingga) tidak boleh lebih kecil dari tanggal (Dari)!')->autoClose(2000)->hideCloseButton();
                }
                else {
                    $productTransactions = Product::where('price', '>', 0)->with('productTransactions', function ($query) use ($fromDate, $toDate) {
                        if ($fromDate == $toDate) {
                            $query->whereDate('created_at', $fromDate);
                        }
                        else {
                            $toDatePlusOne = Carbon::parse($toDate)->addDay()->toDateString();
                            $query->whereBetween('created_at', [$fromDate, $toDatePlusOne]);
                        }
                    })->get()->sortByDesc(function($product) {
                        // Hitung total quantity setiap produk
                        return $product->productTransactions->sum('quantity');
                    }); 
                }
                foreach($productTransactions as $c){
                    if ($c->productTransactions->sum('quantity') > 0) {
                        array_push($totalProduct, $c->productTransactions->sum('quantity'));
                        array_push($nameProduct, $c->name);
                        array_push($codeProduct, $c->product_code);
                        array_push($priceProduct, $c->price);
                        array_push($category, $c->category->name);
                    }
                }
                $result = [
                    'total' => $totalProduct,
                    'product' => $nameProduct,
                    'code' => $codeProduct,
                    'price' => $priceProduct,
                    'category' => $category
                ];
                $totalData = count($result['code']);
                 
             } 
             else {
                $productTransactions = Product::with(['productTransactions', 'category'])
                ->get()
                ->sortByDesc(function($product) {
                    // Hitung total quantity setiap produk
                    return $product->productTransactions->sum('quantity');
                });
                foreach($productTransactions as $c){
                    if ($c->productTransactions->sum('quantity') > 0) {
                        array_push($totalProduct, $c->productTransactions->sum('quantity'));
                        array_push($nameProduct, $c->name);
                        array_push($codeProduct, $c->product_code);
                        array_push($priceProduct, $c->price);
                        array_push($category, $c->category->name);

                    }
                }
                $result = [
                    'total' => $totalProduct,
                    'product' => $nameProduct,
                    'code' => $codeProduct,
                    'price' => $priceProduct,
                    'category' => $category
                ];
        
                $totalData = count($result['code']);
             }
        } else {
            $result = [];
            $totalData = 0;
        }
        


        return view('admin.best-selling.index', compact('result', 'totalData'));
    }
}
