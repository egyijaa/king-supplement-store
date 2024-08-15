@extends('layouts.template')
@section('content')

<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header justify-content-between d-flex d-inline">
          <h4 class="card-title"> Data Pembelian</h4>
          <a href="{{ route('admin.supply.addProduct') }}" target="_blank"><i class="btn btn-sm btn-primary shadow-sm">+ Tambah</i></a>
        </div>
        <div class="ml-3 justify-content-between d-flex d-inline mr-2">
            <button onclick="window.location.reload();" class="btn btn-sm btn-primary">
                <i class="now-ui-icons loader_refresh"></i> Refresh
            </button>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.supply.index') }}">
            
                <div class="row">
                        <div class="col-4">
                            <label for="from_date">Dari Tanggal</label>
                            <input type="date" id="from_date" name="from_date" value="{{Request::get('from_date')}}" class="form-control">
                        </div>
                        <div class="col-4">
                            <label for="to_date">Hingga Tanggal</label>
                            <input type="date" id="to_date" name="to_date" value="{{Request::get('to_date')}}" class="form-control">
                        </div>
                        <div class="col-4">
                            <div class="col-4" style="margin-top: 10px;">
                                <input type="submit" value="Cari" class="btn btn-primary text-white">
                            </div>
                        </div>
                </div>
                </form>
                <form action="{{ route('admin.supply.index') }}">
                    <input type="submit" value="Semua Data" class="btn btn-warning text-white">
                </form>
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable">
              <thead>
                <th>
                  No
                </th>
                <th>
                    Kode
                </th>
                <th>
                  Nama Penjual
                </th>
                <th>
                  Tanggal
                </th>
                <th>
                  Total Item
                </th>
                <th>
                    Total Pembelian
                </th>
                <th>
                  Aksi
                </th>
              </thead>
              <tbody>
                  @php
                      $totalBuy = [];
                  @endphp
                  @foreach($supplies as $key => $supply)
                  <tr>
                      <td>{{ $key+1 }}</td>
                      <td>{{ $supply->code }}</td>
                      <td>{{ $supply->supplier_name }}</td>
                      @php
                            $date = \Carbon\Carbon::parse($supply->supply_date)->format('d-m-Y');
                      @endphp
                      <td>{{ $date }}</td>
                      <td>{{ $supply->productSupply()->count() }}</td>
                      <td>@currency($supply->total)</td>
                      <td>
                          <a href="{{ route('admin.supply.show', $supply->id) }}"><i class="fas fa-eye"></i></a>
                          <a href="#" data-target="#delete" data-toggle="modal" data-id="{{ $supply->id }}"><i class="fas fa-trash"></i></a>
                      </td>
                  </tr>
                  @php
                  $totalBuy[] = $supply->total;
                  @endphp
                  @endforeach
                  
                    @php
                    $total = array_sum($totalBuy);
                    @endphp
                    <p>Total Keseluruhan: @currency($total)</p>
                  
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.supply.delete') }}" method="POST">
                @csrf
                @method('delete')
                <input type="hidden" name="id">
                <div class="modal-header">
                    <h5 class="modal-title"><span>Hapus</span> Data Pembelian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus Data Pembelian ini ? Jika dihapus maka otomatis stok akan kembali ke keadaan sebelumnya
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
@push('scripts')
<script>
    $("#edit").on('show.bs.modal', (e) => {
        var id = $(e.relatedTarget).data('id');
        var code = $(e.relatedTarget).data('code');
        var name = $(e.relatedTarget).data('name');
        var quantity = $(e.relatedTarget).data('quantity');
        var price = $(e.relatedTarget).data('price');
        $('#edit').find('input[name="id"]').val(id);
        $('#edit').find('input[name="product_code"]').val(code);
        $('#edit').find('input[name="name"]').val(name);
        $('#edit').find('input[name="quantity"]').val(quantity);
        $('#edit').find('input[name="price"]').val(price);
    });
    
    $('#delete').on('show.bs.modal', (e) => {
        var id = $(e.relatedTarget).data('id');
        console.log(id);
        $('#delete').find('input[name="id"]').val(id);
    });
</script>
@endpush
