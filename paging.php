<?php function paging($page_, $posts_per_page_, $request_key='page') {
		$query_string = "";
		// $query_string = preg_replace("/&$request_key=\\d*|^$request_key=\\d*/", "", $query_string);

		include("php/connectSQL.php");
		$sql = "SELECT * FROM `monitor` ORDER BY `date` DESC";
		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) {
		    // output data of each row
		    // while($row = mysqli_fetch_assoc($result)) {
		    //     echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
		    // }
		    // while($row = mysqli_fetch_array($result)) {
		    //             printf("<tr><td> &nbsp;%s </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td></tr>", 
		    //                 $row["TimeStamp"], $row["Temperature"], $row["Humidity"], $row["Movement"], $row["Gas"], $row["KitchenLight"], $row["BathroomLight"], $row["GardenLight"], $row["TableLight"], $row["BedroomLight"]);
		    //         }
		    $num_posts = mysqli_num_rows($result);
		} else {
		    $num_posts = 0;
		}

		mysqli_close($conn);

		// // Create connection
		// $conn = mysqli_connect($server, $username, $pass, $dbname);
		// // Check connection
		// if (!$conn) {
		//     die("Connection failed: " . mysqli_connect_error());
		// }

		// $sql = "SELECT * FROM `templog` ORDER BY `TimeStamp` DESC";
		// $result = mysqli_query($conn, $sql);
		// //var_dump($result);

		// if ($result > 0) {
		//     $num_posts = mysql_num_rows($result);
		// } else {
		//     $num_posts = 0;
		// }

		// mysqli_close($conn);

		$num_pages = ceil($num_posts / $posts_per_page_);
		if ($num_pages == 0) $num_pages = 1;

		$start_page = $page_ - 2;
		$end_page = $page_ + 2;
		if ($start_page < 1) $end_page += 1 - $start_page;
		if ($end_page > $num_pages) {
			$start_page -= $end_page - $num_pages;
			$end_page = $num_pages;
		}

		if ($start_page < 1) $start_page = 1;
		if ($page_ == 1) { 
?>

	<li class="page-item disabled"><a class="page-link">&laquo;&laquo;</a></li>
	<li class="page-item disabled"><a class="page-link page-separator-left">&laquo;</a></li>

<?php 	} else { ?>

	<li class="page-item"><a href="?<?php echo $query_string; ?>&<?php echo $request_key ?>=1" class="page-link">&laquo;&laquo;</a></li>
	<li class="page-item"><a href="?<?php echo $query_string; ?>&<?php echo $request_key ?>=<?php echo $page_-1; ?>" class="page-link page-separator-left">&laquo;</a></li>

<?php	}
		
		for ($i=$start_page; $i<=$end_page; $i++) { ?>
		
			<li class="page-item <?php if ($i==$page_) echo "active"; ?>"><a <?php if ($i!=$page_) echo 'href="?'.$request_key.'='.$i.'"'; ?> class="page-link <?php if ($i==$page_) echo "active"; ?>"><?php echo $i; ?><?php if ($i==$page_) echo '<span class="sr-only">(current)</span>'; ?></a></li>

<?php	} 

		if ($page_ == $num_pages) { ?>

	<li class="page-item disabled"><a class="page-link page-separator-right">&raquo;</a></li>
	<li class="page-item disabled"><a class="page-link">&raquo;&raquo;</a></li>

<?php 	} else { ?>

	<li class="page-item"><a href="?<?php echo $query_string; ?>&<?php echo $request_key ?>=<?php echo $page_+1; ?>" class="page-link page-separator-right">&raquo;</a></li>
	<li class="page-item"><a href="?<?php echo $query_string; ?>&<?php echo $request_key ?>=<?php echo $end_page; ?>" class="page-link">&raquo;&raquo;</a></li>

<?php 	}
} 

