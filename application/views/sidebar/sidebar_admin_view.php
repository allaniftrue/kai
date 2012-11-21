<?php
    $total_segments = $this->uri->total_segments();
    $segment = $this->uri->segment($total_segments);
    $has_paid = $this->Paymentsq->has_paid();
    
    if(is_numeric($segment)) { $segment = $this->uri->segment($total_segments - 1); }
    
?>
<div class="span2">
  <div class="well sidebar-nav">
    <ul class="nav nav-list">
        
      <li class="nav-header">Admin Menu</li>  
      <li class="<?=$segment==='all-users' ? 'active' : ''?>"><a href="<?=base_url()?>all-users"><i class="icon-globe"></i> All Users</a></li>
      <li><a href="#"><i class="icon-asterisk "></i> Transactions&nbsp;&nbsp; <?php if($this->Paymentsq->count_unmanaged_payments() != 0){?><small><span class="badge badge-important"><?=$this->Paymentsq->count_unmanaged_payments()?></small><?php } ?></a></span></li>
      
      
      <li class="nav-header">User Menu</li>
      <li class="<?=$segment==='payments' ? 'active' : ''?>">
          <a href="<?=base_url()?>payments" <?php if($has_paid === FALSE) { ?> title="Other menus will be shown after the payment has been verified" id="tooltip-bottom"<?php } ?>><i class="icon-money"></i> Payments</a></li>

      <li><a href="#"><i class="icon-arrow-up"></i> Upgrade Account</a></li>
      <li><a href="#"><i class="icon-download"></i> Downloads</a></li>
      <li class="<?=$segment==='referrals' ? 'active' : ''?>"><a href="<?=base_url()?>referrals"><i class="icon-group"></i> Referrals</a></li>
      <li><a href="#"><i class="icon-sitemap"></i> Genealogy</a></li>
      <li><a href="#"><i class="icon-align-right"></i> History</a></li>
      <li><a href="#"><i class="icon-sign-blank"></i> Commissions</a></li>
      <li><a href="#"><i class="icon-picture"></i> Banner Ads</a></li>
      <li class="<?=$segment==='feedback' ? 'active' : ''?>"><a href="<?=base_url()?>feedback"><i class="icon-comments"></i> Feedback</a></li>
      <li class="nav-header"> Account Settings</li>
      <li class="<?=$segment==='profile' ? 'active' : ''?>"><a href="<?=base_url()?>settings/profile"><i class="icon-user"></i> Profile</a></li>
      <li class="<?=$segment==='admin' ? 'active' : ''?>"><a href="<?=base_url()?>settings/admin"><i class="icon-cogs"></i> Account Settings</a></li>

      <li class="divider"></li>
      <li><a href="<?=base_url()?>logout"><i class="icon-signout"></i> Logout</a></li>
    </ul>
  </div><!--/.well -->
</div><!--/span-->