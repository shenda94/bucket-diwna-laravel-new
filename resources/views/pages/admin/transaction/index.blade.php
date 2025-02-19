@extends('layouts.admin')

@section('title')
Admin Transaction Page
@endsection

@section('content')
<div class="section-content section-dashboard" data-aos="fade-up">
    <div class="container-fluid">
        <div class="dashboard-heading">
            <h2 class="dashboard-title">Transaksi</h2>
            <p class="dashboard-subtitle">
                Ini adalah list transaksi Bucket Diwna
            </p>
            <hr />
        </div>
        <div class="dashboard-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-dashboard">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover scroll-horizontal-vertical w-100" id="crudTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama</th>
                                            <th>Total</th>
                                            <th>Status Transaksi</th>
                                            <th>Resi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('addon-script')
<script>
    var datatable = $('#crudTable').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        ajax: {
            url: '{!! url()->current() !!}',
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'user.name', name: 'user.name' },
            { data: 'total_price', name: 'total_price' },
            { data: 'transaction_status', name: 'transaction_status' },
            { data: 'resi', name: 'resi' },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                width: '20%',
            },
        ],
    });

    $(document).on('change', '.toggle-status', function() {
        const transactionId = $(this).data('id');
        const status = $(this).is(':checked') ? 'SUCCESS' : 'PENDING';

        $.ajax({
            url: `/admin/transaction/toggle-status/${transactionId}`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: status
            },
            success: function(response) {
                if (response.success) {
                    alert('Status updated successfully');
                    datatable.ajax.reload();
                } else {
                    alert('Failed to update status');
                }
            }
        });
    });

    function addResi(transactionId) {
    const resi = $(`#resi-${transactionId}`).val();
    
    if (!resi) {
    alert('Nomor resi tidak boleh kosong!');
    return;
    }
    
    $.ajax({
    url: `/admin/transaction/add-resi/${transactionId}`,
    method: 'POST',
    data: {
    _token: '{{ csrf_token() }}',
    resi: resi,
    },
    success: function(response) {
    console.log(response); // Debugging: Cek response dari server
    if (response.success) {
    alert('Nomor resi berhasil disimpan');
    datatable.ajax.reload();
    } else {
    alert('Gagal menyimpan nomor resi');
    }
    },
    error: function(xhr, status, error) {
    console.log(xhr.responseText); // Debugging: Cek error dari AJAX request
    alert('Terjadi kesalahan. Cek log untuk detail.');
    }
    });
    }
</script>
@endpush