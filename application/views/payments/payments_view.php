<?php $this->load->view("header_view"); ?>
<?php $this->load->view("top_menu/top_nav_view"); ?>
<div class="container-fluid">
  <div class="row-fluid">
    <?php $this->load->view("sidebar/sidebar_dashboard_view"); ?>
    <div class="span9">
      <div class="main">
        <div class="row-fluid">
          <h2>Payments</h2><br />
          
          <a href="javascript:void(0);"><i class="icon-plus"></i> Send Payment Information</a>
          
          <table class="table table-bordered table-hover table-condensed">
              <thead>
                  <tr>
                      <th>ID</th>
                      <th>Date</th>
                      <th>Transaction ID</th>
                      <th>Amount</th>
                      <th>Payment Center</th>
                      <th>Actions</th>
                      <th>Status</th>
                  </tr>
              </thead>
              <tbody>
              <?php  
                    $num_res = count($payments);
                    for($i=0; $i<$num_res; $i++):
              ?>
                  <tr>
                      <td><?=$payments[$i]->uid?></td>
                      <td><?=date('M d, Y', strtotime($payments[$i]->payment_date))?></td>
                      <td><?=$payments[$i]->transaction?></td>
                      <td>PhP <?=$payments[$i]->amount?></td>
                      <td><?=$payments[$i]->payment_center?></td>
                      <td style="font-size: 18px;">
                          <a href="javascript:void(0);" title="Click to see the message" id="message" data-id="<?=$payments[$i]->uid?>"><i class="icon-envelope"></i></a> &middot;
                          <a href="<?=base_url()?>payments/attachment/<?=$payments[$i]->uid?>" target="_blank" id="tooltip-top" title="Click to see transaction receipt"><i class="icon-picture"></i></a>
                      
                      </td>
                      <td>
                            <?=($payments[$i]->status == 0) ? "Unverified" : "Verified"?>
                      </td>
                  </tr>
              <?php
                    endfor;
              ?>
              </tbody>
          </table>
          
        </div><!--/row-->
      </div><!--/main-->
    </div><!--/span-->
  </div><!--/row-->
  <hr>
  <footer>
    <p>&copy; <?=COMPANY_NAME?> <?=date('Y')?></p>
  </footer>
</div><!--/.fluid-container-->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="myModalLabel"></h3>
</div><div class="modal-body"></div><div class="modal-footer"><button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Ok</button></div></div>
<?php $this->load->view("payments/footer_view"); ?>