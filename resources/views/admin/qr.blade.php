@extends('admin.layout')

@section('content')

<main>
    <div class="container-fluid px-4">

        <h1 class="mt-4">QR Code Generator</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>

        <!-- QR Form -->
        <div class="card mb-4">
            <div class="card-header">
                <strong id="formTitle">Create QR Code</strong>
            </div>
            <div class="card-body">

                <form id="qrForm">
                    @csrf
                    <input type="hidden" id="qr_id">

                    <div class="mb-3">
                        <label class="form-label">Text / URL</label>
                        <input type="text" id="data" class="form-control" placeholder="Enter text or URL">
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Size</label>
                            <input type="number" id="size" class="form-control" value="300">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Margin</label>
                            <input type="number" id="margin" class="form-control" value="2">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Type</label>
                            <select id="type" class="form-control">
                                <option value="png">PNG</option>
                                <option value="svg">SVG</option>
                            </select>
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary w-100" onclick="createOrUpdate(event)">
                        Create QR Code
                    </button>

                    <!-- Dynamic Preview -->
                    <div id="previewBox" class="mt-3" style="display:none;">
                        <h6>QR Preview:</h6>
                        <div id="qrPreview"></div>
                    </div>

                </form>

            </div>
        </div>

        <!-- QR List -->
        <div class="card mb-4">
            <div class="card-header"><strong>All QR Codes</strong></div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Preview</th>
                            <th>Data</th>
                            <th>Size</th>
                            <th>Margin</th>
                            <th>Type</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($qrList as $qr)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{ asset('qr/'.$qr->file) }}" width="80">
                            </td>
                            <td style="max-width:250px; word-wrap:break-word;">{{ $qr->data }}</td>
                            <td>{{ $qr->size }}</td>
                            <td>{{ $qr->margin }}</td>
                            <td>{{ strtoupper($qr->type) }}</td>
                            <td>{{ $qr->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ asset('qr/'.$qr->file) }}" download class="btn btn-sm btn-info">Download</a>
                                <button class="btn btn-sm btn-warning" onclick="editQr({{ $qr->id }})">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteQr({{ $qr->id }})">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

@endsection

@section('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>

<script>
    $(document).ready(function() {
    const params = new URLSearchParams(window.location.search);
    const urlParam = params.get('url');

    if (urlParam) {
        $('#data').val(urlParam);
        updatePreview(); // Generate preview automatically
    }
});

// Live Preview
function updatePreview() {
    let text = $('#data').val();
    let size = parseInt($('#size').val());
    let margin = parseInt($('#margin').val());
    let type = $('#type').val();

    if(!text) {
        $('#previewBox').hide();
        return;
    }

    $('#qrPreview').html('');

    if(type === 'png') {
        QRCode.toDataURL(text, { width: size, margin: margin }, function(err, url) {
            if(!err) {
                let img = $('<img>').attr('src', url).attr('width', size/2);
                $('#qrPreview').html(img);
                $('#previewBox').show();
            }
        });
    } else if(type === 'svg') {
        QRCode.toString(text, { type: 'svg', width: size, margin: margin }, function(err, svg) {
            if(!err) {
                $('#qrPreview').html(svg);
                $('#previewBox').show();
            }
        });
    }
}

// Trigger preview on input change
$('#data, #size, #margin, #type').on('input change', updatePreview);

// Create / Update QR
function createOrUpdate(e) {
    e.preventDefault();

    let id = $('#qr_id').val();
    let ajaxUrl = id ? '/admin/qr/' + id + '/update' : '/admin/qr/create';
    let type = $('#type').val();

    if(type === 'png') {
        QRCode.toDataURL($('#data').val(), { width: parseInt($('#size').val()), margin: parseInt($('#margin').val()) }, function(err, base64){
            sendAjax(base64);
        });
    } else {
        QRCode.toString($('#data').val(), { type: 'svg', width: parseInt($('#size').val()), margin: parseInt($('#margin').val()) }, function(err, svg){
            let base64 = 'data:image/svg+xml;base64,' + btoa(svg);
            sendAjax(base64);
        });
    }

    function sendAjax(base64) {
        $.ajax({
            url: ajaxUrl,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                data: $('#data').val(),
                size: $('#size').val(),
                margin: $('#margin').val(),
                type: type,
                base64: base64
            },
            success: function(res){
                Swal.fire({ icon: 'success', title: 'Success', text: res.message });
                setTimeout(()=>location.reload(), 800);
            }
        });
    }
}

// Edit QR
function editQr(id) {
    $.get('/admin/qr/' + id + '/get', function(data){
        $('#qr_id').val(data.id);
        $('#data').val(data.data);
        $('#size').val(data.size);
        $('#margin').val(data.margin);
        $('#type').val(data.type);

        $('#formTitle').text('Edit QR Code');
        $('#formSubmit').text('Update QR Code');
        updatePreview();
    });
}

// Delete QR
function deleteQr(id) {
    Swal.fire({
        title: "Delete this QR?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Delete"
    }).then((res) => {
        if(res.isConfirmed){
            $.ajax({
                url: '/admin/qr/' + id,
                method: "DELETE",
                data: { _token: "{{ csrf_token() }}" },
                success: function(res){
                    Swal.fire({ icon: 'success', title: 'Deleted', text: res.message });
                    setTimeout(()=>location.reload(),800);
                }
            });
        }
    });
}
</script>

@endsection
