<?php
    $total_segments = $this->uri->total_segments();
    $segment = $this->uri->segment($total_segments);
    $has_paid = $this->Paymentsq->has_paid();
?>
<div class="span2">
  <div class="well sidebar-nav">
    <ul class="nav nav-list">
      <li class="nav-header">User Menu</li>
      <li class="<?=$segment==='payments' ? 'active' : ''?>"><a href="<?=base_url()?>payments" <?php if($has_paid === FALSE) { ?> title="Other menus will be shown after the payment has been verified" id="tooltip-bottom"<?php } ?>><i class="icon-money"></i> Payments</a></li>
      <?php
            if($has_paid === TRUE) {
      ?>
      <li><a href="#"><i class="icon-arrow-up"></i> Upgrade Account</a></li>
      <li><a href="#"><i class="icon-download"></i> Downloads</a></li>
      <li><a href="#"><i class="icon-sitemap"></i> Referrals</a></li>
      <li><a href="#"><i class="icon-group"></i> Genealogy</a></li>
      <li><a href="#"><i class="icon-align-right"></i> History</a></li>
      <li><a href="#"><i class="icon-sign-blank"></i> Commissions</a></li>
      <li><a href="#"><i class="icon-picture"></i> Banner Ads</a></li>
      <li><a href="#"><i class="icon-comments"></i> Feedback</a></li>
      <?php
            }
      ?>
      <li class="nav-header"> Account Settings</li>
      <li class="<?=$segment==='profile' ? 'active' : ''?>"><a href="<?=base_url()?>settings/profile"><i class="icon-user"></i> Profile</a></li>
      <li class="<?=$segment==='admin' ? 'active' : ''?>"><a href="<?=base_url()?>settings/admin"><i class="icon-cogs"></i> Account Settings</a></li>
      <li><a href="#"><i class="icon-envelope-alt"></i> Notification Center</a></li>
      <li class="divider"></li>
      <li><a href="<?=base_url()?>logout"><i class="icon-signout"></i> Logout</a></li>
    </ul>
  </div><!--/.well -->
</div><!--/span-->