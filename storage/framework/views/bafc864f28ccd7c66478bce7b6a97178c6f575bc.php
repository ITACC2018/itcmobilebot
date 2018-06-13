<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2>
                            Create Tag

                            <a href="<?php echo e(url('admin/tags')); ?>" class="btn btn-default pull-right">Go Back</a>
                        </h2>
                    </div>

                    <div class="panel-body">
                        <?php echo Form::open(['url' => '/admin/tags', 'class' => 'form-horizontal', 'role' => 'form']); ?>


                            <?php echo $__env->make('admin.tags._form', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-2">
                                    <button type="submit" class="btn btn-primary">
                                        Create
                                    </button>
                                </div>
                            </div>

                        <?php echo Form::close(); ?>

                    </div>
                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>