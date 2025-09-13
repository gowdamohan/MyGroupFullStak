<div class="panel panel-default">
    <div class="panel-heading ui-draggable-handle">
        <h3 class="panel-title">Validity Settings</h3>         
    </div>
    <form enctype="multipart/form-data" method="post" action="<?php echo site_url('client_controller/insert_validity_member');?>" data-parsley-validate="" class="form-horizontal">
    <div class="panel-body">
        <div class="container">

            <div class="memberShipValidity" style="margin-bottom:2rem">
                <h3>
                1. Is Membership validity is there <br><br>
                <input type="radio" <?php if(!empty($member_validity)) if($member_validity->member_ship_validity == 'Yes')  echo 'checked' ?> name="member_validitiy" onclick="memberShipValidity('Yes')" value="Yes" id="member_validitiy">
                Yes
                <input type="radio" <?php if(!empty($member_validity)) if($member_validity->member_ship_validity == 'No')  echo 'checked' ?> name="member_validitiy" onclick="memberShipValidity('No')" value="No" id="member_validitiy">
                No
                </h3>
            </div>
            
            <div class="memberShipPaid" style="display: none;" style="margin-bottom:2rem">
                <h3>
                2. Is Membership is Free Or Paid <br><br>
                <input type="radio" name="membership_paid" <?php if(!empty($member_validity)) if($member_validity->member_ship_paid_free == 'Yes')  echo 'checked' ?> onclick="memberShipPaid('Yes')" value="Yes" id="membership_paid">
                Yes
                <input type="radio" name="membership_paid" <?php if(!empty($member_validity)) if($member_validity->member_ship_paid_free == 'No')  echo 'checked' ?> onclick="memberShipPaid('No')" value="No" id="membership_paid">
                No
                </h3>
            </div>

            <div class="lifetimeShipPaid" style="display: none;" style="margin-bottom:2rem">
                <h3>
                3. Lifetime Membership is Available <br><br>
                <input type="radio" name="lifetime_member" <?php if(!empty($member_validity)) if($member_validity->member_life_time == 'Yes')  echo 'checked' ?>  onclick="lifetimeShipPaid('Yes')"  value="Yes" id="lifetime_member">
                Yes
                <input type="radio" name="lifetime_member" <?php if(!empty($member_validity)) if($member_validity->member_life_time == 'No')  echo 'checked' ?> onclick="lifetimeShipPaid('No')" value="No" id="lifetime_member">
                No
                </h3>
            </div>
            <div class="form-group" id="lifetimeShipAmount" style="display: none;">
                <div class="col-md-2">
                    <p style="font-size: 14px;">Life Member Ship Amount</p>
                    <div class="input-group">
                        <input type="text" class="form-control" name="life_member_ship_amount" value="<?php if(!empty($member_validity)) echo $member_validity->life_member_ship_amount ?>" placeholder="Life Member Ship Amount">
                    </div>
                </div>
            </div>
             <script type="text/javascript">
                $(document).ready(function(){
                    var member_ship_validity = '<?php if(!empty($member_validity)) echo $member_validity->member_ship_validity ?>';
                    var member_ship_paid_free = '<?php if(!empty($member_validity)) echo $member_validity->member_ship_paid_free ?>';
                    var member_others = '<?php if(!empty($member_validity)) echo $member_validity->member_others ?>';
                    var lifetime_member = '<?php if(!empty($member_validity)) echo $member_validity->member_life_time ?>';
                    memberShipValidity(member_ship_validity);
                    memberShipPaid(member_ship_paid_free);
                    select_other_button(member_others);
                    lifetimeShipPaid(lifetime_member);
                });
                function memberShipValidity(val) {
                    if (val == 'Yes') {
                        $('.memberShipPaid').show();
                        $('.lifetimeShipPaid').hide();
                        $('.OtherShipPaid').hide();
                        $('#lifetimeShipAmount').show();
                    }else if(val == 'No'){
                        $('.memberShipPaid').hide();
                        $('.lifetimeShipPaid').hide();
                        $('.OtherShipPaid').hide();
                        $('#lifetimeShipAmount').hide();
                    }else{
                        $('.memberShipPaid').hide();
                        $('.lifetimeShipPaid').hide();
                        $('.OtherShipPaid').hide();
                        $('#lifetimeShipAmount').hide();
                    }
                }

                function memberShipPaid(val) {
                    if (val == 'Yes') {
                        $('.memberShipPaid').show();
                        $('.lifetimeShipPaid').show();
                        $('.OtherShipPaid').show();
                        $('#lifetimeShipAmount').show();
                    }else if(val == 'No'){
                        $('.memberShipPaid').show();
                        $('.lifetimeShipPaid').hide();
                        $('.OtherShipPaid').hide();
                        $('#lifetimeShipAmount').hide();
                    }else{
                        $('.memberShipPaid').hide();
                        $('.lifetimeShipPaid').hide();
                        $('.OtherShipPaid').hide();
                        $('#lifetimeShipAmount').hide();
                    }
                }
                function select_other_button(val) {
                    if (val == 'Fixed') {
                        $('.auto').hide();
                        $('.fixed').show();
                        $('.manual').hide();
                        $('.auto_fresher_amount').html('');
                        $('.auto_renewal_amount').html('');
                        $('.mannual_selection').removeAttr('checked');

                    }else if(val == 'Auto'){
                        $('.auto').show();
                        $('.fixed').hide();
                        $('.manual').hide();
                        $('.fixed_fresher_amount').html('');
                    }else if(val == 'Manual'){
                        $('.auto').hide();
                        $('.fixed').hide();
                        $('.manual').show();
                        $('.fxied_renewal_amount').html('');
                        $('.auto_fresher_amount').html('');
                        $('.auto_renewal_amount').html('');
                        $('.mannual_selection').removeAttr('checked');
                    }
                }

                function lifetimeShipPaid(val) {
                    if (val == 'Yes') {
                        $('#lifetimeShipAmount').show();
                    }else if(val == 'No'){
                         $('#lifetimeShipAmount').hide();
                    }else{
                        $('#lifetimeShipAmount').hide();
                    }
                }
            </script>
            <div class="OtherShipPaid" style="display: none;" >
                <h3>
                    3. Others <br><br>

                    <label class="check">
                        <div class="iradio_minimal-grey checked" style="position: relative;">
                            <input type="radio" <?php if(!empty($member_validity)) if($member_validity->member_others == 'Fixed')  echo 'checked' ?> onclick="select_other_button('Fixed')" value="Fixed" class="iradio" style="width: 20px;height: 20px;margin-top: -0.2rem;" name="member_other">
                        </div> A. Fixed 
                    </label>

                    <label class="check">
                        <div class="iradio_minimal-grey checked" style="position: relative;">
                            <input type="radio" <?php if(!empty($member_validity)) if($member_validity->member_others == 'Auto')  echo 'checked' ?> onclick="select_other_button('Auto')" value="Auto" class="iradio" style="width: 20px;height: 20px;margin-top: -0.2rem;" name="member_other">
                        </div> B. Auto  
                    </label>

                    <label class="check">
                        <div class="iradio_minimal-grey checked" style="position: relative;">
                            <input type="radio" <?php if(!empty($member_validity)) if($member_validity->member_others == 'Manual')  echo 'checked' ?>  onclick="select_other_button('Manual')" class="iradio" style="width: 20px;height: 20px;margin-top: -0.2rem;" value="Manual" name="member_other">
                        </div>C. Manual 
                    </label>
                </h3>
            </div>

           
            <?php 
                $yearSelection = [];
                $autoFresher = [];
                $autoRenewal = [];
                if (!empty($member_validity)) {
                   $yearSelection = json_decode($member_validity->auto_selection);
                   $autoFresher = json_decode($member_validity->auto_fresher_amount);
                   $autoRenewal = json_decode($member_validity->auto_renewal_amount);
                } 
            ?>
            <div class="auto OtherShipPaid" style="display: none;">
                <table class="table no-border" style="width: 40%;">
                    <tr>
                        <td style="font-size: 16px;width: 20%;">
                            <input type="checkbox" name="auto_selection[]" <?php if(!empty($yearSelection[0])) if($yearSelection[0] == '1_year')  echo 'checked' ?> value="1_year" onclick="select_radio_button('mannual')" class="mannual_selection" id="mannual_selection"> 1 Year
                        </td>
                        <td>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control auto_fresher_amount" value="<?php if(!empty($autoFresher[0])) echo $autoFresher[0] ?>" name="auto_fresher_amount[]" placeholder="Fresher Amount">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control auto_renewal_amount" value="<?php if(!empty($autoRenewal[0])) echo $autoRenewal[0] ?>" name="auto_renewal_amount[]" placeholder="Renewal Amount">
                                </div>
                            </div> 
                        </td> 
                    </tr>
                    <tr>
                        <td style="font-size: 16px;width: 20%;">
                            <input type="checkbox" name="auto_selection[]" <?php if(!empty($yearSelection[1])) if($yearSelection[1] == '2_year')  echo 'checked' ?> value="2_year" onclick="select_radio_button('mannual')" class="mannual_selection" id="mannual_selection"> 2 Year
                        </td>
                        <td>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control auto_fresher_amount" value="<?php if(!empty($autoFresher[1])) echo $autoFresher[1] ?>" name="auto_fresher_amount[]" placeholder="Fresher Amount">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control auto_renewal_amount" value="<?php if(!empty($autoRenewal[1])) echo $autoRenewal[1] ?>" name="auto_renewal_amount[]" placeholder="Renewal Amount">
                                </div>
                            </div> 
                        </td> 
                    </tr>
                    <tr>
                        <td style="font-size: 16px;width: 8%;">
                            <input type="checkbox" name="auto_selection[]" <?php if(!empty($yearSelection[2])) if($yearSelection[2] == '3_year')  echo 'checked' ?> value="3_year" onclick="select_radio_button('mannual')" class="mannual_selection" id="mannual_selection"> 3 Year
                        </td>
                        <td>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control auto_fresher_amount" value="<?php if(!empty($autoFresher[2])) echo $autoFresher[2] ?>" name="auto_fresher_amount[]" placeholder="Fresher Amount">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control auto_renewal_amount" value="<?php if(!empty($autoRenewal[2])) echo $autoRenewal[2] ?>" name="auto_renewal_amount[]" placeholder="Renewal Amount">
                                </div>
                            </div> 
                        </td> 
                    </tr>
                    <tr>
                        <td style="font-size: 16px;width: 8%;">
                            <input type="checkbox" name="auto_selection[]" <?php if(!empty($yearSelection[3])) if($yearSelection[3] == '4_year')  echo 'checked' ?> value="4_year" onclick="select_radio_button('mannual')" class="mannual_selection" id="mannual_selection"> 4 Year
                        </td>
                        <td>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control auto_fresher_amount" value="<?php if(!empty($autoFresher[3])) echo $autoFresher[3] ?>" name="auto_fresher_amount[]" placeholder="Fresher Amount">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control auto_renewal_amount" value="<?php if(!empty($autoRenewal[3])) echo $autoRenewal[3] ?>" name="auto_renewal_amount[]" placeholder="Renewal Amount">
                                </div>
                            </div> 
                        </td> 
                    </tr>
                    <tr>
                        <td style="font-size: 16px;width: 8%;">
                            <input type="checkbox" name="auto_selection[]" <?php if(!empty($yearSelection[4])) if($yearSelection[4] == '5_year')  echo 'checked' ?> value="5_year" onclick="select_radio_button('mannual')" class="mannual_selection" id="mannual_selection"> 5 Year
                        </td>
                        <td>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control auto_fresher_amount" value="<?php if(!empty($autoFresher[4])) echo $autoFresher[4] ?>" name="auto_fresher_amount[]" placeholder="Fresher Amount">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control auto_renewal_amount" value="<?php if(!empty($autoRenewal[4])) echo $autoRenewal[4] ?>" name="auto_renewal_amount[]" placeholder="Renewal Amount">
                                </div>
                            </div> 
                        </td> 
                    </tr>
                </table>
            </div>

            <div class="fixed" style="display: none;">
                <div class="form-group" style="margin-bottom: 2rem;">
                    <div class="col-md-2">
                        <p style="font-size: 14px;" >Date</p>
                        <div class="input-group">
                            <span class="input-group-addon add-on"><span class="fa fa-calendar"></span></span>
                            <input type="text" class="form-control datepicker" name="validate_date" value="<?php if(!empty($member_validity)) echo  $member_validity->validate_date ?>">
                        </div>                                            
                    </div>

                    <div class="col-md-2">
                        <p style="font-size: 14px;">Fresher Amount</p>
                        <div class="input-group">
                            <input type="text" class="form-control fixed_fresher_amount" name="fixed_fresher_amount" value="<?php if(!empty($member_validity)) echo  $member_validity->fixed_fresher_amount ?>" placeholder="Fresher Amount">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <p style="font-size: 14px;">Renewal Amount</p>
                        <div class="input-group">
                            <input type="text" class="form-control fxied_renewal_amount" name="fxied_renewal_amount" value="<?php if(!empty($member_validity)) echo  $member_validity->fxied_renewal_amount ?>" placeholder="Renewal Amount">
                        </div>
                    </div>  
                </div>
            </div>

            <div class="manual" style="display: none;">
                <div class="form-group" style="margin-bottom: 2rem;">
                    <div class="col-md-2">
                        <p style="font-size: 14px;">  Validity Date Fixed by Admin</p>
                    </div>

                    <div class="col-md-2">
                        <p style="font-size: 14px;">Fresher Amount</p>
                        <div class="input-group">
                            <input type="text" class="form-control manual_fresher_amount" name="manual_fresher_amount" value="<?php if(!empty($member_validity)) echo $member_validity->manual_fresher_amount ?>" placeholder="Fresher Amount">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <p style="font-size: 14px;">Renewal Amount</p>
                        <div class="input-group">
                            <input type="text" class="form-control manual_renewal_amount" name="manual_renewal_amount" value="<?php if(!empty($member_validity)) echo  $member_validity->manual_renewal_amount ?>" placeholder="Renewal Amount">
                        </div>
                    </div>  
                </div>
            </div> 
        </div>
    </div>
    <div class="panel-footer">
        <center>
            <button type="submit" class="btn btn-primary">Save</button>
        </center>
    </div>
