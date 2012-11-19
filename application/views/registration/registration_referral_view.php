<?php $this->load->view("header_view"); ?>
<?php
    $atts = array(
          'width'      => '800',
          'height'     => '600',
          'scrollbars' => 'yes',
          'status'     => 'yes',
          'resizable'  => 'yes',
          'screenx'    => '0',
          'screeny'    => '0'
    );
?>
<h3>Login Account</h3>
<form id="registrationform" method="POST" action="">
<p>
    <label><strong>Referrer </strong></label>
    <input type="text" class="input input-xxlarge span5" id="referrer" value="<?=$referrer?>" name="referrer" disabled />
</p>
<p>
    <label for="sponsor"><strong>Sponsor </strong></label>
    <input type="text" class="input input-xxlarge span5" id="sponsor" name="sponsor" />
</p>
<p>
    <label class="required" for="username"><strong>Username </strong></label>
    <input type="text" class="input input-xxlarge span5" id="username" name="username" />
</p>
<p>
    <label class="required" for="password"><strong>Password <?=anchor_popup(base_url().'static/password.html', '<i class="icon-question-sign" id="tooltip-right" title="Password Guidelines"></i>', $atts)?></strong></label>
    <input type="password" class="input input-xxlarge span5" id="password" name="password" />
</p>
<p>
    <label class="required" for="password_c"><strong>Re-type Password </strong></label>
    <input type="password" class="input input-xxlarge span5" id="password_c" name="password_c" />
</p>


<h3>Profile</h3>
<p>
    <label class="required" for="lastname"><strong>Last Name </strong></label>
    <input type="text" class="input input-xxlarge span5" id="lastname" name="lastname" />
</p>
<p>
    <label class="required" for="firstname"><strong>First Name </strong></label>
    <input type="text" class="input input-xxlarge span5" id="firstname" name="firstname" />
</p>
<p>
    <label class="required" for="email"><strong>Email Address </strong></label>
    <input type="text" class="input input-xxlarge span5" id="email" name="email" />
</p>
<p>
    <label class="required" for="contact"><strong>Contact Number </strong></label>
    <input type="text" class="input input-xxlarge span5" id="contact" name="contact" />
</p>
<p>
    <label class="required" for="address"><strong>Address </strong></label>
    <input type="text" class="input input-xxlarge span5" id="address" name="address" />
</p>

<p>
    <label class="required" for="question"><strong id="qholder"><?=$question?> </strong></label>
    <input type="text" class="input input-xxlarge span5" id="question" name="question" placeholder="Answer to question..." />
</p>

<p>
    <label class="checkbox">
      <input type="checkbox" id="agree" name="agree"> I agree to the <?=anchor_popup('#', 'terms and conditions', $atts)?>
    </label>
</p><br />

<p>
    <button class="btn btn-success" type="submit" id="save" data-loading-text="Saving Information...">Sign Me Up</button>
</p>
</form>
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="myModalLabel">Result</h3>
</div><div class="modal-body"></div><div class="modal-footer"><button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Ok</button></div></div>
<?php $this->load->view("registration/registration_footer"); ?>