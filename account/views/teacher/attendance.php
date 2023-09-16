<?php include __DIR__ .'/../topheader.php'; ?>
<?php include __DIR__ .'/../menu.php'; ?>

<section id="main-content">
    <section class="wrapper">

            <?php if ($this->session->userdata('success')): ?>
                <div class="alert alert-block alert-success fade in">
                    <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                    <strong>আলহামদুলিল্লাহ,&nbsp;</strong> <?php
                    echo $this->session->userdata('success');
                    $this->session->unset_userdata('success');
                    ?>
                </div> 
            <?php endif; ?>
            <?php if ($this->session->userdata('failed')): ?>
                <div class="alert alert-block alert-danger fade in">
                    <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                    <strong>Oops!</strong> <?php
                    echo $this->session->userdata('failed');
                    $this->session->unset_userdata('failed');
                    ?>
                </div> 
            <?php endif; ?>
            <form id="sform"  method="post" action="<?php echo site_url('teacher/attendance'); ?>"  id="temsellform" enctype="multipart/form-data">
                <div class="row">
                <div class="col-md-6 col-md-push-3">
                    <div class="input-group">
                    <select class="selectpicker form-control" id="class_id" placeholder="Select Class" tabindex="1"   data-live-search="true" name="class_id" onchange="getsection(this.value)">
                                    <option></option>
                                    <?php foreach ($classes as $class):?>
                                    <option <?php echo ($class->id==$class_id)?"selected":"";?> value="<?php echo $class->id?>"><?php echo $class->class_name?></option>

                                    <?php endforeach;?>
                            </select>
                            <label id="rollHelp" class="form-text text-muted" style="color: red;"><?php echo form_error('class_id'); ?></label>
                    <span class="input-group-btn">
                        <button style="margin-left: 20px ;" class="btn btn-default" value="submit" id="sbutton" name="submit" type="submit">জমা দিন</button>
                    </span>
                    <?php if($class_id!=""):?>
                        <span class="input-group-btn">
                            <a style="margin-left: 20px ;" class="btn btn-success" href="<?php echo base_url('teacher/attendance_report/');?><?=$class_id;?>">হাজিরা রিপোর্ট </a>
                        </span>
                    <?php endif;?>
                    </div>
                </div>
                
                </div>  
            </form>

        <?php if(isset($studentlist)):?>
            
        <div class="row">
            <div style="text-align: center;background-color: #bcb9bd;margin-top: 20px;" class="row col-md-push-2 col-md-5 col-sm-12">
            <div class="col-md-6">
                <div class="row">
                    <div><h1><?php echo 'শ্রেনিঃ'.$class_name; echo $section_name!=''?" , ".$section_name:"";?></h1></div> 
                </div>
                <div class="row">
                   <div><h1 id="counter">রোলঃ 1</h1></div> 
                   <div><h3 id="studebtname">নামঃ <?php echo $studentlist[0]->name;?></h3></div> 
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <img id="studentimg" src="<?php echo $baseurl.'assets/image/students/'.$studentlist[0]->image;?>" alt="">
                </div>
            </div>
                <div class="row">
                   <div><button onclick="setattendance('yes')" style="height: 100px;width: 300px;"  class="btn btn-lg btn-success">YES</button></div> 
                </div>
                <div style="margin-top: 15px;" class="row">
                   <div><button onclick="setattendance('no')" style="height: 100px;width: 300px;"  class="btn btn-lg btn-danger">NO</button></div> 
                </div>
            </div>
        </div>
        
        <div style="margin-top: 30px;" >
            <form  method="post" action="<?php echo site_url('teacher/saveattendance'); ?>"  id="tform">
                <table class="table table-striped table-dark">
                    <thead>
                        <tr>
                          <th scope="col">Name</th>
                          <th scope="col">Roll</th>
                          <th scope="col">Attendance</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($studentlist as $student):?>
                            <tr>
                             
                              <td scope="row"> <b>
                                    <?php $temp = $this->db->query("select id,attendance from attendance where insert_date='".date("Y-m-d")."' and student_id='".$student->id."'")->row(); 
                                   
                                    if(empty($temp))
                                       $attendance = 0;
                                    else{
                                        $attendance = $temp->attendance;
                                        $data1 = array(
                                            'type'  => 'hidden',
                                            'name'  => 'attendance_id[]',
                                            'value' =>  $temp->id
                                        );
                                        echo form_input($data1);
                                    }
                                    echo $student->name;?>
                                    
                                    <input id="name<?php echo $student->roll;?>" name="stname[]" type="hidden" value="<?php echo $student->name;?>"> 
                                    <input type="hidden" name="roll[]" value="<?php echo $student->id;?>"> 
                                    <input id="<?php echo $student->roll;?>" type="hidden" name="attendance[]" value="<?php echo $attendance?>">

                                    <input id="image<?php echo $student->roll;?>" type="hidden" value="<?php echo $student->image;?>">
                                </b>
                              </td>
                              
                              <th scope="row"><b><?php echo $student->roll;?></b></th>
                              <td >
                               <?php if($attendance==0):?> <label onclick="changestatus(<?php echo $student->roll;?>)" id="at-<?php echo $student->roll;?>" class="btn btn-sm btn-danger"> Absence </label>
                               <?php else:?>
                                <label onclick="changestatus(<?php echo $student->roll;?>)" id="at-<?php echo $student->roll;?>" class="btn btn-sm btn-success">Attend</label>
                              <?php endif;?>
                             </td>
                            </tr>
                        <?php endforeach;?>
                        <input type="hidden" value="<?php echo $student->roll?>" id="lastroll"
                        >
                    </tbody>
                </table>
                <div style="text-align: center;" id="jomadin" class="row">
                    <input type="hidden" name="class_name" value="<?php echo $class_name;?>">
                    <button style="margin-bottom: 50px;" type="submit" name="submit" value="submit" id="tbutton" class="btn btn-primary">জমা দিন</button> 
                </div>
            </form>
        </div>
        <?php endif;?>
	</section>
