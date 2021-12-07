<html>
<head>
	<meta charset="utf-8">
	<title>Główna strona ELMng</title>
	<link rel="stylesheet" href="css.css">



</head>
<body>
	<header id="header">
		
	</header>
			<?php
				$level=0;
				$id=0;
				function clear_level()
				{
					global $level;
					$level=0;
				}
				function update_level()
				{
					global $level;
					$level++;
					
				}
				function clear_id()
				{
					global $id;
					$id=1;
				}
				function update_id()
				{
					global $id;
					$id++;
					
				}
				function queryProjects()
				{
					global $level;
					global $id;
					$polaczenie = mysqli_connect('localhost', 'root','','mngsitedb');
					$quarry = "SELECT * from projekty WHERE level=".$level." AND parent_id=".$id;
					$sql = mysqli_query($polaczenie, $quarry);
					$projects=array(array());					
					while($wiersz = mysqli_fetch_array($sql))
					{
					$linearray=[
					0=>$wiersz['id'],
					1=>$wiersz['parent_id'],
					2=>$wiersz['level'],
					3=>$wiersz['pos_col'],
					4=>$wiersz['pos_row'],
					];
						array_push($projects,$linearray);
					}
					$polaczenie -> close();
					return $projects;			
						
						
				}	
				function return_lvl_max()		
				{
					global $level;
					global $id;
					$polaczenie = mysqli_connect('localhost', 'root','','mngsitedb');
					$quarry = "SELECT max(pos_col)as max_c, max(pos_row) AS max_r from projekty WHERE level=".$level." AND parent_id=".$id;
					$sql = mysqli_query($polaczenie, $quarry);	
					$wiersz = mysqli_fetch_array($sql);
					$dims=[
						"cols" =>$wiersz['max_c'],
						"rows" =>$wiersz['max_r'],
						];		
						$polaczenie -> close();
					return $dims;
				}				
			?>	
			

	<nav>
		<div class="navdiv">
		</div>
		<div class="navdiv">
		Nowy
		</div>
		<div class="navdiv">
		Projekty
		</div>
		<div class="navdiv">
		Repozytoria
		</div>
		<div class="navdiv">
		</div>
		<div class="navdiv">
		</div>
	</nav>
	
	<div class="underniv">
	</div>
	<section class="flexbox">
	<div class="header">
	</div>
		<main>
			<div class="container" id="container" style="color:white;">
			
				<script type="text/javascript">
					function set_id(id)
					{
						<?php clear_id();?>
						for(;id>0;id--)
						{
						<?php update_id();?>
						}
					}
					function render_projects()
					{
						
						var projects=<?php echo json_encode (queryProjects());?>;
					}
					
					
					function generate_grid()
					{						
						//clear container
						document.getElementById("container").innerHTML="";
						//quary dimentions
						var maxcol=(<?php $dims=return_lvl_max(); echo json_encode($dims['cols']);?>)*1;
						var maxrow=(<?php echo json_encode($dims['rows']);?>)*1;
						//generate frames
						for(var i=0;i<=maxcol+1;i++)
						{
							for(var j=0;j<=maxrow+1;j++)
							{
								var string="<div class='frame' id='"+i+"X"+j+"' style='grid-column-start:"+i+"; grid-column-end:"+(i+1)+"; grid-row-start:"+j+"; grid-row-end:"+(j+1)+";'></div>";
								
								document.getElementById("container").innerHTML+=string;
							}
						}
					}
				
				
					window.onload = function() {
						generate_grid(0);
						

					};
				</script>
				
				
			</div>
		</main>
	</section>


</body>
</html>