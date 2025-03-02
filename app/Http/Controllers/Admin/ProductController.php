<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HistoryProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search_product = $request->get('search_product');

        if($search_product){
            $products = Product::where('product_code','like',"%".$search_product."%")->orWhere('name','like',"%".$search_product."%")->paginate(10);
        } else{
            $products = Product::orderBy('id', 'DESC')->paginate(10);
        }
        $categories = Category::all();
        return view('admin.product.index', compact('products', 'categories'));
    }

    public function generateUniqueCode()
    {
        $randomNumber = random_int(10000, 99999);
        $characters = 'ABCDEFGHJKLMNPRSTUVWXYZ';
        $charactersNumber = strlen($characters);
        
        $char = '';
        while (strlen($char) < 1) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $char = $char.$character;
        }

        $code = $char.$randomNumber;

        if (Product::where('product_code', $code)->exists()) {
        $this->generateUniqueCode();
        }
        return $code;
    }

    public function store(Request $request)
    {
        if ($request->get('otomatic') == 'yes') {
            $product = new Product();
            $product->product_code = $this->generateUniqueCode();
        } else {
            $validatedData = $request->validate([
                'product_code' => 'required|unique:product'
            ]);
            $product = new Product();
            $product->product_code = $request->get('product_code');            
        }
        $product->name = $request->get('name');
        $product->quantity = $request->get('quantity');
        $product->modal = $request->get('modal');
        $product->price = $request->get('price');
        $product->price3 = $request->get('price3');
        $product->price6 = $request->get('price6');
        $product->category_id = $request->get('category_id');

        $product->save();

        $history = new HistoryProduct();
        $history->old_code = $product->product_code;
        $history->old_name = $product->name;
        $history->old_qty = 0;
        $history->barang_masuk = $product->quantity;
        $history->update_qty = $product->quantity;
        $history->category_id = $product->category_id;
        $history->product_id = $product->id;
        $history->user_id = auth()->user()->id;
        $history->status = 2;
        $history->save();

        toast('Data produk berhasil ditambah')->autoClose(2000)->hideCloseButton();
        return redirect()->back();
    }
    public function update(Request $request)
    {
        $product = Product::find($request->id);
        $validatedData = $request->validate([
            'product_code' => 'required|unique:product,product_code,' . $request->id,
        ]);

        if ($request->get('quantity') != $product->quantity) {
            $history = new HistoryProduct();
            if ($request->get('quantity') > $product->quantity) {
                $history->barang_masuk = (float) $request->get('quantity') - $product->quantity;
            }
            else {
                $history->barang_keluar = $product->quantity - (float) $request->get('quantity');
            }
            $history->old_code = $product->product_code;
            $history->old_name = $product->name;
            $history->old_qty = $product->quantity;
            $history->update_qty = $request->get('quantity');
            $history->category_id = $product->category_id;
            $history->product_id = $product->id;
            $history->user_id = auth()->user()->id;
            $history->status = 1;
            $history->save();
        }
        

        $product->product_code = $request->get('product_code');
        $product->name = $request->get('name');
        $product->quantity = $request->get('quantity');
        $product->modal = $request->get('modal');
        $product->price = $request->get('price');
        $product->price3 = $request->get('price3');
        $product->price6 = $request->get('price6');
        $product->category_id = $request->get('category_id');
        $product->save();

        toast('Data produk berhasil diubah')->autoClose(2000)->hideCloseButton();
        return redirect()->back();
    }

    public function print(Request $request)
    {
        $barcode = Product::find($request->id);
        $banyak = $request->get('banyak');
        return view('admin.product.print', compact('barcode', 'banyak'));
    }

    public function delete(Request $request)
    {
        $product = Product::find($request->id);
        toast('Data produk berhasil dihapus')->autoClose(2000)->hideCloseButton();

        $history = new HistoryProduct();
        $history->old_code = $product->product_code;
        $history->old_name = $product->name;
        $history->old_qty = $product->quantity;
        $history->barang_keluar = $product->quantity;
        $history->update_qty = 0;
        $history->category_id = $product->category_id;
        $history->product_id = $product->id;
        $history->user_id = auth()->user()->id;
        $history->status = 5;
        $history->save();

        $product->delete();
        return redirect()->back();
    }
}