</section>
<?php include __DIR__ .'/../footer.php'; ?>
<script type="text/javascript">
    var roll=1;
    var lastroll = $("#lastroll").val();
   function setattendance(att){
    
    var path = "<?php echo $baseurl; ?>" + "assets/image/students/";
        if(att=="yes"){
            $("#at-"+roll).text('Attend').addClass("btn-success")
        .removeClass("btn-danger");
         $("#"+roll).val('1');
        }
        else{
            $("#at-"+roll).text('Absence').addClass("btn-danger")
        .removeClass("btn-success");
         $("#"+roll).val('0');
        }
        roll=roll+1;
        var imagename = $("#image"+roll).val();
        var name2 = $("#name"+roll).val();
        if(roll<=lastroll){
        $("#counter").text('রোলঃ '+roll);
        console.log(name2);
        $("#studebtname").text('নামঃ '+name2);
        $("#studentimg").attr("src", path + imagename);
        }
        else{
            $("#counter").text('End');
        }
   }

   function changestatus(sroll){
    var path = "<?php echo $baseurl; ?>" + "assets/image/students/";
        if($("#at-"+sroll).text()=='Attend'){

            $("#at-"+sroll).text('Absence').addClass("btn-danger")
            .removeClass("btn-success");
            $("#"+sroll).val('0');
        }
        else
        {
            $("#at-"+sroll).text('Attend').addClass("btn-success")
            .removeClass("btn-danger");
            $("#"+sroll).val('1');
        }
         roll = sroll+1;
         var imagename = $("#image"+roll).val();
         var name = $("#name"+roll).val();
         $("#studebtname").text('নামঃ '+name);
         $("#studentimg").attr("src", path + imagename);
         $("#counter").text('রোলঃ '+roll);
   }

   function getsection(id){
        var postdata = 'id=' + id;
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url("teacher/getsection"); ?>',
            data: postdata,
            success: function (response) {
                var jsonObject = jQuery.parseJSON(response);
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
                else{$("#sectionflag").hide();}
            }
        });
      }


</script>