</form>
   
</div>


<style type="text/css">
   .checkbox input,
.checkbox-inline input,
.radio input,
.radio-inline input {
  opacity: 0;
  position: absolute;
}

.checkbox label,
.radio label {
  margin-top: 5px;
  margin-bottom: 5px;
}

.checkbox .indicator,
.checkbox-inline .indicator,
.radio .indicator,
.radio-inline .indicator {
  position: relative;
}

.checkbox .indicator:before,
.checkbox-inline .indicator:before,
.radio .indicator:before,
.radio-inline .indicator:before {
  content: '';
  border: 2px solid #888;
  display: inline-block;
  vertical-align: middle;
  width: 23px;
  height: 23px;
  padding: 2px;
  margin-top: -5px;
  margin-right: 10px;
  text-align: center;
}

.checkbox input:checked + .indicator:before,
.checkbox-inline input:checked + .indicator:before {
  border-color: #00f;
}

.checkbox input:disabled + .indicator:before,
.checkbox-inline input:disabled + .indicator:before {
  border-color: #ccc;
  box-shadow: inset 0px 0px 0px 4px #fff;
}

.checkbox input:checked:disabled + .indicator:before,
.checkbox-inline input:checked:disabled + .indicator:before {
  border-color: #ccc;
}

.radio input + .indicator:before,
.radio-inline input + .indicator:before {
  border-radius: 50%;
}

.radio input:checked + .indicator:before,
.radio-inline input:checked + .indicator:before {
  border-color: #00f;
  background: #00f;
  box-shadow: inset 0px 0px 0px 5px #fff;
}

.radio input:disabled + .indicator:before,
.radio-inline input:disabled + .indicator:before {
  border-color: #ccc;
  box-shadow: inset 0px 0px 0px 5px #fff;
}

.radio input:checked:disabled + .indicator:before,
.radio-inline input:checked:disabled + .indicator:before {
  border-color: #ccc;
  background: #ccc;
  box-shadow: inset 0px 0px 0px 5px #fff;
}

.checkbox input:focus + .indicator,
.checkbox-inline input:focus + .indicator,
.radio input:focus + .indicator,
.radio-inline input:focus + .indicator {
  outline: 0px solid #ddd;
  /* focus style */
}
</style>