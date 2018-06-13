<?php $__env->startSection('content'); ?>
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
<?php $__env->stopSection(); ?>
<?php $__env->startPush('style'); ?>
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
<?php $__env->stopPush(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
$(function() {
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "<?php echo e(url('/faq/datatables')); ?>",
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>