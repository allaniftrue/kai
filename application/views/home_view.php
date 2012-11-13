<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="utf-8">
	    <title>Administration Panel</title>
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	    <link rel="stylesheet" href="<?=base_url()?>css/bootstrap.min.css" />
	    <link rel="stylesheet" href="<?=base_url()?>css/bootstrap-responsive.min.css" />

	    <script type="text/javascript" src="<?=base_url()?>js/bootstrap.min.js"></script>
	    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	    <!--[if lt IE 9]>
	      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	    <![endif]-->
	</head>
	<body>
		<form method="post" action="<?=base_url()?>login/auth">
			<input type="hidden" name="csrf_name" value="<?=$hash?>" />
			<input type="text" name="username" class="input input-medium" value="" placeholder="Username" />
			<input type="password" name="password" class="input input-medium" value=""  placeholder="Password" />
			<input type="submit" value="Login" class="btn btn-primary" />
		</form>
	</body>
</html>