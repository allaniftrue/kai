<?php
    $total_segments = $this->uri->total_segments();
    $segment = $this->uri->segment($total_segments);
?>
<div class="span2">
  <div class="well sidebar-nav">
    <ul class="nav nav-list">
      <li class="nav-header"><i class="icon-user"></i> <b><?=$this->session->userdata('username')?></b></li>
      <li class="<?=$segment==='profile' ? 'active' : ''?>"><a href="<?=base_url()?>settings/profile">Profile</a></li>
      <li class="<?=$segment==='admin' ? 'active' : ''?>"><a href="<?=base_url()?>settings/admin">Account Settings</a></li>
      <li><a href="#">Notification Center</a></li>
      <li class="nav-header">Sidebar</li>
      <li><a href="#">Link</a></li>
      <li><a href="#">Link</a></li>
      <li><a href="#">Link</a></li>
      <li><a href="#">Link</a></li>
      <li><a href="#">Link</a></li>
      <li><a href="#">Link</a></li>
      <li class="nav-header">Sidebar</li>
      <li><a href="#">Link</a></li>
      <li><a href="#">Link</a></li>
      <li><a href="#">Link</a></li>
    </ul>
  </div><!--/.well -->
</div><!--/span-->