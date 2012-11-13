<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php
    
            $total_segments = $this->uri->total_segments();
            $segment = $this->uri->segment($total_segments);
            
            if($total_segments === 0) {
                echo "Administration Panel";
            } else {
                echo ucfirst($segment);
            }
            
    ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?=base_url()?>css/bootstrap.css" rel="stylesheet">
    <link href="<?=base_url()?>css/bootstrap-responsive.css" rel="stylesheet">
    <link href="<?=base_url()?>css/global.css" rel="stylesheet">
    <link href="<?=base_url()?>css/font-awesome.css" rel="stylesheet">
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script>var base_url = "<?=base_url().index_page()?>";</script>
  </head>
  <body>