@extends('admin.layout')

@section('content')
<main>
    <div class="container-fluid px-4">
        
        <h1 class="mt-4">Link Shortener</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>

        <!-- Add/Edit Short Link Form -->
        <div class="card mb-4">
            <div class="card-header">
                <strong id="formTitle">Create Short Link</strong>
            </div>

            <div class="card-body">

                <form id="shortlinkForm" onsubmit="createOrUpdate(event)">
                    @csrf
                    <input type="hidden" id="link_id">

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Original URL</label>
                            <input type="url" id="url" class="form-control" required placeholder="https://example.com">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" id="formSubmit">
                        Create Short Link
                    </button>
                </form>

            </div>
        </div>

        <!-- List All Links -->
        <div class="card mb-4">
            <div class="card-header">
                <strong>All Shortened Links</strong>
            </div>

            <div class="card-body">

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Short URL</th>
                            <th>Original URL</th>
                            <th>Clicks</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($links as $link)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <a href="{{ url('/l/'.$link->code) }}" target="_blank">
                                    {{ url('/l/'.$link->code) }}
                                </a>
                            </td>

                            <td style="max-width:300px; word-wrap:break-word;">
                                {{ $link->original_url }}
                            </td>

                            <td>{{ $link->clicks }}</td>

                            <td>{{ $link->created_at->format('d M Y') }}</td>

                            <td>
                                <button class="btn btn-sm btn-warning"
                                    onclick="editLink({{ $link->id }})">
                                    Edit
                                </button>

                                <button class="btn btn-sm btn-danger"
                                    onclick="deleteLink({{ $link->id }})">
                                    Delete
                                </button>
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

<script>

// ✔ CREATE or UPDATE Short Link
function createOrUpdate(e) {
    e.preventDefault();

    let id = $('#link_id').val();
    let url = $('#url').val();

    let ajaxUrl = id 
        ? '/admin/shorten/' + id + '/update'
        : '/admin/shorten';

    $.ajax({
        url: ajaxUrl,
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            url: url
        },
        success: function(res) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: res.message
            });

            setTimeout(() => location.reload(), 1000);
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON?.message || 'Something went wrong!'
            });
        }
    });
}


// ✔ Load Data for Editing
function editLink(id) {

    $.get('/admin/shorten/' + id + '/get', function(data){

        $('#link_id').val(data.id);
        $('#url').val(data.original_url);
        $('#formSubmit').text('Update Short Link');
        $('#formTitle').text('Edit Short Link');

    }).fail(function(){
        Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load link!' });
    });

}


// ✔ Delete Link
function deleteLink(id) {

    Swal.fire({
        title: "Delete this link?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Delete"
    }).then((res) => {
        if (res.isConfirmed) {

            $.ajax({
                url: '/admin/shorten/' + id,
                method: "DELETE",
                data: { _token: "{{ csrf_token() }}" },

                success: function(res){
                    Swal.fire({ icon: 'success', title: 'Deleted', text: res.message });
                    setTimeout(() => location.reload(), 1000);
                },

                error: function(){
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to delete!' });
                }
            });

        }
    });

}

</script>
@endsection
