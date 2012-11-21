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
                    <h2>Feedback</h2><br />
                    <form id="feedback-form" action="" method="post">
                        <p>
                            <label class="required" for="subject"><strong>Subject</strong> </label>
                            <input type="text" name="subject" id="subject" class="input input-xxlarge"/>
                        </p>
                        <p>
                            <label class="required" for="message"><strong>Feedback Message</strong> </label>
                            <textarea rows="6" class="span8" id="message" name="message"></textarea>
                        </p>
                        <p>
                            <button type="submit" name="submit" id="submit" class="btn btn-success" data-loading-text="Sending Feedback...">Send Feedback Message</button>
                        </p>
                    </form>
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
<?php $this->load->view("feedback/feedback_footer"); ?>