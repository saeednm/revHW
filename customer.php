<?php
    $loginUser="Saeed";
	require_once 'Data.php';
    $d = new Data();
	$userID=1;
    $result = $d->getCustomers($userID);   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php header('Access-Control-Allow-Origin: *'); ?>
    <meta charset="UTF-8">
    <title>Reviso HW</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./stylesheets/main.min.css">
    <link rel="stylesheet" href="./stylesheets/products.min.css">
    <script type="text/javascript" src="script.js"></script>
	 <head>
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script>
      $(function () {

        $('form').on('submit', function (e) {

          e.preventDefault();

          $.ajax({
            type: 'post',
            url: 'post.php',
            data: $('form').serialize(),
            success: function (resp) {
              alert('form was submitted');
			  console.log( resp );
            }
          });

        });

      });
    </script>
  </head>
</head>
<body>
<?php include('menu.html'); ?>
<div class="container">
    <div class="row">
		<?php echo "<h5> Hi ".$loginUser."! </h5>"; ?>
        <div class="col-md-4">            
            <div class="side-bar">
                <h4>Customer</h4>				
				<form>
				<input hidden name="type" value="customer"></input>			
				<br>
				<input name="customer" placeholder="Customer name"></input>			
				<br>
				<br>
				<textarea name="description" type="text" cols="25" rows="4" placeholder="Customer description"></textarea>
				<br>
				<br>
				<input name="submit" type="submit" value="Submit"></input>
				</form>
				
            </div>
        </div>
    </div>
</div>
<!-- scripts added last for faster loading -->
<script src="/bower_components/jquery/dist/jquery.min.js"></script>
<script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>


</body>
</html>