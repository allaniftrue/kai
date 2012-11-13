<?php
    $total_segments = $this->uri->total_segments();
    $segment = $this->uri->segment($total_segments);
?>
<div class="span2">
  <div class="well sidebar-nav">
    <ul class="nav nav-list">
      <li class="nav-header">User Menu</li>
      <li><a href="<?=base_url()?>settings/profile"><i class="icon-user"></i> Account Settings</a></li>
      <li><a href="#"><i class="icon-arrow-up"></i> Upgrade Account</a></li>
      <li><a href="#"><i class="icon-download"></i> Downloads</a></li>
      <li><a href="#"><i class="icon-sitemap"></i> Referrals</a></li>
      <li><a href="#"><i class="icon-group"></i> Genealogy</a></li>
      <li><a href="#"><i class="icon-align-right"></i> History</a></li>
      <li><a href="#"><i class="icon-money"></i> Commissions</a></li>
      <li><a href="#"><i class="icon-picture"></i> Banner Ads</a></li>
      <li><a href="#"><i class="icon-comments"></i> Feedback</a></li>
      <li><a href="<?=base_url()?>logout"><i class="icon-signout"></i> Logout</a></li>
    </ul>
  </div><!--/.well -->
</div><!--/span-->