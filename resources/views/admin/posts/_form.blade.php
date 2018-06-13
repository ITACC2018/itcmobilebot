<div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
    {!! Form::label('category_id', 'Category', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-8">
        {!! Form::select('category_id',  $categories, null, ['class' => 'form-control', 'required']) !!}
        <span class="help-block">
            <strong>{{ $errors->first('category_id') }}</strong>
        </span>
    </div>
</div>

<div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
    {!! Form::label('image_url', 'Image Upload', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-8">
        {!! Form::file('image_url', null, ['class' => 'form-control', 'required']) !!}
        @if(!empty($post))
        <br>
        <img src="{{URL::asset('/images/' . $post->image_url . '')}}" alt="Image upload" width="400" height="200">
        @endif
        <span class="help-block">
            <strong>{{ $errors->first('image_url') }}</strong>
        </span>
    </div>
</div>


<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
    {!! Form::label('title', 'Title', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-8">
        {!! Form::text('title', null, ['class' => 'form-control', 'required', 'autofocus']) !!}
        <span class="help-block">
            <strong>{{ $errors->first('title') }}</strong>
        </span>
    </div>
</div>

<div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
    {!! Form::label('post_body', 'Body', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-8">
        {!! Form::textarea('post_body', null, ['class' => 'form-control editor', 'required']) !!}
        <span class="help-block">
            <strong>{{ $errors->first('post_body') }}</strong>
        </span>
    </div>
</div>

<div class="form-group{{ $errors->has('is_published') ? ' has-error' : '' }}">
    {!! Form::label('is_published', 'Published', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-8">
        {{ Form::radio('is_published', 'Yes',true) }} Yes
        {{ Form::radio('is_published', 'No') }}  No
        <span class="help-block">
            <strong>{{ $errors->first('is_published') }}</strong>
        </span>
    </div>
</div>

