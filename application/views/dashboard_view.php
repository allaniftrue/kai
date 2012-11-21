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
          <div class="row-fluid">
            <div class="span4 well well-small">
              <center>
                <h2><?=$payments_made?></h2>
                <p>Payments Made</p>
              </center>
            </div><!--/span-->
            <div class="span4 well well-small">
              <center>
                <h2>1,000</h2>
                <p>Total Paid</p>
              </center>
            </div><!--/span-->
            <div class="span4 well well-small">
              <center>
                <h2>1,000</h2>
                <p>Total Unpaid</p>
              </center>
            </div><!--/span-->
          </div><!--/row-->
          
          <div class="main">
                <div class="row-fluid">
                    <dl class="dl-horizontal">
                        <dt><h4>Referral Link </h4></dt>
                        <dd><span class="input-xxlarge uneditable-input" id="reflink"><i class=" icon-link"></i> <?=base_url()."ref/".$this->session->userdata('username')?></span></dd>
                    </dl>
                    
                </div><!--//row-fluid -->
          </div><!--//main -->
          
          
          <div class="main">
                <div class="row-fluid">
                    <h3>Account Overview</h3>
                    
                    <dl class="dl-horizontal">
                        <dt>Membership Type:</dt>
                        <dd>...</dd>
                        
                        <dt>Total Earning:</dt>
                        <dd>...</dd>
                        
                        <dt>Total Paid:</dt>
                        <dd>...</dd>
                        
                        <dt>Total Unpaid:</dt>
                        <dd>...</dd>
                    </dl>
                </div><!--//row-fluid -->
          </div><!--//main -->
          
          <div class="main">
                <div class="row-fluid">
                    <h3>Account Details</h3>
                    
                    <dl class="dl-horizontal">
                        <dt>Created:</dt>
                        <dd><?=date('F d, Y',  strtotime($userinfo[0]->date))?></dd>
                        
                        
                        
                        <dt>Expiration:</dt>
                        <dd>
                            <?php
                                    $date = $userinfo[0]->expiration;
                                    if($date === '0000-00-00') {
                                        $date =  "Never Expire";
                                    } else {
                                        $date = date('F d, Y',  strtotime($userinfo[0]->expiration));
                                    }
                                    echo $date;
                            ?>
                        </dd>
                        
                        <dt>Membership Status:</dt>
                        <dd><?=ucfirst($userinfo[0]->status)?></dd>
                        
                        <dt>Username:</dt>
                        <dd><?=$userinfo[0]->username?></dd>
                        
                        <dt>Full Name:</dt>
                        <dd><?=$userinfo[0]->lastname.', '.$userinfo[0]->firstname?></dd>
                        
                        <dt>Email:</dt>
                        <dd><?=$userinfo[0]->email?></dd>
                    </dl>
                </div><!--//row-fluid -->
          </div><!--//main -->
        </div><!--/span-->
      </div><!--/row-->
      <hr>
      <footer>
        <p>&copy; MyClub88 <?=date('Y')?></p>
      </footer>
    </div><!--/.fluid-container-->
<?php $this->load->view("footer_view"); ?>