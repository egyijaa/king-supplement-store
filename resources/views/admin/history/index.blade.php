@extends('layouts.template')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card ">
            <div class="card-header"> 
                <ul class="nav nav-tabs card-header-tabs pull-right"  id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="satu-tab" data-toggle="tab" href="#satu" role="tab" aria-controls="satu" aria-selected="true">Perubahan Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="dua-tab" data-toggle="tab" href="#dua" role="tab" aria-controls="dua" aria-selected="false">Barang Masuk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tiga-tab" data-toggle="tab" href="#tiga" role="tab" aria-controls="tiga" aria-selected="false">Barang Keluar</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" id="empat-tab" data-toggle="tab" href="#empat" role="tab" aria-controls="empat" aria-selected="false">Barang Keluar</a>
                    </li> --}}
                </ul>
            </div>
    
            <div class="card-body">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="satu" role="tabpanel" aria-labelledby="satu-tab">
                        <div class="card-header justify-content-between d-flex d-inline">
                            <h4 class="card-title">Riwayat Perubahan Produk</h4>
                        </div>
                        <div class="ml-3">
                            <button onclick="window.location.reload();" class="btn btn-sm btn-primary">
                                <i class="now-ui-icons loader_refresh"></i> Refresh
                            </button>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.history.index') }}">
                    
                                <div class="row">
                                        <div class="col-4">
                                            <label for="search_product">Cari Kode Produk / Nama :</label>
                                            <input type="text" id="search_product" name="search_product" value="{{Request::get('search_product')}}" class="form-control" autofocus>
                                        </div>
                                        <div class="col-4 mt-3">
                                            <input type="submit" value="Cari" class="btn btn-primary btn-sm text-white">
                                        </div>
                                </div>
                            </form>
                            <form action="{{ route('admin.history.index') }}">
                                <input type="submit" value="Lihat Semua Data" class="btn btn-warning text-white">
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="text-primary">
                                        <tr>
                                            <td class="text-center" style="border: 1px solid black;">
                                                No.
                                            </td>
                                            <td class="text-center" style="border: 1px solid black;">
                                            Produk
                                            </td>
                                            <td class="text-center" style="border: 1px solid black;">
                                            Kondisi
                                            </td>
                                            <td class="text-center" style="border: 1px solid black;">
                                            Kode Produk
                                            </td>
                                            <td class="text-center" style="border: 1px solid black;">
                                            Nama Produk
                                            </td>
                                            <td class="text-center" style="border: 1px solid black;">
                                            Kategori Produk
                                            </td>
                                            <td class="text-center" style="border: 1px solid black;">
                                            Stok
                                            </td>
                                            <td class="text-center" style="border: 1px solid black;">
                                            Harga Modal
                                            </td>
                                            <td class="text-center" style="border: 1px solid black;">
                                            Harga 1
                                            </td>
                                            <td class="text-center" style="border: 1px solid black;">
                                            Harga 3
                                            </td>
                                            <td class="text-center" style="border: 1px solid black;">
                                            Harga 6
                                            </td>
                                            <td class="text-center" style="border: 1px solid black;">
                                            Keterangan
                                            </td>
                                            <td class="text-center" style="border: 1px solid black;">
                                            Tanggal Update
                                            </td>
                                            <td class="text-center" style="border: 1px solid black;">
                                            Oleh
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products as $key => $product)
                                            @if ($product->status == 2)
                                                <tr>
                                                    <td class="text-center">{{ $products->firstItem() + $key }}</td>
                                                    <td>
                                                        @if (isset($product->belong_product->name))
                                                            {{ $product->belong_product->name }}
                                                        @else
                                                            @if (isset($product->update_name))
                                                                {{ $product->update_name }}
                                                            @else
                                                                @if (isset($product->old_name))
                                                                    {{ $product->old_name }}
                                                                @else
                                                                    -
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td class="text-center">{!! isset($product->update_code) ? "<span class='badge badge-success'>".$product->update_code."</span>" : "-" !!}</td>
                                                    <td class="text-center">{!! isset($product->update_name) ? "<span class='badge badge-success'>".$product->update_name."</span>" : "-" !!}</td>
                                                    <td class="text-center">{!! isset($product->belong_category->name) ? "<span class='badge badge-success'>".$product->belong_category->name."</span>" : "-" !!}</td>
                                                    <td class="text-center">{!! isset($product->update_qty) ? "<span class='badge badge-success'>".$product->update_qty."</span>" : "-" !!}</td>
                                                    <td class="text-center">
                                                        @if (isset($product->update_modal))
                                                            <span class='badge badge-success'> @currency($product->update_modal) </span>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($product->update_price))
                                                            <span class='badge badge-success'> @currency($product->update_price) </span>
                                                        @else
                                                            -
                                                        @endif
                                                    
                                                    <td class="text-center">
                                                        @if (isset($product->update_price3))
                                                            <span class='badge badge-success'> @currency($product->update_price3) </span>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($product->update_price6))
                                                            <span class='badge badge-success'> @currency($product->update_price6) </span>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-success">Barang Baru</span>
                                                    </td>
                                                    <td class="text-center">{!! $product->created_at->format('d-M-Y') . " <br> " . $product->created_at->format('H:i') !!}</td>
                                                    <td class="text-center">{{ isset($product->belong_user->name) ? $product->belong_user->name : "-" }}</td>
                                                </tr>
                                            @elseif ($product->status == 3)
                                                <tr>
                                                    <td class="text-center">{{ $products->firstItem() + $key }}</td>
                                                    <td>
                                                        @if (isset($product->belong_product->name))
                                                            {{ $product->belong_product->name }}
                                                        @else
                                                            @if (isset($product->old_name))
                                                                {{ $product->old_name }}
                                                            @else
                                                                @if (isset($product->update_name))
                                                                    {{ $product->update_name }}
                                                                @else
                                                                    -
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td class="text-center">{!! isset($product->old_code) ? "<span class='badge badge-danger'>".$product->old_code."</span>" : "-" !!}</td>
                                                    <td class="text-center">{!! isset($product->old_name) ? "<span class='badge badge-danger'>".$product->old_name."</span>" : "-" !!}</td>
                                                    <td class="text-center">{!! isset($product->belong_old_category->name) ? "<span class='badge badge-danger'>".$product->belong_old_category->name."</span>" : "-" !!}</td>
                                                    <td class="text-center">{!! isset($product->old_qty) ? "<span class='badge badge-danger'>".$product->old_qty."</span>" : "-" !!}</td>
                                                    <td class="text-center">
                                                        @if (isset($product->old_modal))
                                                            <span class='badge badge-danger'> @currency($product->old_modal) </span>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($product->old_price))
                                                            <span class='badge badge-danger'> @currency($product->old_price) </span>
                                                        @else
                                                            -
                                                        @endif
                                                    <td class="text-center">
                                                        @if (isset($product->old_price3))
                                                            <span class='badge badge-danger'> @currency($product->old_price3) </span>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if (isset($product->old_price6))
                                                            <span class='badge badge-danger'> @currency($product->old_price6) </span>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-danger">Barang Dihapus</span>
                                                    </td>
                                                    <td class="text-center">{!! $product->created_at->format('d-M-Y') . " <br> " . $product->created_at->format('H:i') !!}</td>
                                                    <td class="text-center">{{ isset($product->belong_user->name) ? $product->belong_user->name : "-" }}</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td class="text-center" rowspan="2" style="border: 1px solid black;">{{ $products->firstItem() + $key }}</td>
                                                    <td rowspan="2" style="border: 1px solid black;">
                                                        @if (isset($product->belong_product->name))
                                                            {{ $product->belong_product->name }}
                                                        @else
                                                            @if (isset($product->old_name))
                                                                {{ $product->old_name }}
                                                            @else
                                                                @if (isset($product->update_name))
                                                                    {{ $product->update_name }}
                                                                @else
                                                                    -
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td class="text-center table-secondary"  style="border-left: 1px solid black; border-right: 1px solid black;">Sebelum</td>
                                                    <td class="text-center table-secondary"  style="border-left: 1px solid black; border-right: 1px solid black;">{!! isset($product->old_code) ? "<span class='badge badge-dark'>".$product->old_code."</span>" : "-" !!}</td>
                                                    <td class="text-center table-secondary"  style="border-left: 1px solid black; border-right: 1px solid black;">{!! isset($product->update_name) ? "<span class='badge badge-dark'>".$product->old_name."</span>" : "-" !!}</td>
                                                    <td class="text-center table-secondary"  style="border-left: 1px solid black; border-right: 1px solid black;">{!! isset($product->belong_old_category->name) ? "<span class='badge badge-dark'>".$product->belong_old_category->name."</span>" : "-" !!}</td>
                                                    <td class="text-center table-secondary"  style="border-left: 1px solid black; border-right: 1px solid black;">{{ isset($product->old_qty) ? $product->old_qty : "-" }}</td>
                                                    <td class="text-center table-secondary"  style="border-left: 1px solid black; border-right: 1px solid black;">
                                                        @if (isset($product->old_modal))
                                                            <span class='badge badge-dark'> @currency($product->old_modal)</span>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="text-center table-secondary"  style="border-left: 1px solid black; border-right: 1px solid black;">
                                                        @if (isset($product->old_price))
                                                            <span class='badge badge-dark'> @currency($product->old_price)</span>
                                                        @else
                                                            -
                                                        @endif
                                                    <td class="text-center table-secondary"  style="border-left: 1px solid black; border-right: 1px solid black;">
                                                        @if (isset($product->old_price3))
                                                            <span class='badge badge-dark'> @currency($product->old_price3) </span>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="text-center table-secondary"  style="border-left: 1px solid black; border-right: 1px solid black;">
                                                        @if (isset($product->old_price6))
                                                            <span class='badge badge-dark'> @currency($product->old_price6) </span>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="text-center" rowspan="2" style="border: 1px solid black;">
                                                        <span class="badge badge-primary">Update Data</span>
                                                    </td>
                                                    <td class="text-center" rowspan="2" style="border: 1px solid black;">{!! $product->created_at->format('d-M-Y') . " <br> " . $product->created_at->format('H:i') !!}</td>
                                                    <td class="text-center" rowspan="2" style="border: 1px solid black;">{{ isset($product->belong_user->name) ? $product->belong_user->name : "-" }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;" class="text-center table-primary">Setelah</td>
                                                    <td style="border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;" class="text-center table-primary">{!! isset($product->update_code) ? "<span class='badge badge-primary'>".$product->update_code."</span>" : "-" !!}</td>
                                                    <td style="border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;" class="text-center table-primary">{!! isset($product->update_name) ? "<span class='badge badge-primary'>".$product->update_name."</span>" : "-" !!}</td>
                                                    <td style="border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;" class="text-center table-primary">{!! isset($product->belong_category->name) ? "<span class='badge badge-primary'>".$product->belong_category->name."</span>" : "-" !!}</td>
                                                    <td style="border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;" class="text-center table-primary">{{ isset($product->update_qty) ? $product->update_qty : "-" }}</td>
                                                    <td style="border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;" class="text-center table-primary">
                                                        @if (isset($product->update_modal))
                                                            <span class='badge badge-primary'> @currency($product->update_modal) </span>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td style="border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;" class="text-center table-primary">
                                                        @if (isset($product->update_price))
                                                        <span class='badge badge-primary'> @currency($product->update_price) </span>
                                                        @else
                                                            -
                                                        @endif
                                                    <td style="border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;" class="text-center table-primary">
                                                        @if (isset($product->update_price3))
                                                        <span class='badge badge-primary'> @currency($product->update_price3) </span>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td style="border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;" class="text-center table-primary">
                                                        @if (isset($product->update_price6))
                                                        <span class='badge badge-primary'> @currency($product->update_price6) </span>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $products->appends(['barang_masuk_page' => request('barang_masuk_page'), 'barang_keluar_page' => request('barang_keluar_page')])->links() }}
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="dua" role="tabpanel" aria-labelledby="dua-tab">
                        <div class="card-header justify-content-between d-flex d-inline">
                            <h4 class="card-title">Riwayat Barang Masuk</h4>
                        </div>
                        <div class="ml-3">
                            <button onclick="window.location.reload();" class="btn btn-sm btn-primary">
                                <i class="now-ui-icons loader_refresh"></i> Refresh
                            </button>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.history.index') }}">
                    
                                <div class="row">
                                        <div class="col-4">
                                            <label for="search_barang_masuk">Cari Kode Produk / Nama :</label>
                                            <input type="text" id="search_barang_masuk" name="search_barang_masuk" value="{{Request::get('search_barang_masuk')}}" class="form-control" autofocus>
                                        </div>
                                        <div class="col-4 mt-3">
                                            <input type="submit" value="Cari" class="btn btn-primary btn-sm text-white">
                                        </div>
                                </div>
                            </form>
                            <form action="{{ route('admin.history.index') }}">
                                <input type="submit" value="Lihat Semua Data" class="btn btn-warning text-white">
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                <thead class=" text-primary">
                                    <tr>
                                        <td class="text-center">
                                            No.
                                        </td>
                                        <td class="text-center">
                                        Produk
                                        </td>
                                        <td class="text-center">
                                        Info Produk <br> Baru
                                        </td>
                                        <td class="text-center">
                                        Stok Lama
                                        </td>
                                        <td class="text-center">
                                        Barang <br> Masuk
                                        </td>
                                        <td class="text-center">
                                        Stok Total
                                        </td>
                                        <td class="text-center">
                                        Harga Modal
                                        </td>
                                        <td class="text-center">
                                        Harga 1|3|6 <br> Baru
                                        </td>
                                        <td class="text-center">
                                        Nama Penjual
                                        </td>
                                        <td class="text-center">
                                        Tanggal Masuk
                                        </td>
                                        <td class="text-center">
                                        Keterangan
                                        </td>
                                        <td class="text-center">
                                        Tanggal Update
                                        </td>
                                        <td class="text-center">
                                        Oleh
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $i = 1;
                                        ?>
                                    @foreach($barang_masuk as $key => $product)
                                        @if ($product->status == 2)
                                            <tr>
                                                <td class="text-center">{{ $barang_masuk->firstItem() + $key }}</td>
                                                <td>
                                                    @if (isset($product->belong_product->name))
                                                        {{ $product->belong_product->name }}
                                                    @else
                                                        @if (isset($product->update_name))
                                                            {{ $product->update_name }}
                                                        @else
                                                            @if (isset($product->old_name))
                                                                {{ $product->old_name }}
                                                            @else
                                                                -
                                                            @endif
                                                        @endif
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {!! isset($product->update_code) ? "<span class='badge badge-success'>".$product->update_code."</span>" : "-" !!}
                                                    <br>
                                                    {!! isset($product->update_name) ? "<span class='badge badge-success'>".$product->update_name."</span>" : "-" !!}
                                                    <br>
                                                    {!! isset($product->belong_category->name) ? "<span class='badge badge-success'>".$product->belong_category->name."</span>" : "-" !!}
                                                </td>
                                                <td class="text-center">{{ isset($product->old_qty) ? $product->old_qty : "-" }}</td>
                                                <td class="text-center">{{ isset($product->barang_masuk) ? $product->barang_masuk : "-" }}</td>
                                                <td class="text-center">{{ isset($product->update_qty) ? $product->update_qty : "-" }}</td>
                                                <td class="text-center">
                                                    @if (isset($product->update_modal))
                                                        <span class='badge badge-success'> @currency($product->update_modal) </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if (isset($product->update_price))
                                                        <span class='badge badge-success'> @currency($product->update_price) </span>
                                                    @else
                                                        -
                                                    @endif
                                                    <br>
                                                    @if (isset($product->update_price3))
                                                        <span class='badge badge-success'> @currency($product->update_price3) </span>
                                                    @else
                                                        -
                                                    @endif
                                                    <br>
                                                    @if (isset($product->update_price6))
                                                        <span class='badge badge-success'> @currency($product->update_price6) </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-center">{!! isset($product->supplier_name) ? "<span class='badge badge-success'>".$product->supplier_name."</span>" : "-" !!}</td>
                                                <td class="text-center">{{ isset($product->supplier_date) ? date("d-M-Y", strtotime($product->supplier_date)) : "-" }}</td>
                                                <td class="text-center">
                                                    <span class="badge badge-success">Barang Baru</span>
                                                </td>
                                                <td class="text-center">{!! $product->created_at->format('d-M-Y') . " <br> " . $product->created_at->format('H:i') !!}</td>
                                                <td class="text-center">{{ isset($product->belong_user->name) ? $product->belong_user->name : "-" }}</td>
                                            </tr>
                                        @elseif ($product->status == 3) 
                                            <tr>
                                                <td class="text-center">{{ $barang_masuk->firstItem() + $key }}</td>
                                                <td>
                                                    @if (isset($product->belong_product->name))
                                                        {{ $product->belong_product->name }}
                                                    @else
                                                        @if (isset($product->update_name))
                                                            {{ $product->update_name }}
                                                        @else
                                                            @if (isset($product->old_name))
                                                                {{ $product->old_name }}
                                                            @else
                                                                -
                                                            @endif
                                                        @endif
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {!! isset($product->update_code) ? "<span class='badge badge-info'>".$product->update_code."</span>" : "-" !!}
                                                    <br>
                                                    {!! isset($product->update_name) ? "<span class='badge badge-info'>".$product->update_name."</span>" : "-" !!}
                                                    <br>
                                                    {!! isset($product->belong_category->name) ? "<span class='badge badge-info'>".$product->belong_category->name."</span>" : "-" !!}
                                                </td>
                                                <td class="text-center">{{ isset($product->old_qty) ? $product->old_qty : "-" }}</td>
                                                <td class="text-center">{{ isset($product->barang_masuk) ? $product->barang_masuk : "-" }}</td>
                                                <td class="text-center">{{ isset($product->update_qty) ? $product->update_qty : "-" }}</td>
                                                <td class="text-center">
                                                    @if (isset($product->update_modal))
                                                        <span class='badge badge-info'> @currency($product->update_modal) </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if (isset($product->update_price))
                                                        <span class='badge badge-info'> @currency($product->update_price) </span>
                                                    @else
                                                        -
                                                    @endif
                                                    <br>
                                                    @if (isset($product->update_price3))
                                                        <span class='badge badge-info'> @currency($product->update_price3) </span>
                                                    @else
                                                        -
                                                    @endif
                                                    <br>
                                                    @if (isset($product->update_price6))
                                                        <span class='badge badge-info'> @currency($product->update_price6) </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-center">{!! isset($product->supplier_name) ? "<span class='badge badge-info'>".$product->supplier_name."</span>" : "-" !!}</td>
                                                <td class="text-center">{{ isset($product->supplier_date) ? date("d-M-Y", strtotime($product->supplier_date)) : "-" }}</td>
                                                <td class="text-center">
                                                    <span class="badge badge-info">Barang Baru<br>(Pembelian)</span>
                                                </td>
                                                <td class="text-center">{!! $product->created_at->format('d-M-Y') . " <br> " . $product->created_at->format('H:i') !!}</td>
                                                <td class="text-center">{{ isset($product->belong_user->name) ? $product->belong_user->name : "-" }}</td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td class="text-center">{{ $barang_masuk->firstItem() + $key }}</td>
                                                <td>
                                                    @if (isset($product->belong_product->name))
                                                        {{ $product->belong_product->name }}
                                                    @else
                                                        @if (isset($product->update_name))
                                                            {{ $product->update_name }}
                                                        @else
                                                            @if (isset($product->old_name))
                                                                {{ $product->old_name }}
                                                            @else
                                                                -
                                                            @endif
                                                        @endif
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {!! isset($product->update_code) ? "<span class='badge badge-info'>".$product->update_code."</span>" : "-" !!}
                                                    <br>
                                                    {!! isset($product->update_name) ? "<span class='badge badge-info'>".$product->update_name."</span>" : "-" !!}
                                                    <br>
                                                    {!! isset($product->belong_category->name) ? "<span class='badge badge-info'>".$product->belong_category->name."</span>" : "-" !!}
                                                </td>
                                                <td class="text-center">{{ isset($product->old_qty) ? $product->old_qty : "-" }}</td>
                                                <td class="text-center">{{ isset($product->barang_masuk) ? $product->barang_masuk : "-" }}</td>
                                                <td class="text-center">{{ isset($product->update_qty) ? $product->update_qty : "-" }}</td>
                                                <td class="text-center">
                                                    @if (isset($product->update_modal))
                                                        <span class='badge badge-info'> @currency($product->update_modal) </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if (isset($product->update_price))
                                                        <span class='badge badge-info'> @currency($product->update_price) </span>
                                                    @else
                                                        -
                                                    @endif
                                                    <br>
                                                    @if (isset($product->update_price3))
                                                        <span class='badge badge-info'> @currency($product->update_price3) </span>
                                                    @else
                                                        -
                                                    @endif
                                                    <br>
                                                    @if (isset($product->update_price6))
                                                        <span class='badge badge-info'> @currency($product->update_price6) </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-center">{!! isset($product->supplier_name) ? "<span class='badge badge-info'>".$product->supplier_name."</span>" : "-" !!}</td>
                                                <td class="text-center">{{ isset($product->supplier_date) ? date("d-M-Y", strtotime($product->supplier_date)) : "-" }}</td>
                                                <td class="text-center">
                                                    <span class="badge badge-info">Pembelian Baru</span>
                                                </td>
                                                <td class="text-center">{!! $product->created_at->format('d-M-Y') . " <br> " . $product->created_at->format('H:i') !!}</td>
                                                <td class="text-center">{{ isset($product->belong_user->name) ? $product->belong_user->name : "-" }}</td>
                                            </tr>
                                        @endif
                                    
                                    @endforeach
                                </tbody>
                                </table>
                                {{ $barang_masuk->appends(['product_page' => request('product_page'), 'barang_keluar_page' => request('barang_keluar_page')])->links() }}
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tiga" role="tabpanel" aria-labelledby="tiga-tab">
                        <div class="card-header justify-content-between d-flex d-inline">
                            <h4 class="card-title">Riwayat Barang Keluar</h4>
                        </div>
                        <div class="ml-3">
                            <button onclick="window.location.reload();" class="btn btn-sm btn-primary">
                                <i class="now-ui-icons loader_refresh"></i> Refresh
                            </button>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.history.index') }}">
                    
                                <div class="row">
                                        <div class="col-4">
                                            <label for="search_barang_keluar">Cari Kode Produk / Nama :</label>
                                            <input type="text" id="search_barang_keluar" name="search_barang_keluar" value="{{Request::get('search_barang_keluar')}}" class="form-control" autofocus>
                                        </div>
                                        <div class="col-4 mt-3">
                                            <input type="submit" value="Cari" class="btn btn-primary btn-sm text-white">
                                        </div>
                                </div>
                            </form>
                            <form action="{{ route('admin.history.index') }}">
                                <input type="submit" value="Lihat Semua Data" class="btn btn-warning text-white">
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                <thead class=" text-primary">
                                    <tr>
                                        <td class="text-center">
                                            No.
                                        </td>
                                        <td class="text-center">
                                        Produk
                                        </td>
                                        <td class="text-center">
                                        Stok Awal
                                        </td>
                                        <td class="text-center">
                                        Barang Keluar
                                        </td>
                                        <td class="text-center">
                                        Total Stok
                                        </td>
                                        <td class="text-center">
                                        Nama Penjual
                                        </td>
                                        <td class="text-center">
                                        Tanggal Masuk
                                        </td>
                                        <td class="text-center">
                                        Keterangan
                                        </td>
                                        <td class="text-center">
                                        Tanggal Update
                                        </td>
                                        <td class="text-center">
                                        Oleh
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $i = 1;
                                        ?>
                                    @foreach($barang_keluar as $key => $product)
                                    @if ($product->status == 5)
                                        <tr>
                                            <td class="text-center">{{ $barang_keluar->firstItem() + $key }}</td>
                                            <td>
                                                @if (isset($product->belong_product->name))
                                                    {{ $product->belong_product->name }}
                                                @else
                                                    @if (isset($product->old_name))
                                                        {{ $product->old_name }}
                                                    @else
                                                        @if (isset($product->update_name))
                                                            {{ $product->update_name }}
                                                        @else
                                                            -
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center">{!! isset($product->old_qty) ? $product->old_qty : "-" !!}</td>
                                            <td class="text-center">{!! isset($product->barang_keluar) ? $product->barang_keluar : "-" !!}</td>
                                            <td class="text-center">{!! isset($product->update_qty) ? $product->update_qty : "-" !!}</td>
                                            <td class="text-center">{!! isset($product->supplier_name) ? "<span class='badge badge-info'>".$product->supplier_name."</span>" : "-" !!}</td>
                                                <td class="text-center">{{ isset($product->supplier_date) ? $product->created_at->format('d-M-Y') : "-" }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-danger">Barang Dihapus</span>
                                            </td>
                                            <td class="text-center">{!! $product->created_at->format('d-M-Y') . " <br> " . $product->created_at->format('H:i') !!}</td>
                                            <td class="text-center">{{ isset($product->belong_user->name) ? $product->belong_user->name : "-" }}</td>
                                        </tr>
                                    @elseif($product->status == 6)
                                        <tr>
                                            <td class="text-center">{{ $barang_keluar->firstItem() + $key }}</td>
                                            <td>
                                                @if (isset($product->belong_product->name))
                                                    {{ $product->belong_product->name }}
                                                @else
                                                    @if (isset($product->old_name))
                                                        {{ $product->old_name }}
                                                    @else
                                                        @if (isset($product->update_name))
                                                            {{ $product->update_name }}
                                                        @else
                                                            -
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center">{!! isset($product->old_qty) ? $product->old_qty : "-" !!}</td>
                                            <td class="text-center">{!! isset($product->barang_keluar) ? $product->barang_keluar : "-" !!}</td>
                                            <td class="text-center">{!! isset($product->update_qty) ? $product->update_qty : "-" !!}</td>
                                            <td class="text-center">{!! isset($product->supplier_name) ? "<span class='badge badge-info'>".$product->supplier_name."</span>" : "-" !!}</td>
                                                <td class="text-center">{{ isset($product->supplier_date) ? $product->created_at->format('d-M-Y') : "-" }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-warning">Transaksi Pembayaran</span>
                                            </td>
                                            <td class="text-center">{!! $product->created_at->format('d-M-Y') . " <br> " . $product->created_at->format('H:i') !!}</td>
                                            <td class="text-center">{{ isset($product->belong_user->name) ? $product->belong_user->name : "-" }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td class="text-center">{{ $barang_keluar->firstItem() + $key }}</td>
                                            <td>
                                                @if (isset($product->belong_product->name))
                                                    {{ $product->belong_product->name }}
                                                @else
                                                    @if (isset($product->old_name))
                                                        {{ $product->old_name }}
                                                    @else
                                                        @if (isset($product->update_name))
                                                            {{ $product->update_name }}
                                                        @else
                                                            -
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center">{!! isset($product->old_qty) ? $product->old_qty : "-" !!}</td>
                                            <td class="text-center">{!! isset($product->barang_keluar) ? $product->barang_keluar : "-" !!}</td>
                                            <td class="text-center">{!! isset($product->update_qty) ? $product->update_qty : "-" !!}</td>
                                            <td class="text-center">{!! isset($product->supplier_name) ? "<span class='badge badge-info'>".$product->supplier_name."</span>" : "-" !!}</td>
                                                <td class="text-center">{{ isset($product->supplier_date) ? $product->created_at->format('d-M-Y') : "-" }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-dark">Pembatalan Pembelian</span>
                                            </td>
                                            <td class="text-center">{!! $product->created_at->format('d-M-Y') . " <br> " . $product->created_at->format('H:i') !!}</td>
                                            <td class="text-center">{{ isset($product->belong_user->name) ? $product->belong_user->name : "-" }}</td>
                                        </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                                </table>
                                {{ $barang_keluar->appends(['product_page' => request('product_page'), 'barang_masuk_page' => request('barang_masuk_page')])->links() }}
                        </div>
                    </div>
                    {{-- <div class="tab-pane fade" id="empat" role="tabpanel" aria-labelledby="empat-tab">...</div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
  

@endsection
@push('scripts')
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

<script>
    $(document).ready(function () {
        // Cek apakah ada tab yang tersimpan di localStorage
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            $('.nav-link[href="' + activeTab + '"]').tab('show');
        }

        // Simpan tab yang terakhir diklik ke localStorage
        $('.nav-link').on('click', function () {
            var tabId = $(this).attr('href');
            localStorage.setItem('activeTab', tabId);
        });
    });
</script>
@endpush