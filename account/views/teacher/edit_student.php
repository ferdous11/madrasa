<?php include __DIR__ .'/../topheader.php'; ?>
<?php include __DIR__ .'/../menu.php'; ?>
<section id="main-content">
    <section class="wrapper">
        <div style="text-align: center;" class="row col-sm-12">
        <a style="float: left;" class="btn btn-danger" href="<?=$baseurl?>teacher/students_list/<?=$student->class_id;?>/<?=$student->section_id;?>/#tr-<?=$student->roll;?>"><i class="fa fa-arrow-left"></i> পূর্ববর্তী পেজে ফিরে যান </a>  
            <img src="<?php echo $baseurl."assets/image/students/".$student->image;?>" alt="">
            <h1 class="col-md-5 col-sm-11 col-md-push-3"> তালেবে এলেম তথ্য হালনাগাত </h1>
        </div>
        <div class="row">
            <form id="form1"  method="post" action="<?php echo site_url('teacher/saveeditstudent'); ?>"  id="sform" enctype="multipart/form-data">
                <div class="col-sm-10 col-md-7 col-md-push-3 col-sm-push-1">
                    <div class="form-group">
                        <label for="class_id">শ্রেণী</label>
                        <select class="form-control selectpicker" id="class_id" placeholder="Select Class" tabindex="1"   data-live-search="true" name="class_id" onchange="getsection(this.value)">
                            <option></option>
                            <?php foreach ($classes as $class):?>
                            <option <?php echo ($class->id==$student->class_id)?"selected":"";?> value="<?php echo $class->id?>"><?php echo $class->class_name?></option>

                            <?php endforeach;?>
                        </select>
                        <label id="rollHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('class_id'); ?></label>
                    </div> 
                </div>
                <div class="col-sm-10 col-md-7 col-md-push-3 col-sm-push-1">
                    <div class="form-group" <?php echo $student->section_id > 0 ?'':'hidden=""';?> id="sectionflag">
                        <label for="student_section">উপ-শ্রেণী</label>
                        <select class="form-control selectpicker" id="student_section" placeholder="Select Section" tabindex="2"   data-live-search="true" name="section_id" >
                            <option></option>
                            <?php foreach ($sections as $section):?>
                            <option <?php echo ($section->id==$student->section_id)?"selected":"";?> value="<?php echo $section->id?>"><?php echo ($section->section_name);?></option>
                            <?php endforeach;?>
                        </select>
                    </div>   
                </div>
                <div class="col-sm-10 col-md-7 col-md-push-3 col-sm-push-1">
                    <div class="form-group" id="sectionflag">
                        <label for="student_name">নাম</label>
                        <input class="form-control" type="text" value="<?php echo $student->name; ?>" tabindex="3" name="student_name" id="student_name">
                        <label id="rollHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('student_name'); ?></label>
                        
                    </div> 
                </div> 
                <div class="col-sm-10 col-md-7 col-md-push-3 col-sm-push-1">
                    <div class="form-group" id="sectionflag">
                        <label for="call_name">ডাক নাম</label>
                        <input class="form-control" type="text" value="<?php echo $student->call_name; ?>" tabindex="4" name="call_name" id="call_name">
                        <label id="rollHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('call_name'); ?></label>
                        
                    </div> 
                </div>
                <div class="col-sm-10 col-md-7 col-md-push-3 col-sm-push-1">
                    <div class="form-group" id="sectionflag">
                        <label for="father_name">বাবার নাম</label>
                        <input class="form-control" type="text" value="<?php echo $student->father_name; ?>" tabindex="5" name="father_name" id="father_name">
                        <label id="rollHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('father_name'); ?></label>
                        
                    </div> 
                </div>
                <div class="col-sm-10 col-md-7 col-md-push-3 col-sm-push-1">
                    <div class="form-group" id="sectionflag">
                        <label for="mother_name">মায়ের নাম</label>
                        <input class="form-control" type="text" value="<?php echo $student->mother_name; ?>" tabindex="6" name="mother_name" id="mother_name">
                        <label id="rollHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('mother_name'); ?></label>
                        
                    </div> 
                </div>
                <div class="col-sm-10 col-md-7 col-md-push-3 col-sm-push-1">
                    <div class="form-group" id="sectionflag">
                        <label for="guardian_name">অভিভাবকের নাম</label>
                        <input class="form-control" type="text" value="<?php echo $student->guardian_name; ?>" tabindex="7" name="guardian_name" id="guardian_name">
                        <label id="rollHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('guardian_name'); ?></label>
                        
                    </div> 
                </div>
                <div class="col-sm-10 col-md-7 col-md-push-3 col-sm-push-1">
                    <div class="form-group" id="sectionflag">
                        <label for="guardian_mobile">অভিভাবকের মোবাইল নং</label>
                        <input class="form-control" type="text" value="<?php echo $student->guardian_mobile; ?>" tabindex="8" name="guardian_mobile" id="guardian_mobile">
                        <label  id="rollHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('guardian_mobile'); ?></label>
                    </div>    
                </div> 
                
                <div class="col-sm-10 col-md-7 col-md-push-3 col-sm-push-1">
                    <div class="form-group" id="sectionflag">
                        <label for="student_roll">রোল</label>
                        <input aria-describedby="rolllHelp" class="form-control" tabindex="9" value="<?php echo $student->roll; ?>" readonly type="number" step="1" name="student_roll" id="student_roll">
                        <label id="rollHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('student_roll'); ?></label>
                    </div> 
                </div>

                <div class="col-sm-10 col-md-7 col-md-push-3 col-sm-push-1">
                    <div class="form-group" id="sectionflag">
                        <label for="student_fee">মাসিক বেতন</label>
                        <input aria-describedby="feeHelp" class="form-control" tabindex="10" value="<?php echo $student->fee; ?>" type="number" step="1" name="student_fee" id="student_roll">
                        <label id="feeHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('student_fee'); ?></label>
                    </div> 
                </div>

                <div class="col-sm-10 col-md-7 col-md-push-3 col-sm-push-1">
                    <div class="form-group" id="sectionflag">
                        <label for="student_mobile">ঠিকানা</label>
                        <textarea  tabindex="11" class="form-control" name="student_address" id="student_address" cols="30" rows="10"><?php echo $student->student_address?></textarea>
                        <label  id="rollHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('student_address'); ?>
                        </label>
                    </div>    
                </div> 

                <div class="col-sm-10 col-md-6 col-md-push-3 col-sm-push-1">
                    <div class="form-group" id="sectionflag">
                        <label for="student_image">ছবি</label>
                        <input aria-describedby="student_imageHelp" class="form-control" tabindex="12"  type="file" name="student_image" id="student_image">
                        [Recommended max H: 225px & W: 225px S:1MB]
                        <label id="student_imageHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('student_image'); ?></label>
                    </div> 
                </div>

                <div class="col-sm-10 col-md-7 col-md-push-3 col-sm-push-1"> 
                    
                    <input type="hidden" name="pimage" value="<?php echo $student->image;?>">
                    <input type="hidden" name="studentid" value="<?php echo $student->id;?>">
                    <input type="hidden" name="ledgerid" value="<?php echo $student->ledger_id;?>">
                    <div class="container-fluid">
                        <div class="row">
                        <div class="col-md-2 col-sm-4"><a class="btn btn-danger" href="<?=$baseurl?>teacher/students_list/<?=$student->class_id;?>/<?=$student->section_id;?>/#tr-<?=$student->roll;?>"><i class="fa fa-times"></i> বাতিল করুন </a></div>
                        <div class="col-md-2 col-sm-4"><button  tabindex="13" value="submit" name="submit" type="submit" id="sbutton" class="btn btn-primary"> হালনাগাত করুন </button></div>
                        </div>
                    </div>
                </div>    
            </form>
        </div>
        <div class="row col-md-5 col-md-push-2" style="margin-top: 30px;">
            <?php echo anchor("teacher/index/",'Attendance') ;?>
        </div>
      </section>
</section>
<?php include __DIR__ .'/../footer.php'; ?>
<script type="text/javascript">
      
      function getsection(id){
        
        var postdata = 'id=' + id;
        // console.log(id);
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("teacher/getsection"); ?>',
            data: postdata,
            success: function (response) {
                var jsonObject = jQuery.parseJSON(response);
                // console.log(jsonObject);
                $("#student_section").find('option').remove().end();
                
                $("#student_section").append($('<option>', {
                        value: '' ,
                        text: 'Select Section'
                }));
               
                if(Object.keys(jsonObject).length>0){
                    $("#sectionflag").show();
                    $.each( jsonObject, function( r,v) {
                        
                        $("#student_section").append($('<option>', {
                            value: v.id,
                            text: v.section_name
                        }));
                    });
                    $('.selectpicker').selectpicker('refresh');
                }

                else{
                    $("#sectionflag").hide();
                }
            }
        });
      }

</script>
