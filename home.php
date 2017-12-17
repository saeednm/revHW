<?php   
    require_once 'Data.php';
    $d = new Data();
    $result = $d->getProjects();  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php header('Access-Control-Allow-Origin: *'); ?>
    <meta charset="UTF-8">
    <title>Reviso HW</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="./bower_components/bootstrap/dist/css/bootstrap.min.css">
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
        <div class="col-md-4">
            <div class="side-bar">

                <h4>Projects</h4>
				
				<form>
				
				<input hidden name="type" value="work"> </input>
				
				<label style="width:60px">project</label>
				<select name="project" style="width:150px">
  

                <?php
                   
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["p_id"] . "' >". $row["p_name"] . "</option>";
                }
                ?>
				</select>
				<br>
				<label style="width:60px">hours</label>
				
				<input name="hour" value="0" style="width:150px"> </input>
				<br>
				<label style="width:60px">minute</label>
				<input name="minute" value="0" style="width:150px"> </input>
				<br>
				<label style="width:60px">date</label>
				<input id="date" name="date" type="date" value="date" style="width:150px"> </input>
				<br>
				<input name="submit" type="submit" value="Submit"> </input>
				</form>
                <hr>
                
				
            </div>
        </div>
        
    </div>
</div>
<!-- scripts added last for faster loading -->
<script src="./bower_components/jquery/dist/jquery.min.js"></script>
<script src="./bower_components/bootstrap/dist/js/bootstrap.min.js"></script>


</body>
</html>