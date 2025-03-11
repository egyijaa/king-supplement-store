@extends('layouts.template')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card ">
            <div class="card-header justify-content-between d-flex d-inline">
                <h4 class="card-title">Riwayat Produk</h4>
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
                    <table class="table table-bordered" id="dataTableHistory">
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
                            Barang Masuk
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
                        @foreach($products as $key => $product)
                        <tr>
                            {{-- <td class="text-center">{{ $products->firstItem() + $key }}</td> --}}
                            <td class="text-center">{{ $i++ }}</td>
                            <td>
                                @if (isset($product->belong_product->name))
                                    {{ $product->belong_product->name }}
                                    <br>
                                    {{ $product->belong_product->product_code }}
                                @else
                                    @if (isset($product->old_name))
                                        {{ $product->old_name }}
                                        <br>
                                        {{ $product->old_code }}
                                    @else
                                        @if (isset($product->update_name))
                                            {{ $product->update_name }}
                                            <br>
                                            {{ $product->update_code }}
                                        @else
                                            -
                                        @endif
                                    @endif
                                @endif
                            </td>
                            <td class="text-center">{!! isset($product->old_qty) ? $product->old_qty : "-" !!}</td>
                            <td class="text-center">{!! isset($product->barang_masuk) ? "+ ".$product->barang_masuk : "-" !!}</td>
                            <td class="text-center">{!! isset($product->barang_keluar) ? "- ".$product->barang_keluar : "-" !!}</td>
                            <td class="text-center">{!! isset($product->update_qty) ? $product->update_qty : "-" !!}</td>
                            <td class="text-center">{!! isset($product->supplier_name) ? "<span class='badge badge-info'>".$product->supplier_name."</span>" : "-" !!}</td>
                                <td class="text-center">{{ isset($product->supplier_date) ? $product->created_at->format('d-M-Y') : "-" }}</td>
                            <td class="text-center">
                                @if ($product->status == 1)
                                    <span class="badge badge-primary">Update Produk</span>
                                @elseif($product->status == 2)
                                    <span class="badge badge-success">Produk Baru</span>
                                @elseif($product->status == 3)
                                    <span class="badge badge-info">Pembelian Produk Baru</span> 
                                    @if (isset($product->supply_id))
                                    <a href="{{ route('admin.supply.show', $product->supply_id) }}"><i class="fas fa-eye"></i></a>
                                    @endif
                                @elseif($product->status == 4)
                                    <span class="badge badge-secondary">Pembelian Baru</span> 
                                    @if (isset($product->supply_id))
                                    <a href="{{ route('admin.supply.show', $product->supply_id) }}"><i class="fas fa-eye"></i></a>
                                    @endif
                                @elseif($product->status == 5)
                                    <span class="badge badge-danger">Barang Dihapus</span>
                                @elseif($product->status == 6)
                                    <span class="badge badge-warning">Transaksi Pembayaran</span> 
                                    @if (isset($product->trans_id))
                                    <a href="{{ route('admin.report.show', $product->trans_id) }}"><i class="fas fa-eye"></i></a>
                                    @endif
                                @else
                                    <span class="badge badge-danger">Pembatalan Pembelian</span>
                                @endif
                               
                            </td>
                            <td class="text-center">{!! $product->created_at->format('d-M-Y') . " <br> " . $product->created_at->format('H:i') !!}</td>
                            <td class="text-center">{{ isset($product->belong_user->name) ? $product->belong_user->name : "-" }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
            </div>
        </div>
    </div>
</div>
  

@endsection
@push('scripts')
<script>
    $(document).ready(function(){
        $('#dataTableHistory').DataTable({
            searching: false
        });
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

<script>
    // $(document).ready(function () {
    //     // Cek apakah ada tab yang tersimpan di localStorage
    //     var activeTab = localStorage.getItem('activeTab');
    //     if (activeTab) {
    //         $('.nav-link[href="' + activeTab + '"]').tab('show');
    //     }

    //     // Simpan tab yang terakhir diklik ke localStorage
    //     $('.nav-link').on('click', function () {
    //         var tabId = $(this).attr('href');
    //         localStorage.setItem('activeTab', tabId);
    //     });
    // });
</script>
@endpush