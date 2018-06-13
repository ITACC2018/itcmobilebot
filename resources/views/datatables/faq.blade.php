@extends('layouts.admin')

@section('content')
      <!-- Breadcrumbs-->
<ol class="breadcrumb">
<li class="breadcrumb-item">
    <a href="#">FAQ</a>
</li>
<li class="breadcrumb-item active"><a href="/faq/create" class="btn btn-info btn-wkwk" role="button">Create FAQ</a></li>
<li></li>
<li></li>
</ol>

<div class="table-responsive">
    <table class="table table-bordered" id="users-table" width="100%" style="font-size:11px;">
        <thead>
            <tr>
                <!-- <th>Id</th> -->
                <th>category</th>
                <th>sub_category</th>
                <th>sub_sub_category</th>
                <th>question_category</th>
                <th>action</th>
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
        ajax: "{{ url('/faq/datatables') }}",
        columns: [
            // { data: 'id', name: 'id' },
            { data: 'category_name', name: 'category_name' },
            { data: 'sub_category_name', name: 'sub_category_name' },
            { data: 'sub_sub_category_name', name: 'sub_sub_category_name' },
            { data: 'question_category', name: 'question_category' },
            { data: 'action', name: 'action' },
        ]
    });
});
</script>
@endpush