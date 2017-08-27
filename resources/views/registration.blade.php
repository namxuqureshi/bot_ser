<!DOCTYPE html>
<html>
<head>
	<head>

<!-- Meta Tags -->
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<meta name="description" content="DentalPlus - Dental Care HTML5 Template" />
<meta name="keywords" content="building,business,construction,cleaning,transport,workshop" />
<meta name="author" content="ThemeMascot" />

<!-- Page Title -->
<title>BeeHive Pharmacy</title>

<!-- Favicon and Touch Icons -->
<link href="images/favicom.png" rel="shortcut icon" type="image/png">
<link href="images/apple-touch-icon.png" rel="apple-touch-icon">
<link href="images/apple-touch-icon-72x72.png" rel="apple-touch-icon" sizes="72x72">
<link href="images/apple-touch-icon-114x114.png" rel="apple-touch-icon" sizes="114x114">
<link href="images/apple-touch-icon-144x144.png" rel="apple-touch-icon" sizes="144x144">
<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;

}
td{
    
    border-right: 10px solid transparent;
    -webkit-background-clip: padding;
    -moz-background-clip: padding;
    background-clip: padding-box;
}​

</style>
<!-- Stylesheet -->
<link rel="stylesheet" type="text/css" href="css/custom-style.css">
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="css/jquery-ui.min.css" rel="stylesheet" type="text/css">
<link href="css/animate.css" rel="stylesheet" type="text/css">
<link href="css/css-plugin-collections.css" rel="stylesheet"/>
<!-- CSS | menuzord megamenu skins -->
<link id="menuzord-menu-skins" href="css/menuzord-skins/menuzord-boxed.css" rel="stylesheet"/>
<!-- CSS | Main style file -->
<link href="css/style-main.css" rel="stylesheet" type="text/css">
<!-- CSS | Preloader Styles -->
<link href="css/preloader.css" rel="stylesheet" type="text/css">
<!-- CSS | Custom Margin Padding Collection -->
<link href="css/custom-bootstrap-margin-padding.css" rel="stylesheet" type="text/css">
<!-- CSS | Responsive media queries -->
<link href="css/responsive.css" rel="stylesheet" type="text/css">
<!-- CSS | Style css. This is the file where you can place your own custom css code. Just uncomment it and use it. -->
<!-- <link href="css/style.css" rel="stylesheet" type="text/css"> -->

<!-- Revolution Slider 5.x CSS settings -->
<link  href="js/revolution-slider/css/settings.css" rel="stylesheet" type="text/css"/>
<link  href="js/revolution-slider/css/layers.css" rel="stylesheet" type="text/css"/>
<link  href="js/revolution-slider/css/navigation.css" rel="stylesheet" type="text/css"/>
<!-- CSS | Theme Color -->
<link href="css/colors/theme-skin-blue4.css" rel="stylesheet" type="text/css">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
@include('partials._header')
<div class="container">
    <h1 class="well">Registration Form</h1>
	<div class="col-lg-12 well">
	<div class="row">
				<form method="post" action="/user/register">
					<div class="col-sm-12">
						<div class="row">
							<div class="col-sm-6 form-group">
								<label>First Name</label>
								<input name="f_name" type="text" placeholder="Enter First Name Here.." class="form-control">
							</div>
							<div class="col-sm-6 form-group">
								<label>Surname</label>
								<input name="s_name" type="text" placeholder="Enter Last Name Here.." class="form-control">
							</div>
						</div>	
						<div class=" form-group">
								<label>Email Address</label>
								<input type="text" name="email" placeholder="Enter Email Address Here.." class="form-control">
							</div>
							<div class="form-group">
								<label>Password</label>
								<input type="Password" name="password" placeholder="Password" class="form-control">
							</div>
							<div class="form-group">
								<label>Confirm Password</label>
								<input type="Password" placeholder="Confirm Password" class="form-control">
							</div>				
						<div class="form-group">
							<label>Address</label>
							<textarea placeholder="Enter Address Here.." name="address"rows="3" class="form-control"></textarea>
						</div>
						{{ csrf_field() }}
						<div class="row">
							<div class="col-sm-4 form-group">
								<label>City</label>
								<input type="text" name="city" placeholder="Enter City Name Here.." class="form-control">
							</div>	
							<div class="col-sm-4 form-group">
								<label>Country</label>
								<input type="text" name="country" placeholder="Enter Country Name Here.." class="form-control">
							</div>	
							<div class="col-sm-4 form-group">
								<label>Postcode</label>
								<input type="text" name="postcode" placeholder="Enter Postcode Here.." class="form-control">
							</div>		
						</div>
						<center><button type="submit" class="btn btn-lg btn-info">Register</button>
						OR
						<a type="button" class="btn btn-lg btn-success" href="/login">Login</a>
						</center>
											
					</div>
				</form> 
				</div>
	</div>
	</div>
@include('partials._footer')
</body>
</html>