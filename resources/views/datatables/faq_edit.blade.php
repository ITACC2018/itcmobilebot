@extends('layouts.admin')

@section('content')
      <!-- Breadcrumbs-->
<ol class="breadcrumb">
<li class="breadcrumb-item">
    <a href="#">FAQ</a>
</li>
<li class="breadcrumb-item active">Edit FAQ</li>
</ol>

<!-- <h1>Editing "{{ $faqs->question_category }}"</h1> -->
<p class="lead">Edit and save this faqs below, or <a href="{{ route('faq') }}">go back to all Faqs.</a></p>
<hr>

@include('partials.alerts.errors')

@if(Session::has('flash_message'))
    <div class="alert alert-success">
        {{ Session::get('flash_message') }}
    </div>
@endif

{!! Form::model($faqs, ['method' => 'PATCH', 'route' => ['faq.update', $faqs->id]]) !!}

<div class="form-group">
    {!! Form::label('title', 'Category Name:', ['class' => 'control-label']) !!}
    {!! Form::select('category_name', $items, $faqs->category_id, ['class' => 'form-control', 'id' => 'category', 'onchange' => 'changeDropdown(this.value, "category", "sub_category", false)']) !!}
</div>

<div class="form-group">
    {!! Form::label('title', 'Sub Category Name:', ['class' => 'control-label']) !!}
    {!! Form::select('sub_category_name', [], null, ['class' => 'form-control', 'id' => 'sub_category', 'disabled' => 'true', 'onchange' => 'changeDropdown(this.value, "sub_category", "sub_sub_category", false)']) !!}
</div>

<div class="form-group">
    {!! Form::label('title', 'Sub Sub Category Name:', ['class' => 'control-label']) !!}
    {!! Form::select('sub_sub_category_name', [], null, ['class' => 'form-control', 'disabled' => 'true', 'id' => 'sub_sub_category']) !!}
</div>

<div class="form-group">
    {!! Form::label('title', 'Question:', ['class' => 'control-label']) !!}
    {!! Form::text('question_category', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('description', 'Answer:', ['class' => 'control-label']) !!}
    {!! Form::textarea('answer_category', null, ['class' => 'form-control editor']) !!}
</div>

{!! Form::submit('Update faqs', ['class' => 'btn btn-primary']) !!}

{!! Form::close() !!}

@stop
@push('style')
<style>
    .table td, .table th {
        padding: .5rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }
</style>
@endpush
@push('scripts')
<script src="{{asset('js/tinymce/jquery.tinymce.min.js')}}"></script>
<script src="{{asset('js/tinymce/tinymce.min.js')}}"></script>
<script>
    var base_url = '{{ url("/") }}';
    var editor_config = {
        path_absolute : "/",
        selector: "textarea.editor",
        plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        //   "searchreplace wordcount visualblocks visualchars code fullscreen",
        //   "insertdatetime media nonbreaking save table contextmenu directionality",
        //   "emoticons template paste textcolor colorpicker textpattern codesample",
        //   "fullpage toc tinymcespellchecker imagetools help"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic strikethrough | alignleft aligncenter alignright alignjustify | ltr rtl | bullist numlist outdent indent removeformat formatselect| link image media | emoticons charmap | code codesample | forecolor backcolor",
        //external_plugins: { "nanospell": "http://YOUR_DOMAIN.COM/js/tinymce/plugins/nanospell/plugin.js" },
        nanospell_server:"php",
        browser_spellcheck: true,
        relative_urls: false,
        remove_script_host: false,
        file_browser_callback : function(field_name, url, type, win) {
        var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
        var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

        var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
        if (type == 'image') {
            cmsURL = cmsURL + "&type=Images";
        } else {
            cmsURL = cmsURL + "&type=Files";
        }

        tinymce.activeEditor.windowManager.open({
            file: '<?= route('elfinder.tinymce4') ?>',// use an absolute path!
            title: 'File manager',
            width: 900,
            height: 450,
            resizable: 'yes'
        }, {
            setUrl: function (url) {
            win.document.getElementById(field_name).value = url;
            }
        });
        }
    };

    $('#category').append('<option value="0">--Silahkan Pilih--</option>');

    @if(!empty($faqs->category_id))
        changeDropdown('{{$faqs->category_id}}', 'category', 'sub_category', true);
    @endif
    @if(!empty($faqs->sub_category_id))
        changeDropdown('{{$faqs->sub_category_id}}', 'sub_category', 'sub_sub_category', true);
    @endif

    function changeDropdown(value, type, elId, status){
        if(type == 'category'){
            $('#sub_category').attr('disabled', true);
            $('#sub_sub_category').attr('disabled', true);
            $('#sub_category').html('');
            $('#sub_category').append('<option value="">--Silahkan Pilih--</option>');
            $('#sub_sub_category').html('');
            $('#sub_sub_category').append('<option value="">--Silahkan Pilih--</option>');
        }
        if(type == 'sub_category'){
            $('#sub_sub_category').attr('disabled', true);
            $('#sub_sub_category').html('');
            $('#sub_sub_category').append('<option value="">--Silahkan Pilih--</option>');
        }
        if(value != ''){
            $.ajax({
                type: "GET",
                url: base_url + '/faq/getcategory/'+value+'/'+type,
                success: function(data){
                        buildDropdown(
                            jQuery.parseJSON(data),
                            $('#'+elId),
                            '--Silahkan Pilih--',
                            elId,
                            status
                        );
                }
            });
        }
    }


    function buildDropdown(result, dropdown, emptyMessage, elId, status) {
        // Remove current options
        dropdown.html('');
        dropdown.attr('disabled', false);
        if(elId == 'sub_category' && status == false) {
            $('#sub_sub_category').html('');
            $('#sub_sub_category').append('<option value="">' + emptyMessage + '</option>');
        }
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
        
        if(status == true){
            @if(!empty($faqs->sub_category_id))
                $('#sub_category').val('{{$faqs->sub_category_id}}');
            @endif
            @if(!empty($faqs->sub_sub_category_id))
                $('#sub_sub_category').val('{{$faqs->sub_sub_category_id}}');
            @endif
        }
    }
    tinymce.init(editor_config);
</script>
<script>
  {!! \File::get(base_path('vendor/barryvdh/laravel-elfinder/resources/assets/js/standalonepopup.min.js')) !!}
</script>
@endpush