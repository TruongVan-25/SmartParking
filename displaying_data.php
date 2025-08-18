<?php
	
	//include 'php/draw.php';
	session_start();
	
	//include('php/conn2.php');
	if($_SESSION['LoginInto'] == "TRUE") {
		$current = 'data';
		require_once("includes/header.php");
	//	include 'php/getdatatable.php';
	}
	else {
		 header('Location: /smartparking/login.php');
	}
?>

	
	<div class="wrap" style="background: url(image/3.jpg);">
		<div class="row">
	        <div class="col-12" style="text-align: center;">
	            <h2 class="Title" style="color: white"> REAL-TIME MONITORING DATA </h2>
				<button id="showDataGraph" class="btn btn-success" style="font-size: 17px; text-align: center;">View Graph Data</button>
				<button id="refresh_data" class="btn btn-info" style="font-size: 17px;">Refresh Data</button>
	        </div>
	    </div>
		<div class="row">
			<div class="col-sm-6" style="text-align: center;">
				<!-- <form method="POST" action="php/downloadtable.php">
					<input type="submit" name="export" class="btn btn-success" value="Export & Download" />
				</form>   -->
				<br />
				<table class="data-table">
				<!-- <table class="table table-striped"> -->
					<thead>
						<tr>
							<th>DATE</th>
							<th>TEMPERATURE</th>
							<th>HUMINITY</th>
							
						</tr>
					</thead>
					<tbody>
						<?php   
	                        $i = 1;
	                        include("php/connectSQL.php");
	                        // Check connection
	                        
	                        
	                        if (isset($_GET['page'])) {
	                            $page  = $_GET['page'];
	                        } else {
	                            $page  = 1;
	                        }
	                        $pos_per_page  = 12; 
	                        $offset  = ( $page  - 1)  *  $pos_per_page;

	                        $sql = "SELECT * FROM `monitor` ORDER BY `date` DESC Limit $offset,  $pos_per_page";
	                        $result = mysqli_query($conn, $sql);
	                        while($row = mysqli_fetch_array($result)) {
	                    ?>
	                        <tr>
	                            <!-- <th scope="row"><?php echo $i++; ?></th>                       -->
								<td><?php echo $row["date"] ?></td>
	                            <td><?php echo $row["temperature"] ?></td>
	                            <td><?php echo $row["humidity"] ?></td>
	                            
	                        </tr>
	                    <?php } 
	                        mysqli_close($conn);
	                    ?>
						
					</tbody>
				</table>
				<nav style="margin-top: 20px;margin-bottom: 20px" aria-label="..." >
                  <ul class="pagination">
                    <?php include ( "./paging.php"); 
                        paging($page, $pos_per_page);
                    ?>
                  </ul>
                </nav>
				
			</div>
			<div class="col-sm-6" style="text-align: center;">
				<!-- <form method="POST" action="php/downloadtable.php">
					<input type="submit" name="export" class="btn btn-success" value="Export & Download" />
				</form>   -->
				<br />
				<table class="data-table">
				<!-- <table class="table table-striped"> -->
					<thead>
						<tr>
							<th>DATE</th>
							<th>MQ2</th>
							
						</tr>
					</thead>
					<tbody>
						<?php   
	                        $i = 1;
	                        include("php/connectSQL.php");
	                        
	                        if (isset($_GET['page'])) {
	                            $page  = $_GET['page'];
	                        } else {
	                            $page  = 1;
	                        }
	                        $pos_per_page  = 12; 
	                        $offset  = ( $page  - 1)  *  $pos_per_page;

	                        $sql = "SELECT * FROM `mq2sensor` ORDER BY `date` DESC Limit $offset,  $pos_per_page";
	                        $result = mysqli_query($conn, $sql);
	                        while($row = mysqli_fetch_array($result)) {
	                    ?>
	                        <tr>	                                              
								<td><?php echo $row["date"] ?></td>
	                            <td><?php echo $row["mq2"] ?></td>	                           
	                        </tr>
	                    <?php } 
	                        mysqli_close($conn);
	                    ?>
						
					</tbody>
				</table>
				<nav style="margin-top: 20px;margin-bottom: 20px; text-align: center !important;" aria-label="..." >
                  <ul class="pagination" style="text-align: center;">
                    <?php //include ( "./paging.php"); 
                        paging($page, $pos_per_page);
                    ?>
                  </ul>
                </nav>
				
			</div>
		</div>
		<br><br>
		
		
	    
	</div>

<script>
   
    document.getElementById('showDataGraph').onclick = function () {
      window.location = 'data.php';
  	}
  	document.getElementById('refresh_data').onclick = function () {
      window.location.reload();
   	}
</script>
<?php require_once("includes/footer.php"); ?> 