<?php $this->load->view("header_view"); ?>
<?php $this->load->view("top_menu/top_nav_view"); ?>
<div class="container-fluid">
    <div class="row-fluid">
        <?php 
                if($this->session->userdata('usertype') === 'admin'):
                    $this->load->view("sidebar/sidebar_admin_view"); 
                else:
                    $this->load->view("sidebar/sidebar_dashboard_view"); 
                endif;
        ?>
        <div class="span9">
            <div class="main">
                <div class="row-fluid">
                    <h2>Referrals</h2><br />
                    <table class="table table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $total = count($referrals);
                            if($total > 0):
                                for($i=0; $i<$total; $i++){
                                    $count = $i+1;
                        ?>
                        <tr>
                            <td><?=$referrals[$i]->lastname.', '.$referrals[$i]->firstname?></td>
                            <td><?=safe_mailto($referrals[$i]->email)?></td>
                        </tr>
                        <?php
                                }
                                else:
                        ?>
                        <tr><td colspan="2"><em>No referrals</em></td></tr>
                        <?php
                                endif;
                        ?>
                    </tbody>
                    </table>
                    <?echo $this->pagination->create_links();?>
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
<?php $this->load->view("footer_view"); ?>