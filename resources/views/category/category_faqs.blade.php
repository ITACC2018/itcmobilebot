@extends('layouts.admin')

@section('content')
      <!-- Breadcrumbs-->
<ol class="breadcrumb">
<li class="breadcrumb-item">
    <a href="#">FAQ</a>
</li>
<li class="breadcrumb-item active"><a href="#" class="btn btn-info btn-wkwk" role="button">Create Category, Create Sub Category, Create Sub Sub Category</a></li>
<li></li>
<li></li>
</ol>
<div class="row">
    <div class="col-md-12">
        @include('partials.alerts.errors')

        @if(Session::has('flash_message'))
            <div class="alert alert-success">
                {{ Session::get('flash_message') }}
            </div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4 box-shadow">
        <div class="card-body">
        {!! Form::model($faqs, ['method' => 'POST', 'route' => ['categoryfaqs.store']]) !!}
        <fieldset>
                <legend align="left">Create Category:</legend>
            <div class="form-group">
                <input type="hidden" name="type_form" value="form1">
                {!! Form::label('title', 'Category Name:', ['class' => 'control-label']) !!}
                {!! Form::text('category_name', null, ['class' => 'form-control', 'id' => 'category_name_1']) !!}
            </div>
            {!! Form::submit('Create Category', ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
        </fieldset>
        </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-4 box-shadow">
        <div class="card-body">
        {!! Form::model($faqs, ['method' => 'POST', 'route' => ['categoryfaqs.store']]) !!}
            <fieldset>
                <legend align="left">Create Sub Category:</legend>
                <div class="form-group">
                    <input type="hidden" name="type_form" value="form2">
                    {!! Form::label('title', 'Category Name:', ['class' => 'control-label']) !!}
                    {!! Form::select('category_name',  $category, null, ['class' => 'selectpicker form-control', 'id' => 'category_name_2', 'data-live-search' => 'true', 'title'=>' -- PILIH --']) !!} 
                </div>
                <div class="form-group">
                    {!! Form::label('title', 'Sub Category:', ['class' => 'control-label']) !!}
                    {!! Form::text('sub_category_name', null, ['class' => 'form-control']) !!}
                </div>
            {!! Form::submit('Create Sub Category', ['class' => 'btn btn-primary']) !!}
            {!! Form::close() !!}
            </fieldset>
        </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-4 box-shadow">
        <div class="card-body">
        {!! Form::model($faqs, ['method' => 'POST', 'route' => ['categoryfaqs.store']]) !!}
        <fieldset>
            <legend align="left">Create Sub Sub Category:</legend>
            <div class="form-group">
                <input type="hidden" name="type_form" value="form3">
                {!! Form::label('title', 'Category Name:', ['class' => 'control-label']) !!}
                {!! Form::select('category_name', $category, null, ['class' => 'form-control selectpicker', 'id' => 'category_name_3', 'data-live-search' => 'true', 'title'=>' -- PILIH --']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('title', 'Sub Category Name:', ['class' => 'control-label']) !!}
                {!! Form::select('sub_category_name', [], null, ['class' => 'form-control selectpicker', 'id' => 'sub_category_name_3', 'data-live-search' => 'true', 'title'=>' -- PILIH --']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('title', 'Sub Sub Category Name:', ['class' => 'control-label']) !!}
                {!! Form::text('sub_sub_category_name', null, ['class' => 'form-control']) !!}
            </div>
        {!! Form::submit('Create Sub Sub Category', ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
        </fieldset>
        </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered" id="users-table" width="100%" style="font-size:11px;">
                <thead>
                    <tr>
                        <th>category</th>
                        <th>sub_category</th>
                        <th>sub_sub_category</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

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
.bootstrap-select.show>.dropdown-menu>.dropdown-menu {
    display: block;
}
.bootstrap-select > .dropdown-menu > .dropdown-menu li.hidden{
    display:none;
}
.bootstrap-select > .dropdown-menu > .dropdown-menu li a{
    display: block;
    width: 100%;
    padding: 3px 1.5rem;
    clear: both;
    font-weight: 400;
    color: #292b2c;
    text-align: inherit;
    white-space: nowrap;
    background: 0 0;
    border: 0;
}
.card-body {
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 0rem 1.25rem 1.25rem 1.25rem;
}
.btn-default {
    color: #fff;
    background-color: #17a2b8;
    border-color: #17a2b8;
}
.bootstrap-select>.dropdown-toggle.bs-placeholder, .bootstrap-select>.dropdown-toggle.bs-placeholder:active, .bootstrap-select>.dropdown-toggle.bs-placeholder:focus, .bootstrap-select>.dropdown-toggle.bs-placeholder:hover {
    color: #fff;
}
.alert-danger {
    text-align: center;
}
.alert-success {
    text-align: center;
}
.dropdown-menu.open { max-height: none !important; }
</style>
@endpush
@push('scripts')
<script>
$(function() {
    var base_url = '{{ url("/") }}';
    $('.selectpicker').selectpicker({
        style: 'btn-info',
        size: false
    });
    $('#category_name_3').on('change', function(){
        var value = $(this).find("option:selected").val();
        var type = 'category';
        if(value != '' || value != '000'){
            $.ajax({
                type: "GET",
                url: base_url + '/faq/getcategory/'+value+'/'+type,
                success: function(data){
                        buildDropdown(
                            jQuery.parseJSON(data),
                            $('#sub_category_name_3'),
                            '--Silahkan Pilih--'
                        );
                }
            });
        }
    });

    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('/category-faqs/datatables') }}",
        columns: [
            { data: 'category_name', name: 'category_name' },
            { data: 'sub_category_name', name: 'sub_category_name' },
            { data: 'sub_sub_category_name', name: 'sub_sub_category_name' }
        ]
    });


    function buildDropdown(result, dropdown, emptyMessage) {
        // Remove current options
        dropdown.html('');
        //dropdown.attr('disabled', false);
        // Add the empty option with the empty message
        dropdown.append('<option value="">' + emptyMessage + '</option>');
        // Check result isnt empty
        if(result != '')
        {
            // Loop through each of the results and append the option to the dropdown
            $.each(result, function(k, v) {
                dropdown.append('<option value="' + k + '">' + v + '</option>');
            });
        }
        dropdown.selectpicker('destroy');
        dropdown.selectpicker('refresh');
    }
    //$('.selectpicker').selectpicker('destroy');
    //$('.selectpicker').selectpicker('refresh');
});
</script>
@endpush