<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="<?=base_url()?>">MyClub88</a>
          <div class="nav-collapse collapse">

            <ul class="nav pull-right">
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="icon-user icon-white"></i> <?=$this->session->userdata('username')?> <b class="caret"></b>
                </a>
                    <ul class="dropdown-menu">
                      <li><a href="<?=base_url()?>settings/profile"><i class="icon-cogs"></i> Account Settings</a></li>
                      <li class="divider"></li>
                      <li><a href="<?=base_url()?>logout"><i class="icon-signout"></i> Logout</a></li>
                    </ul>
              </li>
            </ul>

           
            <ul class="nav">
              <li class="active"><a href="<?=base_url()?>dashboard">Dashboard</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
</div>