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
                    <h2>Credits</h2><br />
                    <table class="table table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Credits</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                $total = count($all_users);
                                for($i=0; $i<$total; $i++) {
                        ?>
                        <tr>
                            <td><?=$all_users[$i]->username?></td>
                            <td><?=$all_users[$i]->lastname.', '.$all_users[$i]->firstname?></td>
                            <td><?=safe_mailto($all_users[$i]->email)?></td>
                            <td><?=$all_users[$i]->credits?></td>
                            <td style="font-size:14px">
                                <a href="javascript:void(0);" data-id="<?=$all_users[$i]->id?>" id="add-credits" title="Click to manually add credits"><i class="icon-plus-sign"></i></a>&nbsp;&middot;&nbsp;<a href="javascript:void(0);" data-id="<?=$all_users[$i]->id?>" id="sub-credits" title="Click to manually subtract credits"><i class="icon-minus-sign"></i></a>
                            </td>
                        </tr>
                        <?php            
                                }
                        ?>
                        <tr>
                    </tbody>
                    </table>
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
    <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="myModalLabel"></h3>
    </div><div class="modal-body"></div><div class="modal-footer"><button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Ok</button></div></div>

<?php $this->load->view("credits/credits_footer"); ?>