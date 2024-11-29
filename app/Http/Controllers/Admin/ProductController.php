<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryItem;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function searchFilterIndex($search, $filterCategory){
        $index = Product::query();
        
        //Jika input search terisi
        if($search) {
            $index->where('product_code','like',"%".$search."%")
            ->orWhere('name','like',"%".$search."%");
        }
        
        if($filterCategory!= null) {
            $index->whereHas('category', function ($query) use ($filterCategory){
                $query->where('name', 'like', '%'.$filterCategory.'%');
            });
        }
        
        return $index->paginate(10);
        
    }
    public function index(Request $request)
    {
        $search = $request->get('search_product');
        $filterCategory = $request->get('category_search');

        if ($search || $filterCategory) {
            $products = $this->searchFilterIndex($search, $filterCategory); //Memanggil fungsi search dan filter
        } else {
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

        $code = $randomNumber;

        if (Product::where('product_code', $code)->exists()) {
        $this->generateUniqueCode();
        }
        return $code;
    }

    public function store(Request $request)
    {
        if ($request->product_code == null) {
            $product = new Product();
            $product->product_code = $this->generateUniqueCode();
        } else {
            $validatedData = $request->validate([
                'product_code' => 'required|unique:product'
            ]);
            $product = new Product();
            $product->product_code = $request->product_code;            
        }
        $product->name = $request->name;
        if ($request->quantity == '') {
            $quantity = "0";
        } else {
            $quantity = $request->quantity;
        }
        $product->quantity = $quantity;
        $product->price = $request->price;
        $product->capital_price = $request->get('capital_price');
        $product->category_id = $request->category_id;

        $product->save();
        toast('Data produk berhasil ditambah')->autoClose(2000)->hideCloseButton();
        return redirect()->back();
    }

    public function update(Request $request)
    {
        $product = Product::find($request->id);
        $validatedData = $request->validate([
            'product_code' => 'required|unique:product,product_code,' . $request->id,
        ]);

        $product->product_code = $request->get('product_code');
        $product->name = $request->get('name');
        $product->quantity = $request->get('quantity');
        $product->price = $request->get('price');
        $product->capital_price = $request->get('capital_price');
        $product->category_id = $request->get('category_id');
        $product->save();

        toast('Data produk berhasil diubah')->autoClose(2000)->hideCloseButton();
        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $product = Product::find($request->id);
        $product->delete();
        toast('Data produk berhasil dihapus')->autoClose(2000)->hideCloseButton();
        return redirect()->back();
    }
}

