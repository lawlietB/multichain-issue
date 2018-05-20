<?php
	
	require_once 'functions.php';
	
	$config=read_config();
	$chain=@$_GET['chain'];
	
	if (strlen($chain))
		$name=@$config[$chain]['name'];
	else
		$name='';

?>

<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie ie6 no-js" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 no-js" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 no-js" lang="en"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 no-js" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="en"><!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>Chain-demo</title>
<meta name="description" content="OnePage Resume Portfolio">
<meta name="author" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!--main style-->
<link rel="stylesheet" type="text/css" media="screen" href="template/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="template/css/main.css">
<!--google font style-->
<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'> 
<!--font-family: 'Metrophobic', serif;-->
<link href='http://fonts.googleapis.com/css?family=Crimson+Text:400,600,400italic,600italic' rel='stylesheet' type='text/css'> 
<!--font-family: 'Open Sans', sans-serif;-->
<!-- font icon css style-->
<link rel="stylesheet" href="css/font-awesome.min.css">
</head>


<body onLoad="load()" onUnload="GUnload()">
<!-- Preloader -->
<div id="preloader">
	<div id="status"></div>
</div>
<!--wrapper start-->
<div class="wrapper noGap" id="wrapper">

<!--Header start -->
<header>
  	
</header>
  <!--Header end -->
  
 
  
  <!--portfolio start-->
	<section class="protfolio" id="ourwork">
    <div class="container">
        <div class="heading">
            <h1><a href="./">Demo Server 1<?php if (strlen($name)) { ?> &ndash; <?php echo html($name)?><?php } ?></a></h1>
            <?php
                if (strlen($chain)) {
                    $name=@$config[$chain]['name'];
            ?>
        </div>
    </div>
    <div class="row">
      <div class="portfolioFilter">
        <ul>
          <li><a href="#" data-filter="*" class="current">All</a></li>
          <li><a href="#" data-filter=".wordpress">Wordpress</a></li>
          <li><a href="#" data-filter=".template">template</a></li>
          <li><a href="#" data-filter=".illustration">Illustration</a></li>
          <li class="last"><a href="#" data-filter=".webdesign">Web Design</a></li>
        </ul>
        <nav class="navbar navbar-default">
				<div id="navbar" class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li><a href="./?chain=<?php echo html($chain)?>" class="pair-first">Node |</a></li>
						<li><a href="./?chain=<?php echo html($chain)?>&page=permissions" class="pair-center">|  Permissions |</a></li>
						<li><a href="./?chain=<?php echo html($chain)?>&page=issue" class="pair-center">|  Issue Asset |</a></li>
						<li><a href="./?chain=<?php echo html($chain)?>&page=update" class="pair-center">|  Update |</a></li>
						<li><a href="./?chain=<?php echo html($chain)?>&page=balance" class="pair-center">|  Balance |</a></li>
						<li><a href="./?chain=<?php echo html($chain)?>&page=send" class="pair-center"> |  Send |</a></li>
						<li><a href="./?chain=<?php echo html($chain)?>&page=offer" class="pair-center">|  Create Offer |</a></li>
						<li><a href="./?chain=<?php echo html($chain)?>&page=accept" class="pair-center">|  Accept |</a></li>
						<li><a href="./?chain=<?php echo html($chain)?>&page=create" class="pair-center">|  Create Stream |</a></li>
						<li><a href="./?chain=<?php echo html($chain)?>&page=publish" class="pair-center">|  Publish |</a></li>
						<li><a href="./?chain=<?php echo html($chain)?>&page=view" class="pair-center">|  View Streams|</a></li>
						<li><a href="./?chain=<?php echo html($chain)?>&page=block" class="pair-last">|  View Block</a></li>
					</ul>
				</div>
			</nav>
      </div>
    </div>

    <?php
		set_multichain_chain($config[$chain]);
		
		switch (@$_GET['page']) {
			case 'label':
			case 'permissions':
			case 'issue':
			case 'update':
			case 'balance':
			case 'send':
			case 'offer':
			case 'accept':
			case 'create':
			case 'publish':
			case 'view':
			case 'block':
			case 'asset-file':
				require_once 'page-'.$_GET['page'].'.php';
				break;
				
			default:
				require_once 'page-default.php';
				break;
		}
		
	} else {
?>
			<p class="lead"><br/>Choose an available node to get started:</p>
		
			<p>
<?php
		foreach ($config as $chain => $rpc)
			if (isset($rpc['rpchost']))
				echo '<p class="lead"><a href="./?chain='.html($chain).'">'.html($rpc['name']).'</a><br/>';
?>
			</p>
<?php
	}
?>
  </section>
  <!--portfolio end-->   
  
 
  <!--footer start-->
  <section class="footer" id="footer">
    <div class="container">
     <!-- Footer -->
    </div>
  </section>
  <!--footer end-->     
  </div>
<!--wrapper end--> 

<!--modernizr js--> 
<script type="text/javascript" src="template/js/modernizr.custom.26633.js"></script> 
<!--jquary min js--> 
<script type="text/javascript" src="template/js/jquery.min.js"></script> 
<script src="js/bootstrap.min.js"></script> 

<!--for placeholder jquery--> 
<script type="text/javascript" src="template/js/jquery.placeholder.js"></script> 

<!--for header jquery--> 
<script type="text/javascript" src="template/js/stickUp.js"></script> 
<script src="template/js/jquery.superslides.js" type="text/javascript" charset="utf-8"></script>

<!--for portfolio jquery--> 
<script src="template/js/jquery.isotope.js" type="text/javascript"></script> 
<link type="text/css" rel="stylesheet" id="theme" href="template/css/jquery-ui-1.8.16.custom.css">
<link type="text/css" rel="stylesheet" href="template/css/lightbox.min.css">
<script type="text/javascript" src="template/js/jquery.ui.widget.min.js"></script> 
<script type="text/javascript" src="template/js/jquery.ui.rlightbox.js"></script> 

<!--contact form js--> 
<script type="text/javascript" src="template/js/jquery.contact.js"></script> 
<script src="template/js/jquery.easing.js"></script> 
<script src="template/js/jquery.mousewheel.js"></script> 
<!-- <script defer src="template/js/slideroption.js"></script>  -->

<!--for theme custom jquery--> 
<script src="template/js/custom.js"></script>

</body>
</html>