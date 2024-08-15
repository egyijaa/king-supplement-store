<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSupply;
use App\Models\Supply;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SupplyController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        if ($fromDate) {
            $supplies = Supply::whereRaw(
                "(supply_date >= ? AND supply_date <= ?)", 
                [
                   $fromDate, 
                   $toDate
                ]
              )->get();
        } else {
            $supplies = Supply::orderBy('id', 'DESC')->get();
        }
        $categories = Category::all();
        $products = Product::orderBy('id', 'DESC')->get();
        return view('admin.supply.index', compact('supplies','products', 'categories'));
    }

    public function addProduct(Request $request)
    {
        $products = Product::orderBy('id', 'DESC')->get();
        $categories = Category::all();

        $productSupply = ProductSupply::with('product')->where('user_id', auth()->user()->id)->where('status', 0)->orderBy('id', 'DESC')->get();
        $totalPrice = ProductSupply::with('product')->where('user_id', auth()->user()->id)->where('status', 0)->sum('total_price');
        $totalItem = ProductSupply::with('product')->where('user_id', auth()->user()->id)->where('status', 0)->count();
        return view('admin.supply.store', compact('products', 'productSupply',  'categories', 'totalPrice','totalItem'));
    }

    public function generateUniqueCode()
    {
        $characters = '0123456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $charactersNumber = strlen($characters);
        $codeLength = 10;

        $code = '';

        while (strlen($code) < $codeLength) {
        $position = rand(0, $charactersNumber - 1);
        $character = $characters[$position];
        $code = $code.$character;
        }

        if (Supply::where('code', $code)->exists()) {
        $this->generateUniqueCode();
        }
        return $code;
    }

    public function generateUniqueCodeProduct()
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
        $this->generateUniqueCodeProduct();
        }
        return $code;
    }

    public function storeProduct(Request $request)
    {
        $productSupply = new ProductSupply();
        $productSupply->user_id = auth()->user()->id;
        $productSupply->supply_id = null;
        $productSupply->product_id = $request->product_id;
        $productSupply->quantity = $request->quantity;
        $productSupply->status = 0;
        $productSupply->price = $request->total_price / $request->quantity;
        $productSupply->total_price = $request->total_price;
        $productSupply->save();

        toast('Berhasil menambah data pembelian')->autoClose(2000)->hideCloseButton();
        return redirect()->back(); 
    }

    public function storeNewProduct(Request $request)
    {
        DB::beginTransaction();
        try{
            //masukkan dulu data ke tabel produk
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
            $quantity = "0";
            $product->quantity = $quantity;
            $product->price = $request->price;
            $product->capital_price = $request->capital_price;
            $product->category_id = $request->category_id;
            $product->save();

            //lanjut masukkan data ke product supply
            $productSupply = new ProductSupply();
            $productSupply->user_id = auth()->user()->id;
            $productSupply->supply_id = null;
            $productSupply->product_id = $product->id;
            $productSupply->quantity = $request->quantity;
            $productSupply->status = 0;

            //atur disini tanpa ppn
            $productSupply->price = $request->capital_price;
            $productSupply->total_price = $request->capital_price * $request->quantity;
            
            $productSupply->save();
            DB::commit();
            toast('Berhasil menambah data Produk Baru')->autoClose(2000)->hideCloseButton();
            return redirect()->back(); 
        } catch (\Exception $e) {
            DB::rollback();
            toast('Gagal menambah data')->autoClose(2000)->hideCloseButton();
            return redirect()->back();
        }
    }

    public function deleteProduct($id)
    {
        $productSupply = ProductSupply::find($id);

        $productSupply->delete();
        toast('Data berhasil dihapus')->autoClose(2000)->hideCloseButton();
        return redirect()->back();
    }

    public function storeSupply (Request $request)
    {
        DB::beginTransaction();

        if ($request->supplier_name == null) {
            $supplier_name = '-';
        } else {
            $supplier_name = $request->supplier_name;
        }

        if ($request->supply_date == null) {
            $supply_date = date('Y-m-d');
        } else {
            $supply_date = $request->supply_date;
        }

        try {
            $supply = Supply::create([
                'user_id' => auth()->user()->id,
                'code' => $this->generateUniqueCode(),
                'supplier_name' => $supplier_name,
                'supply_date' => $supply_date,
                'total' => ''
                
            ]);
            $total = [];
            $productSupply = ProductSupply::where('user_id', auth()->user()->id)->where('status', 0);
            foreach ($productSupply->get() as $ps) {
                //tambahkan stok
                $moreProduct = Product::where('product_code', $ps->product->product_code)->first();
                $moreProduct->quantity = $moreProduct->quantity + $ps->quantity;
                //update harga modal atau capital price
                $moreProduct->capital_price = round($ps->price);
                $moreProduct->save();
                $total[] = $ps->total_price;
            }

            //ganti status produk supply menjadi 1
            $productSupply->update([
                'supply_id' => $supply->id,
                'status' => '1',
            ]);

            //tambahkan total di Supply 
            $totalFinal = array_sum($total);
            $s = Supply::find($supply->id);
            $s->total = $totalFinal;
            $s->save();
            DB::commit();
            toast('Data Pembelian berhasil ditambahkan')->autoClose(2000)->hideCloseButton();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            toast('Gagal menambah data pembelian')->autoClose(2000)->hideCloseButton();
            return redirect()->back();
        }
    }
       
    public function delete(Request $request)
    {
        $supply = Supply::find($request->id);
        $productSupplies = ProductSupply::where('supply_id', $request->id)->pluck('id');
        
        for($i = 0; $i < count($productSupplies); $i++){
            $getQuantity = ProductSupply::where('id', $productSupplies[$i])->first()->quantity;
            $getProductId = ProductSupply::where('id', $productSupplies[$i])->first()->product_id;
            $produk = Product::find($getProductId);
            $quantity = $produk->quantity - $getQuantity;
            $produk->update(['quantity' => $quantity]);
        }
        $supply->delete();
        toast('Data Pembelian berhasil dihapus')->autoClose(2000)->hideCloseButton();
        return redirect()->back()->with('success','Berhasil menghapus data pembelian');
    }
    
    public function show(Request $request)
    {
        $supply = Supply::find($request->id);
        $product_supplies = ProductSupply::where('supply_id', $supply->id)->get();
        return view('admin.supply.show', compact('supply','product_supplies'));
    }

    public function print(Request $request)
    {
        $supply = Supply::find($request->id);
        $product_supplies = ProductSupply::where('supply_id', $supply->id)->get();

        foreach($product_supplies as $key => $product) {
        $name[] = $product->product->name;
        $code[] = $product->product->product_code;
        $price[] = $product->product->price;
        $price3[] = $product->product->price3;
        $price6[] = $product->product->price6;
        $print[] = $product->quantity;
        }
        $result = [
            'name' => $name,
            'code' => $code,
            'price' => $price,
            'price3' => $price3,
            'price6' => $price6,
            'print' => $print
        ];

        $data = [];
        for ($i = 0; $i < count($name); $i++) {
        $data[] = [
            'name' => $name[$i],
            'code' => $code[$i],
            'price' => $price[$i],
            'price3' => $price3[$i],
            'price6' => $price6[$i],
            'code' => $code[$i],
            ];
        $quantity[] = [
            'qty' => (int)$print[$i],
        ];
        }
        
        for($i=0; $i<count($quantity);$i++) {
            // echo $quantity[$i]['qty'];
            for($j = 1; $j<= $quantity[$i]['qty']; $j++){
                $jumlah[] = [ 
                    'nama' => $data[$i]['name'],
                    'kode' => $data[$i]['code'],
                    'harga' => $data[$i]['price'],
                    'harga3' => $data[$i]['price3'],
                    'harga6' => $data[$i]['price6'],
                    'kuanKe' => $j,
                ];
            }
        };

        return view('admin.supply.print1', compact('supply', 'product_supplies', 'jumlah', 'quantity'));
    }

    public function storeNew(Request $request)
    {

        DB::beginTransaction();

        if ($request->supplier_name == null) {
            $supplier_name = '-';
        } else {
            $supplier_name = $request->supplier_name;
        }

        if ($request->supply_date == null) {
            $supply_date = date('Y-m-d');
        } else {
            $supply_date = $request->supply_date;
        }

        try {
            $supply = Supply::create([
                'user_id' => auth()->user()->id,
                'code' => $this->generateUniqueCode(),
                'supplier_name' => $supplier_name,
                'supply_date' => $supply_date,
                'total' => ''
                
            ]);
            $total = [];
            for($i = 0; $i < count($request->product_name); $i++){
                $product = Product::create([
                    'product_code' => $this->generateUniqueCodeProduct(),
                    'name' => $request->product_name[$i],
                    'quantity' => $request->quantity[$i],
                    'slug' => Str::slug($request->product_name[$i]),
                    'price' => $request->price1[$i],
                    'category_id' => $request->category_id[$i],
                    'capital_price' => $request->price[$i],
                ]);
                ProductSupply::create([
                    'supply_id' => $supply->id,
                    'product_id' => $product->id,
                    'quantity' => $request->quantity[$i],
                    'price' => $request->price[$i],
                    'status' => '1',
                    'user_id' => auth()->user()->id,
                    'total_price' => $request->quantity[$i]*$request->price[$i],
                    'is_ppn' => 0,
                ]);
                $total[] = $request->quantity[$i] * $request->price[$i];
            } 
            //coba tambahkan total di Supply
            $totalFinal = array_sum($total);
            $s = Supply::find($supply->id);
            $s->total = $totalFinal;
            $s->save();
            DB::commit();
            toast('Data Pembelian berhasil ditambahkan')->autoClose(2000)->hideCloseButton();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            toast('Gagal menambah data pembelian')->autoClose(2000)->hideCloseButton();
            return redirect()->back();
        }
       
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            //update product
            $product = Product::find($request->idProduct);
            $product->name = $request->get('nameProduct');
            $product->quantity = $request->get('qtyProduct');
            $product->price = $request->get('priceProduct');
            $product->update();

            //update product supply
            $productSupply = ProductSupply::find($request->idProductSupply);
            $productSupply->quantity = $request->qtyProductSupply;
            $productSupply->price = $request->totalPriceSupply / $request->qtyProductSupply;

            $productSupply->total_price = $request->totalPriceSupply;
            $productSupply->update();

            DB::commit();
            toast('Data produk berhasil diubah')->autoClose(2000)->hideCloseButton();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            toast('Gagal mengubah data')->autoClose(2000)->hideCloseButton();
            return redirect()->back();
        }
    }
}
