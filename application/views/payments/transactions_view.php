<?php $this->load->view("header_view"); ?>
<?php $this->load->view("top_menu/top_nav_view"); ?>
<div class="container-fluid">
    <div class="row-fluid">
        <?php
        if ($this->session->userdata('usertype') === 'admin'):
            $this->load->view("sidebar/sidebar_admin_view");
        else:
            $this->load->view("sidebar/sidebar_dashboard_view");
        endif;
        ?>
        <div class="span9">
            <div class="main">
                <div class="row-fluid">
                    <h2>Transactions</h2><br />
                    <div class="alert alert-info">
                        <i class="icon-info-sign" id="tooltip-top" title="Click to approve transaction"></i> Approve the transaction if it has been verified.  It will automatically add the value paid to the user's number of credit(s)
                    </div>
                    
                    <?php
                            $total_segment = $this->uri->total_segments();
                            $segment = $this->uri->segment($total_segment);
                            
                            if(is_numeric($segment)) {
                                $segment = $this->uri->segment($total_segment-1);
                            }
                    ?>
                    
                    
                    <ul class="nav nav-pills">
                        <li class="<?=($segment=='transactions') ? 'active' : ''?>">
                          <a href="<?=base_url()?>transactions">All</a>
                        </li>
                        <li class="<?=($segment=='pending') ? 'active' : ''?>"><a href="<?=base_url()?>transactions/pending">Pending</a></li>
                        <li class="<?=($segment=='approved') ? 'active' : ''?>"><a href="<?=base_url()?>transactions/approved">Approved</a></li>
                    </ul>
                    <table class="table table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Payment Center</th>
                            <th>Amount</th>
                            <th>Transaction ID</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $total = count($transactions_info);
                            if($total > 0) 
                            {
                                for($i=0; $i<$total; $i++):
                        ?>
                                <tr class="<?=($transactions_info[$i]->status == '1')?'success':''?>">
                                    <td><?=date('M d, Y',strtotime($transactions_info[$i]->payment_date))?></td>
                                    <td><?=$transactions_info[$i]->username?></td>
                                    <td><?=$transactions_info[$i]->lastname.', '.$transactions_info[$i]->firstname?></td>
                                    <td><?=$transactions_info[$i]->contact?></td>
                                    <td><?=mailto($transactions_info[$i]->email)?></td>
                                    <td>
                                        <?=anchor(base_url().'payments/attachment/'.$transactions_info[$i]->uid,$transactions_info[$i]->payment_center,'id="download" title="<i class=\'icon-download-alt\'></i> Download Transaction Receipt" target="_blank"')?>
                                    </td>
                                    <td class="text-right">&#8369; <?=number_format($transactions_info[$i]->amount)?></td>
                                    <td><?=$transactions_info[$i]->transaction?></td>
                                    <td style="font-size:16px">
                                        <a href="javascript:void(0);" id="message" data-id="<?=$transactions_info[$i]->uid?>" title="Read Message">
                                            <i class="icon-envelope-alt"></i></a> &middot;
                                        <?php if($transactions_info[$i]->status === '0'){ ?>
                                        <a href="javascript:void(0);" id="claimed" data-id="<?=$transactions_info[$i]->uid?>" title="Approve Transaction">
                                            <i class="icon-thumbs-up"></i></a>
                                        <?php } ?>
                                        <?php if($transactions_info[$i]->status === '0'){ ?>
                                         &middot;
                                        <a href="javascript:void(0);" id="remove" data-id="<?=$transactions_info[$i]->uid?>" title="Completely Remove trasaction"><i class="icon-trash"></i></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                        <?php
                                endfor;
                            } 
                            else 
                            {
                        ?>
                                <tr>
                                    <td colspan="9"><em>No transactions found</em></td>
                                </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                    </table>
                    <?php echo $this->pagination->create_links(); ?>
                </div><!--/row-->
            </div><!--/main-->
        </div><!--/span-->
    </div><!--/row-->
    <hr>
    <footer>
        <p>&copy; <?= COMPANY_NAME ?> <?= date('Y') ?></p>
    </footer>
</div><!--/.fluid-container-->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="myModalLabel">Result</h3>
    </div><div class="modal-body"></div><div class="modal-footer"><button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Ok</button></div></div>
<?php $this->load->view("payments/transactions_footer_view"); ?>