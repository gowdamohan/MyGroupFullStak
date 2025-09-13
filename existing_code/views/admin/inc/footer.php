        <!-- MESSAGE BOX-->
        <div class="message-box animated fadeIn" data-sound="alert" id="mb-signout">
            <div class="mb-container">
                <div class="mb-middle">
                    <div class="mb-title"><span class="fa fa-sign-out"></span> Log <strong>Out</strong> ?</div>
                    <div class="mb-content">
                    <!--  -->     
                       <!--  <p>Press No if youwant to continue work. Press Yes to logout current user.</p> -->
                        <div class="pull-right text-center">
                            <p style="color: #000; font-size: 18px; margin-bottom: 28px;">Are you sure you want to log out?</p>
                            <a  style="background-color: #00701a" class="btn btn-success btn-lg mb-control-close margin_mobile">No</a>
                            <a  href="<?php echo site_url('auth/logout');?>" class="btn btn-default btn-lg margin_mobile">Yes</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style type="text/css">
            .message-box .mb-container{
                background: #fff;
            }
            .x-navigation li.active > a{
                background: #00701a;
            }

            @media (max-width: 768px) {
                .message-box .mb-container .mb-middle{
                    width: 100%;
                    padding: 0;
                    margin-left: -25%;
                }
            }

           

        </style>
        <!-- END MESSAGE BOX-->

        
        <!-- START THIS PAGE PLUGINS-->        
        <script type='text/javascript' src='<?php echo base_url();?>assets/back_end/js/plugins/icheck/icheck.min.js'></script>        
        <script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
       
        <script type='text/javascript' src='<?php echo base_url();?>assets/back_end/js/plugins/bootstrap/bootstrap-datepicker.js'></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/plugins/owl/owl.carousel.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/plugins/moment.min.js"></script>

        <script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/plugins/bootstrap/bootstrap-select.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/plugins/tagsinput/jquery.tagsinput.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/plugins/bootstrap/bootbox.min.js"></script>
        <!-- END THIS PAGE PLUGINS-->
        

        
        <script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/plugins/bootstrap/bootstrap-timepicker.min.js"></script>
        <!-- <script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/plugins/bootstrap/bootstrap-colorpicker.js"></script> -->
        <script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/actions.js"></script>
                
        <script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/parsley.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/bootstrap-datetimepicker.js"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/back_end/js/common/multiselect.min.js');?>"></script>
        <!-- <script type='text/javascript' src='<?php echo base_url();?>assets/js/chung-timepicker.js'></script>  -->
        <script type='text/javascript' src='<?php echo base_url();?>assets/back_end/js/pnotify.js'></script>
        <script type='text/javascript' src='<?php echo base_url();?>assets/back_end/js/pnotify.buttons.js'></script> 
        
        <!--For Calendar-->
        <script type="text/javascript" src="<?php echo base_url();?>assets/back_end/js/monthly.js"></script>
        <!-- End For Calendar-->
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/back_end/js/plugins/dropzone/dropzone.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/back_end/js/plugins/blueimp/jquery.blueimp-gallery.min.js"></script>


        <script type="text/javascript">
        $('#demo-form').parsley();
        </script>
        <script type="text/javascript">
            var $form = $('#demo-form');
            $('.submitFormSingleClick').click (function () {
                if ($form.parsley().validate()){
                  //console.log ( 'valid' );
                  $(this).val('Please wait ...').attr('disabled','disabled');
                  $('#demo-form').submit(); 
                }
            });
        </script>
    </body>
</html>