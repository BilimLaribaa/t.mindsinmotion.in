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
                <form id="shortlinkForm" action="{{ url('/shorten') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="link_id">

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Original URL</label>
                            <input type="url" name="url" id="url" class="form-control" required placeholder="https://example.com/my-form">
                        </div>
                    </div>

                    <button class="btn btn-primary" id="formSubmit">Create Short Link</button>
                </form>
            </div>
        </div>

        <!-- Table of All Short Links -->
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
                                <!-- Edit button -->
                                <a href="{{ url('/shorten/'.$link->id.'/edit') }}" class="btn btn-sm btn-warning edit-btn">
                                    Edit
                                </a>

                                <!-- Delete button -->
                                <form action="{{ url('/shorten/'.$link->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Delete this link?')" class="btn btn-sm btn-danger">
                                        Delete
                                    </button>
                                </form>
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

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

    // Handle Edit button click
     $('.edit-btn').click(function(e){
        e.preventDefault();

        let url = $(this).attr('href');
        let id = url.split('/').slice(-2)[0];

        $.get('/shorten/' + id + '/get', function(data){
            $('#link_id').val(data.id);
            $('#url').val(data.original_url);

            $('#shortlinkForm').attr('action', '/shorten/' + data.id + '/update');
            $('#formSubmit').text('Update Short Link');
            $('#formTitle').text('Edit Short Link');
        });
    });

});
</script>
@endsection
