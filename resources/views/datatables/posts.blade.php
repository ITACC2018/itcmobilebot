@extends('layouts.admin')

@section('content')
      <!-- Breadcrumbs-->
<ol class="breadcrumb">
<li class="breadcrumb-item">
    <a href="#">Blogs</a>
</li>
<li class="breadcrumb-item active"><a href="/posts/create" class="btn btn-info btn-wkwk" role="button">Create Blogs</a></li>
<li></li>
<li></li>
</ol>

<div class="table-responsive">
    <table class="table table-bordered" id="users-table" width="100%" style="font-size:11px;">
        <thead>
            <tr>
                <th>Category</th>
                <th>Title</th>
                <th>Published</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
@stop
@push('style')
<style>
    .table td, .table th {
        padding: .3rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }
    .btn-wkwk{
        padding:0;
    }
    .btn-group-sm>.btn, .btn-sm{
        padding:0px 2px 0px 2px;
    }
</style>
@endpush
@push('scripts')
<script>
$(function() {
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('/posts/datatables') }}",
        columns: [
            // { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'title', name: 'title' },
            { data: 'is_published', name: 'is_published' },
            { data: 'action', name: 'action' },
        ]
    });
});
</script>
@endpush