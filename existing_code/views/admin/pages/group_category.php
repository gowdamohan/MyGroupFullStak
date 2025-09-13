<?php 
  $users = $this->ion_auth->user()->row();
?>

<ul class="breadcrumb">
   <li><a href="<?php echo site_url('dashboard');?>">Dashboard</a></li>
   <li>Create Category</li>
</ul>
<div class="panel panel-default">
   <div class="panel-heading">
      <h3 class="panel-title"><?php echo strtoupper($group_name) ?></h3>
   </div>
    <?php 
   $group_list = [];
   $a = 0;
   $b = 0;
   $c = 0;
   switch ($group_name) {
      case 'Mymedia':
         $group_list = ['tv','radio','news','magazine','webnews','youtube','mygod'];
         $a = 0;
         $b = 0;
         $c = 0;
         break;
      // case 'Mydiary':
      //    $group_list = ['myradio','myaudio','mybooks','mypage','mytok','mygames'];
      //    break;
      case 'Myjoy':
         $group_list = ['myvideo','myaudio','mybooks','mypage','mytok','mygames'];
         $a = 0;
         $b = 0;
         $c = 0;
         break;
      case 'Myshop':
         $group_list = ['shop','local','resale','brands','wholesale','echoshop'];
         break;
      case 'Myfriend':
         $group_list = ['myfriend','mymarry','myjobs','health','travel','booking'];
         $a = 0;
         $b = 0;
         $c = 0;
         break;
      case 'Myunions':
         // $group_list = ['news','unions','federation','ids','notice','me'];
         $group_list = ['unions','federation','ids'];
         $a = 0;
         $b = 0;
         $c = 0;
         break;
      case 'Mybiz':
         $group_list = ['production','finance','advertise','franchises','trading','services'];
         $a = 0;
         $b = 0;
         $c = 0;
         break;
      case 'Mytv':
         $group_list = ['reporter','gallery','public'];
         $a = 0;
         $b = 0;
         $c = 0;
         break;
      case 'Myneedy':
         $group_list = ['doorstep','centers','manpower','online','myhelp'  ];
         $a = 0;
         $b = 0;
         $c = 0;
         break;

     
   } ?>
   <div class="panel-body">
      <div class="col-md-3">
         <div class="panel-body list-group list-group-contacts">
            <?php $i=1; foreach ($group_list as $key => $val) { ?>
               <a href="<?php echo site_url('admin_controller/create_group_category/'.$group_name.'/'.$val.'/'.'singleActive') ?>" class="list-group-item">                               
                  <span class="contacts-title"><?php echo $i.'. '.strtoupper($val) ?></span>
               </a>
            <?php $i++; } ?>
         </div>
      </div>
   </div>
</div>