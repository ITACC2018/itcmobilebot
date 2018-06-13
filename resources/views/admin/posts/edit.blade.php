@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2>
                            Edit Post

                            <a href="{{ url('/posts') }}" class="btn btn-default pull-right">Go Back</a>
                        </h2>
                    </div>

                    <div class="panel-body">
                        {!! Form::model($post, ['method' => 'PUT', 'url' => "/posts/{$post->id}", 'class' => 'form-horizontal', 'role' => 'form', 'enctype' => 'multipart/form-data', 'novalidate']) !!}

                            @include('admin.posts._form')

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-2">
                                    <button type="submit" class="btn btn-primary">
                                        Update
                                    </button>
                                </div>
                            </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
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

    tinymce.init(editor_config);
</script>
<script>
  {!! \File::get(base_path('vendor/barryvdh/laravel-elfinder/resources/assets/js/standalonepopup.min.js')) !!}
</script>
@endpush