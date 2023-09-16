<?php include 'topheader.php'; ?>
<?php include 'menu.php'; ?>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->    

        <div class="row">
            <div class="col-lg-12">                

                <section class="panel">

                    <header class="panel-heading">
                        Account Ledger
                    </header>

                    <div class="panel-body">

                        <?php if ($this->session->userdata('success')): ?>
                            <div class="alert alert-block alert-success fade in">
                                <button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>
                                <strong>Congratulation!</strong> <?php
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
                        <form class="form-horizontal" role="form"action="<?php echo site_url('master/updateledger'); ?>" method="post" enctype="multipart/form-data">


                                    <div class="form-group" <?php if($supp->status==2) echo "hidden";?>>
                                        <label for="name" class="col-lg-4 col-sm-4 control-label">Account Ledger Name</label>
                                        <div class="col-lg-7">
                                            <input type="text" class="form-control" maxlength="50" name="ledgername"  value="<?php echo $supp->ledgername; ?>" required="">
                                        </div>
                                    </div> 

                                    <div <?php if($supp->accountgroupid!=5&&$supp->accountgroupid!=6&&$supp->accountgroupid!=8&&$supp->accountgroupid!=9) echo"hidden"; ?> id="father_name<?php echo $supp->id;?>" class="form-group">
                                        <label for="father_name" class="col-lg-4 col-sm-4 control-label">Father's Name</label>
                                        <div class="col-lg-7">
                                            <input type="text" class="form-control" name="father_name" maxlength="25"  value="<?php echo $supp->father_name; ?>">
                                        </div>
                                    </div>


                                    <div <?php if($supp->status==2) echo "hidden";?> class="form-group">
                                    <label for="acgroup" class="col-lg-4 col-sm-4 control-label">Account Group</label>
                                    <div class="col-lg-7">
                                        <select class="form-control"  name="accountgroup" required="" onchange="editcheckacgroup(this.value,<?php echo $supp->id;?>)">
                                            <?php
                                                foreach ($acgroup as $acg):
                                                    ?>
                                                    <option <?php echo($acg->id==$supp->accountgroupid)?" selected ":"";?> value="<?php echo $acg->id; ?>"><?php echo $acg->name ?></option>
                                                    <?php
                                                endforeach;
                                            ?>
                                        </select>
                                        <span style="color: red" id="error_acgroup"></span>
                                    </div>
                                    </div> 


                                    <div <?php if($supp->openingbalance!=0 ) echo "Hidden";?> class="form-group">
                                        <label for="opbalance" class="col-lg-4 col-sm-4 control-label">Opening Balance</label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" maxlength="11" name="opbalance" value="<?php echo ($supp->openingbalance);?>">
                                        </div>
                                        <div class="col-lg-3">
                                            <select class="form-control" name="baltype">
                                                <option <?php if($supp->credit!=0) echo ' selected '; ?> value="credit">Credit</option>
                                                <option <?php if($supp->debit!=0) echo ' selected '; ?>value="debit">Debit</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div <?php if($supp->accountgroupid!=5&&$supp->accountgroupid!=6&&$supp->accountgroupid!=8&&$supp->accountgroupid!=9) echo"hidden";?> id="districtlist<?php echo $supp->id;?>" class="form-group">
                                        <label for="email" class="col-lg-4 col-sm-4 control-label">District</label>
                                        <div class="col-lg-7">
                                            <select  data-live-search="true" class="form-control selectpicker"  name="district" required="">
                                                <option value="0">---Select One---</option>
                                                <?php
                                                    foreach ($districts as $district):
                                                        ?>
                                                        <option <?php echo($supp->district==$district->id)?" selected ":"";?> value="<?php echo $district->id; ?>"><?php echo $district->name ?></option>
                                                        <?php
                                                    endforeach;
                                                
                                                ?>
                                            </select>
                                            <span style="color: red" id="error_acgroup"></span>
                                        </div>
                                    </div>

                                    <div <?php if($supp->accountgroupid!=5&&$supp->accountgroupid!=6&&$supp->accountgroupid!=8&&$supp->accountgroupid!=9) echo"hidden"?> id="mobile<?php echo $supp->id;?>" class="form-group">
                                        <label for="mobile" class="col-lg-4 col-sm-4 control-label">Mobile</label>
                                        <div class="col-lg-7">
                                            <input type="text" class="form-control" maxlength="25" name="mobile" value="<?php echo $supp->mobile; ?>">
                                        </div>
                                    </div>

                                    <div <?php if($supp->accountgroupid!=5&&$supp->accountgroupid!=6&&$supp->accountgroupid!=8&&$supp->accountgroupid!=9) echo"hidden"?> id="address<?php echo $supp->id;?>" class="form-group">
                                        <label for="address" class="col-lg-4 col-sm-4 control-label">Address</label>
                                        <div class="col-lg-7">
                                            <input type="text" class="form-control" maxlength="50" name="address" value="<?php echo $supp->address; ?>">
                                        </div>
                                    </div> 

                                    <div class="form-group">
                                        <div class="col-lg-offset-4 col-lg-7">
                                            <input type="hidden" name="fromname" value="ledger"/>
                                            <input type="hidden" name="updateid" value="<?php echo $supp->id; ?>"/>
                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                            <input type="submit" class="btn btn-success" value="Submit" id="finaleditsub<?php echo $supp->id; ?>">
                                            
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </section>
                    </div>
                </div>
            </section>
        </section>

        <?php include 'footer.php'; ?>
        <script type="text/javascript">
           function editcheckacgroup(id,ledgerid){
        if(id==5||id==6||id==8|| id==9){
            $( "#accounttype"+ ledgerid ).show();
            $( "#districtlist"+ ledgerid ).show();
            $( "#mobile"+ ledgerid ).show();
            $( "#address"+ ledgerid ).show();
            $( "#father_name"+ ledgerid).show();
        }
        else{
            $( "#accounttype"+ ledgerid ).hide();
            $( "#districtlist"+ ledgerid ).hide();
            $( "#mobile"+ ledgerid ).hide();
            $( "#address"+ ledgerid ).hide();
            $( "#father_name"+ ledgerid).hide();
        }
    }
    </script>