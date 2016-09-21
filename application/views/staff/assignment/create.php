<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<link rel="stylesheet" href="<?php echo asset_url();?>js/jquery-file-upload/css/jquery.fileupload.css">
<link rel="stylesheet" href="<?php echo asset_url();?>js/jquery-file-upload/css/jquery.fileupload-ui.css">
<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript><link rel="stylesheet" href="<?php echo asset_url();?>js/jquery-file-upload/css/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="<?php echo asset_url();?>js/jquery-file-upload/css/jquery.fileupload-ui-noscript.css"></noscript>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            
            <h2 class="text-muted admin-page-title">
                Create a Assignment
            </h2><br/>

            <div class="validation-errors">
                <?php echo validation_errors(); ?>
            </div>
            
            <form role="form" name="manage_ac_user_form" method="post" action="/staff/assignment/create" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="course_id">Batch<span class="required">*</span></label>
                    <select class="form-control" name="batch_id" id="batch_id">
                        <option value="">-</option>
                        <?php foreach($courses as $course) {?>
                            <option value="<?=$course->id?>"><?=$course->name . ' ' . date('Y', strtotime($course->start_date))?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="subject_id">Subject<span class="required">*</span></label>
                    <select class="form-control" name="subject_id" id="subject_id">
                        
                    </select>
                </div>

                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="due_date">Due Date<span class="required">*</span></label>
                        <input type="text" class="form-control col-md-3" id="due_date" name="due_date" value=""/>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description<span class="required">*</span></label>
                    <textarea class="form-control" rows="10" id="description" name="description"></textarea>
                </div>

                <div class="form-group">
                    <label for="attachment">Attachments<span class="required">*</span></label>
                    <input type="file" id="attachment"  name="attachment">
                </div>

                <div class="form-group">
                    <button type="reset" class="btn btn-danger">Cancel</button>&nbsp;&nbsp;
                    <button type="submit" class="btn btn-primary" name="btn_create" value="create">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $('#batch_id').on('change', function() {
            var semesters = get_subjects_in_curret_semster($(this).val());
            
            $('#subject_id option').remove();
            
            var options = '<option value="">-</option>';
            $.each(semesters, function(i, v) {
                options += '<option value="'+v.subject_id+'">'+v.name+'</option>';
            });
            $('#subject_id').html(options);
        });

        $('#batch_id').trigger('change');


        $('#due_date').datetimepicker({
            format: "dd-mm-yyyy",
            minView : 2,
            autoclose: true,
            daysOfWeekDisabled: [0, 6],
            minDate: moment().toString()
        });
    })
</script>