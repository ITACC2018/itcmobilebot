<div class="form-group<?php echo e($errors->has('category_id') ? ' has-error' : ''); ?>">
    <?php echo Form::label('category_id', 'Category', ['class' => 'col-md-2 control-label']); ?>

    <div class="col-md-8">
        <?php echo Form::select('category_id',  $categories, null, ['class' => 'form-control', 'required']); ?>

        <span class="help-block">
            <strong><?php echo e($errors->first('category_id')); ?></strong>
        </span>
    </div>
</div>

<div class="form-group<?php echo e($errors->has('body') ? ' has-error' : ''); ?>">
    <?php echo Form::label('image_url', 'Image Upload', ['class' => 'col-md-2 control-label']); ?>

    <div class="col-md-8">
        <?php echo Form::file('image_url', null, ['class' => 'form-control', 'required']); ?>

        <?php if(!empty($post)): ?>
        <br>
        <img src="<?php echo e(URL::asset('/images/' . $post->image_url . '')); ?>" alt="Image upload" width="400" height="200">
        <?php endif; ?>
        <span class="help-block">
            <strong><?php echo e($errors->first('image_url')); ?></strong>
        </span>
    </div>
</div>


<div class="form-group<?php echo e($errors->has('title') ? ' has-error' : ''); ?>">
    <?php echo Form::label('title', 'Title', ['class' => 'col-md-2 control-label']); ?>

    <div class="col-md-8">
        <?php echo Form::text('title', null, ['class' => 'form-control', 'required', 'autofocus']); ?>

        <span class="help-block">
            <strong><?php echo e($errors->first('title')); ?></strong>
        </span>
    </div>
</div>

<div class="form-group<?php echo e($errors->has('body') ? ' has-error' : ''); ?>">
    <?php echo Form::label('post_body', 'Body', ['class' => 'col-md-2 control-label']); ?>

    <div class="col-md-8">
        <?php echo Form::textarea('post_body', null, ['class' => 'form-control editor', 'required']); ?>

        <span class="help-block">
            <strong><?php echo e($errors->first('post_body')); ?></strong>
        </span>
    </div>
</div>

<div class="form-group<?php echo e($errors->has('is_published') ? ' has-error' : ''); ?>">
    <?php echo Form::label('is_published', 'Published', ['class' => 'col-md-2 control-label']); ?>

    <div class="col-md-8">
        <?php echo e(Form::radio('is_published', 'Yes',true)); ?> Yes
        <?php echo e(Form::radio('is_published', 'No')); ?>  No
        <span class="help-block">
            <strong><?php echo e($errors->first('is_published')); ?></strong>
        </span>
    </div>
</div>

