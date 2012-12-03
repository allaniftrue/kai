<?php 
    $this->load->view("header_view");  
    $this->load->view("top_menu/top_nav_view"); 
    $atts = array(
          'width'      => '800',
          'height'     => '600',
          'scrollbars' => 'yes',
          'status'     => 'yes',
          'resizable'  => 'yes',
          'screenx'    => '0',
          'screeny'    => '0'
    );
    $product_image = $thumb ? '_products/'.$thumb : 'img/products.png';
?>
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
               
                <form id="product-form" method="post" action="<?=base_url()?>products/file_upload"  enctype="multipart/form-data">
                <div class="row-fluid">
                    <h2>Add New Products</h2>
                     <div class="alert alert-info">
                        <i class="icon-info-sign"></i>&nbsp;A default product image will be used for undefined product image
                    </div>
                    <?php 
                        if($this->session->userdata('status') === 1) {
                    ?>
                    <div class="alert alert-success">
                        <i class="icon-ok-sign"></i>&nbsp;<?=$this->session->userdata('message')?>
                    </div>
                    <?php
                        } elseif($this->session->userdata('status') === 0) {
                    ?>
                    <div class="alert alert-error">
                        <i class="icon-exclamation-sign"></i>&nbsp;<?=$this->session->userdata('message')?>
                    </div>
                    <?php 
                        } 
                        $array = array('status'=>'','message'=>"");
                        $this->session->set_userdata($array);
                    ?>
                    <!-- products image -->
                    <div class="span3">
                        <p>
                            <label for="productimage" class="required"><strong>Product Image</strong></label>
                            <?php
                                  $max_upload = (int)(ini_get('upload_max_filesize'));
                                  $max_post = (int)(ini_get('post_max_size'));
                                  $memory_limit = (int)(ini_get('memory_limit'));
                                  $upload_mb = min($max_upload, $max_post, $memory_limit);
                            ?>
                          <div clas="" id="avatar-holder" data-original-title="<p align='left'>Click to add the product image<br />Image Size: 160x160 px<br />Maximum File Size: <?=$upload_mb." MB"?> </p>">
                              <div id="productuploader"><center><i class="icon-upload icon-white"></i> File Select</center></div>
                              <img src="<?=base_url().$product_image?>" class="img-polaroid" />
                          </div> 
                          <div id="messages"></div>
                          <br />
                        </p>
                    </div>
                    <div class="span3">
                            <p>
                                <label for="name" class="required"><strong>Product Name</strong></label>
                                <input type="text" id="name" name="name" class="input input-large" />
                            </p>
                            <p>
                                <label for="cost" class="required"><strong>Cost</strong></label>
                                <input type="text" id="cost" name="cost" class="input input-large" value="1" />
                            </p>
                            <p>
                                <label for="file" class="required"><strong>File (zip) <?=anchor_popup(base_url().'static/zip-unzip.html', '<i class="icon-question-sign" id="tooltip-right" title="How to zip a file"></i>', $atts)?></strong></label>
                                    <input id="filename" type="text" class="input disabled input-large" name="filename" readonly />
                                    <a id="fileselectbutton" class="btn">Choose...</a>
                                    <input type="file" id="file" name="file" class="hide" />
                            </p>
                        </div>
                        <div class="span5">
                            <p>
                                <label for="description" class="required"><strong>Product Description</strong></label>
                                <textarea name="description" id="description" class="input-block-level" rows="10"></textarea>
                            </p>
                        </div>
                    
                    <!-- end of priducts -->
                </div><!--/row-->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" data-loading-text="Adding New Product..." id="add-new-product">&nbsp;Add New Product</button>
                </div>
               </form>
            </div><!--/main-->
            
            <div class="main">
                <div class="row-fluid">
                    <h2>Products</h2>

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
<?php $this->load->view("products/footer_view"); ?>