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

                <h4>Project </h4>
				
				<form>
				<input hidden name="type" value="project"> </input>
				<br>
				<input name="project" placeholder="Project name"></input>		
				<br>
				<br>
				<textarea name="description"  type="text" cols="25" rows="4"  placeholder="project description" style=" width:200px; position:relative; left:10px"></textarea>
				
				<br>
				<label>Customer:</label>
				
				<br>	
				<select name="customer" style="height:30px; width:200px; position:relative; left:10px">
				
				<option value="" selected disabled hidden>Choose customer</option>
                <?php
                   
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["c_id"] . "' >". $row["c_name"] . "</option>";
                }
                ?>
				</select> 
				<a href="customer.php" style="position:relative; left:10px">add</a>
				<br>
				<label>fee per hour(€)</label>
				<br>
				<input name="fee" placeholder="0.00"></input>
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