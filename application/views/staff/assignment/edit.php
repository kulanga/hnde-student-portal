<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
    $readonly = false;

    if($assignment->status == 1) {
        $readonly = "readonly='readonly'";
    }

?>

<link rel="stylesheet" href="<?php echo asset_url();?>js/jquery-file-upload/css/jquery.fileupload.css">
<link rel="stylesheet" href="<?php echo asset_url();?>js/jquery-file-upload/css/jquery.fileupload-ui.css">
<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript><link rel="stylesheet" href="<?php echo asset_url();?>js/jquery-file-upload/css/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="<?php echo asset_url();?>js/jquery-file-upload/css/jquery.fileupload-ui-noscript.css"></noscript>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">

            <h2 class="text-muted admin-page-title">
                Update Assignment
            </h2><br/>

            <?php if(validation_errors()) {?>
                <div class="validation-errors">
                    <?php echo validation_errors(); ?>
                </div>
            <?php } ?>

            <form role="form" name="manage_ac_user_form" method="post" action="/staff/assignment/edit/<?=$assignment->id?>" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="course_id">Batch<span class="required">*</span></label>
                    <select class="form-control" name="batch_id" id="batch_id" <?=$readonly;?>>
                        <option value="">-</option>
                        <?php foreach($courses as $course) {?>
                            <option value="<?=$course->id?>" <?=$course->id == set_value('batch_id', $assignment->batch_id) ? 'selected="selected"' : '';?>>
                                <?=$course->name?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="subject_id">Subject<span class="required">*</span></label>
                    <select class="form-control" name="subject_id" id="subject_id" <?=$readonly;?>>
                        <option value="">-</option>
                        <?php foreach($subjects as $subject) {?>
                            <option value="<?=$subject->id?>" <?=$subject->id == set_value('subject_id', $assignment->subject_id) ? 'selected="selected"' : '';?>>
                                <?=$subject->name?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="title">Title<span class="required">*</span></label>
                        <input type="text" class="form-control col-md-3" <?=$readonly;?> id="title" name="title" value="<?=$assignment->title?>"/>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-4">
                        <label for="due_date">Due Date<span class="required">*</span></label>
                        <input type="text" class="form-control col-md-3" id="due_date" name="due_date" value="<?=date('d-m-Y', strtotime($assignment->due_date))?>"/>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description<span class="required">*</span></label>
                    <textarea class="form-control" rows="10" id="description" name="description" <?=$readonly;?>><?=$assignment->description?></textarea>
                </div>

                <div class="form-group">
                    <label for="attachment">Attachments</label>
                    <input type="file" id="attachment"  name="attachment">
                </div>

                <?php if($assignment->status == 0) {?>
                    <div class="form-group">
                        <label for="attachment">Uploaded Attachments</span></label>
                        <ul>
                            <?php foreach($assignment_attachments as $attachment) {?>
                                <li id="file-item-<?=$attachment->id?>">
                                    <a style="width:90%"  href="<?= base_url() . 'uploads/assignments/assignments/' . $attachment->file_name?>"><?=$attachment->original_file_name?></a>

                                    <?php if($assignment->status == 0) {?>
                                        <a style="width:10%;" class="btn-sm btn-warning" href="javascript:remove_file('<?=$assignment->id?>','<?=$attachment->id?>')">X</a>
                                    <?php }?>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary" name="btn_create" value="update">Update</button>&nbsp;&nbsp;
                    <?php if($assignment->status == 0) {?>
                        <button type="submit" class="btn btn-primary" name="btn_create" value="publish">Publish</button>
                    <?php } elseif($assignment->status == 1) {?>
                        <a href="/staff/assignment" class="btn btn-danger" name="btn_create" role="button">&nbsp;Back&nbsp;</a>&nbsp;&nbsp;
                        <span class="bold">*Assignment is in live.</span><br/>
                    <?php } ?>
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

        $('#due_date').datetimepicker({
            format: "dd-mm-yyyy",
            minView : 2,
            autoclose: true,
            daysOfWeekDisabled: [0, 6],
            minDate: moment().toString()
        });
    });

    var remove_file = function(assid, fileid) {
        $.ajax({
            url: '/staff/staff_assignment/remove_attachment',
            type: 'post',
            data: {assignment_id: assid, file_id: fileid},
            success: function(data) {
                if(data == '1') {
                    $('#file-item-' + fileid).remove();
                }
            }
        })
    }
</script>
