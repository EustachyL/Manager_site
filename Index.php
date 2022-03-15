<html>
<head>
	<meta charset="utf-8">
	<title>Główna strona ELMng</title>
	<link rel="stylesheet" href="css.css">



</head>
<body>
	<?php

				if (isset($_POST['cId']))
				{	
				$id=$_POST['cId'];	
				}else
				{
					$id=0;
				}
				if(isset($_POST['quaryMode']))
				{
					if (isset($_POST['parent']) && $_POST['quaryMode']==1)
						insert_project();
					
					if (isset($_POST['dId']) && $_POST['quaryMode']==2)
						delete_project();
					if ((isset($_POST['uId']) || isset($_POST['sId'])|| isset($_POST['MfId']) || isset($_POST['pId'])) && $_POST['quaryMode']>=3)
						update_project($_POST['quaryMode']);
				}	
				
				function find_parent($fId)
				{
					$polaczenie = mysqli_connect('localhost', 'root','','mngsitedb');
					$quarry = "SELECT parent_id from projekty WHERE id='$fId'";
					$sql = mysqli_query($polaczenie, $quarry);
					$wiersz = mysqli_fetch_array($sql);
					global $id;
					$id=$wiersz['parent_id'];
					@$polaczenie->close();
				}

				function insert_project()
				{
										
					$parent=$_POST['parent'];
					$col=$_POST['col'];
					$row=$_POST['row'];
					$Title=$_POST['Title'];
					$line1=$_POST['line1'];
					$line2=$_POST['line2'];
					$date=$_POST['date'];
					$state=$_POST['state'];
					$priority=$_POST['priority'];
					global $id;
					$id=$parent;
					$polaczenie = mysqli_connect('localhost', 'root','','mngsitedb');
					$quarry = "SELECT count(*) as liczba from projekty WHERE parent_id=".$parent." AND pos_col=".$col." AND pos_row=".$row;
					$sql = mysqli_query($polaczenie, $quarry);
					while($wiersz = mysqli_fetch_array($sql))
					{
						if($wiersz['liczba']*1>0)
						return;
					}
					$quarry = "INSERT INTO projekty(parent_id,pos_col,pos_row,Title,line1,line2,creationDate,dueDate,state,priority) VALUES('$parent','$col','$row','$Title','$line1','$line2',CURDATE(),'$date','$state','$priority')" ;
					$sql = mysqli_query($polaczenie, $quarry);
					@$polaczenie->close();					
					
					
				}
				function delete_project()
				{
										
					$dId=$_POST['dId'];
					$polaczenie = mysqli_connect('localhost', 'root','','mngsitedb');
					$quarry = "DELETE FROM projekty WHERE id=".$dId."" ;
					$sql = mysqli_query($polaczenie, $quarry);
					@$polaczenie->close();		
					find_parent($dId);
					
				}
				function update_project($mode)
				{
					if($mode==3)
					{
					$uId=$_POST['uId'];
					}
					if($mode==5)
					{
					$uId=$_POST['MfId'];
					}
					
					if($mode==4)
					{
					$uId=$_POST['sId'];
					}
					if($mode==6)
					{
					$uId=$_POST['pId'];
					}
					$polaczenie = mysqli_connect('localhost', 'root','','mngsitedb');
					$quarry = "SELECT * from projekty WHERE id=".$uId;
					$sql = mysqli_query($polaczenie, $quarry);
										
					$wiersz = mysqli_fetch_array($sql);
					
					$upos_col=$wiersz['pos_col'];
					$upos_row=$wiersz['pos_row'];
					$uTitle=$wiersz['Title'];
					$uline1=$wiersz['line1'];
					$uline2=$wiersz['line2'];
					$udueDate=$wiersz['dueDate'];
					$ustate=$wiersz['state'];
					$upriority=$wiersz['priority'];
					$unote=$wiersz['note'];
					$uisNote=$wiersz['isNote'];
					$uisArrowOnly=$wiersz['isArrowOnly'];
					$uarrowDirection=$wiersz['arrowDirection'];
					if($mode==3)
					{
						if(isset($_POST['arrowD']) && $_POST['arrowD']>=0)
						{
							$uarrowDirection=$_POST['arrowD'];
						}
					}
					if($mode==4)
					{
						if(isset($_POST['cstate']) && $_POST['cstate']>=0)
						{
							$ustate=$_POST['cstate'];
						}
					}
					if($mode==5)
					{
						$uTitle=$_POST['MfTitle'];
						$uline1=$_POST['Mfline1'];
						$uline2=$_POST['Mfline2'];
						$udueDate=$_POST['Mfdate'];
						$ustate=$_POST['Mfstate'];
						$upriority=$_POST['Mfpriority'];
						$unote=$_POST['Mfnote'];
					}
					if($mode==6)
					{
					$upos_col=$_POST['pcol'];
					$upos_row=$_POST['prow'];
					}
					
					$quarry = "UPDATE projekty SET pos_col='$upos_col', pos_row='$upos_row', Title='$uTitle', line1='$uline1', line2='$uline2', dueDate='$udueDate', state='$ustate', priority='$upriority', note='$unote', isNote='$uisNote', isArrowOnly='$uisArrowOnly', arrowDirection='$uarrowDirection' WHERE id='$uId' ";
					
					$sql = mysqli_query($polaczenie, $quarry);
								
					
					
					@$polaczenie->close();		
					find_parent($uId);			
					
					
				}

				function queryProjects()
				{
					
					global $id;
					$polaczenie = mysqli_connect('localhost', 'root','','mngsitedb');
					$quarry = "SELECT * from projekty WHERE parent_id=".$id;
					$sql = mysqli_query($polaczenie, $quarry);
					$projects=array();	
										
					while($wiersz = mysqli_fetch_array($sql))
					{
					$linearray=[
					0=>$wiersz['id'],
					1=>$wiersz['parent_id'],
					3=>$wiersz['pos_col'],
					4=>$wiersz['pos_row'],
					5=>$wiersz['Title'],
					6=>$wiersz['line1'],
					7=>$wiersz['line2'],
					8=>$wiersz['creationDate'],
					9=>$wiersz['dueDate'],
					10=>$wiersz['state'],
					11=>$wiersz['priority'],
					12=>$wiersz['note'],
					13=>$wiersz['isNote'],
					14=>$wiersz['isArrowOnly'],
					15=>$wiersz['arrowDirection'],
					];
						array_push($projects,$linearray);
					}
					$polaczenie -> close();
					return $projects;		
						
						
				}	
				function queryDetails()
				{					
					$polaczenie = mysqli_connect('localhost', 'root','','mngsitedb');
					$quarry = "SELECT * FROM details";
					$sql = mysqli_query($polaczenie, $quarry);
					$details=array();	
										
					while($wiersz = mysqli_fetch_array($sql))
					{
					$linearray=[
					0=>$wiersz['id'],
					1=>$wiersz['children'],
					2=>$wiersz['waiting'],
					3=>$wiersz['in_progress'],
					4=>$wiersz['finished'],
					5=>$wiersz['canceled'],
					6=>$wiersz['suspended'],
					];
						array_push($details,$linearray);
					}
					$polaczenie -> close();	
					return $details;	
						
						
				}
				function queryChildren()
				{					
					$polaczenie = mysqli_connect('localhost', 'root','','mngsitedb');
					$quarry = "SELECT * FROM potomkowie";
					$sql = mysqli_query($polaczenie, $quarry);
					$Children=array();	
										
					while($wiersz = mysqli_fetch_array($sql))
					{
					$linearray=[
					0=>$wiersz['parent_id'],
					1=>$wiersz['id'],
					];
						array_push($Children,$linearray);
					}
					$polaczenie -> close();	
					return $Children;	
						
						
				}
				function return_lvl_max()		
				{
					global $id;
					$polaczenie = mysqli_connect('localhost', 'root','','mngsitedb');
					$quarry = "SELECT id, max(pos_col)as max_c, max(pos_row) AS max_r from projekty WHERE parent_id=".$id;
					$sql = mysqli_query($polaczenie, $quarry);	
					$wiersz = mysqli_fetch_array($sql);
					$dims=[
						"cols" =>$wiersz['max_c'],
						"rows" =>$wiersz['max_r'],
						];		
						$polaczenie -> close();
					return $dims;
				}	
				function main_name()		
				{
					global $id;
					if($id==0)
						return "Projekty";
					$polaczenie = mysqli_connect('localhost', 'root','','mngsitedb');
					$quarry = "SELECT Title from projekty WHERE id=".$id;
					$sql = mysqli_query($polaczenie, $quarry);	
					$wiersz = mysqli_fetch_array($sql);
					return $wiersz['Title'];
				}				
			?>	
	<header id="header">
	
		<div  id="fieldMonit">
			
				Wybierz pole
				<button type="button" class="btn" onclick="switch_mode()">Cofnij</button>
			
		</div> 
		<div class="monit" id="monit">
			<form method="post" id="monit-form" class="monit-form">
				<h1>New Project</h1>
				<input hidden type="number"  id="parent" name="parent" value="0">
				<input hidden type="number"  id="col" name="col" value="0">
				<input hidden type="number"  id="row" name="row" value="0">
				<input hidden type="number"  id="quaryMode" name="quaryMode" value="1">
				
				<label for="Title"><b>Title</b></label>
				<input type="text" placeholder="Title" name="Title" required>
				<br>				
				<label for="line1"><b>Linia 1</b></label>
				<textarea id="line1" name="line1" rows="1" cols="30"></textarea>
				<br>
				<label for="line2"><b>Linia 2</b></label>
				<input type="text" placeholder="tekst" name="line2" >
				<br>
				<label for="date"><b>Termin do wykonania</b></label>
				<input type="date" name="date" required>
				<br>
				<label for="state"><b>Status po utworzeniu</b></label>
				<select name="state" id="state">
					<option value=0>oczekujący </option>
					<option value=1>w trakcie </option>
					<option value=2>zakończony </option>
					<option value=3>anulowany </option>
					<option value=4>wstrzymany </option>
				</select>
				<br>
				<label for="priority" required><b>Piorytet</b></label>
				<input type="number" value=1 name="priority" >
				<br>
			
				<button type="button" class="btn" onclick="submitForm('monit')" >Create</button>
				<button type="button" class="btn" onclick="closeForm('monit')">Close</button>
			</form>
		</div> 
	
		<div class="monit" id="ChangeMonit">
			<form method="post" id="ChangeMonit-form" class="monit-form">
				<h1 id="MFT"></h1>
				<input class="hiddenInput" hidden id="MfId" name="MfId" type="number" value="-1">
			<input class="hiddenInput"  hidden id="MfcId" name="cId" type="number" value="-1">
				<input hidden type="number"  id="quaryMode" name="quaryMode" value="5">
				
				<label for="Title"><b>Title</b></label>
				<input type="text" placeholder="Title" name="MfTitle" id="MfTitle" required>
				<br>				
				<label for="Mfline1"><b>Linia 1</b></label>
				<textarea id="Mfline1" name="Mfline1" rows="1" cols="30"></textarea>
				<br>
				<label for="line2"><b>Linia 2</b></label>
				<input type="text" placeholder="tekst" name="Mfline2" id="Mfline2" >
				<br>
				<label for="date"><b>Termin do wykonania</b></label>
				<input type="date" name="Mfdate" id="Mfdate" required>
				<br>
				<label for="state"><b>Status po utworzeniu</b></label>
				<select name="Mfstate" id="Mfstate">
					<option value=0>oczekujący </option>
					<option value=1>w trakcie </option>
					<option value=2>zakończony </option>
					<option value=3>anulowany </option>
					<option value=4>wstrzymany </option>
				</select>
				<br>
				<label for="priority" ><b>Piorytet</b></label>
				<input type="number" value=1 name="Mfpriority" id="Mfpriority" required>
				<br>
				<label for="Mfnote" ><b>Note</b></label>
				<br>
				<textarea id="Mfnote" name="Mfnote" rows="6" cols="30"></textarea>
				<br>
			
				<button type="button" class="btn" onclick="submitForm('ChangeMonit')" >modify</button>
				<button type="button" class="btn" onclick="closeForm('ChangeMonit')">cancel</button>
			</form>
		</div> 
		<div class="details_div" id="details_div">
			<h1 id="details_title"></h1> 
			<hr style="margin-top:-5%;">
			<div> 
				<div id="details_cdate">Data utworzenia <p class="details_date" id="details_cdateIn"></p></div> 
				<div id="details_edate">Termin zakończenia <p class="details_date" id="details_edateIn"></p></div> 
			</div>
			<div id="details_date_bar"> </div> 
			<div class="details_children">
				<h3 class="details_subtitle">Elementy potomne</h3>
				<div style="width:45%; float:left; padding-left:5%;">
					Łącznie: <br> <span style="color:grey;">Oczekujące: </span><br> <span style="color:blue;">W trakcie: </span><br>
					<span style="color:green;">Zakończone: </span><br> <span style="color:yellow;">Wstrzymane: </span><br>
					<span style="color:red;">Anulowane:</span>
				</div>
				<div id="details_children_stats" style="width:45%; float:right; padding-right:5%;">
				
				</div>
				<h3 class="details_subtitle" style="margin-top:30%; margin-bottom:0%;">Nota:</h3>
				<div id="details_note" class="details_note">
				
				</div>
			</div>
		
		</div>

	</header>
			
			

	<nav>
		<div class="navdiv">
		</div>
		<div class="navdiv" onclick="switch_mode()">
		Nowy
		</div>
		<div id="navHome" class="navdiv" onclick="home()">
		Projekty
		</div>
		<div class="navdiv">
		
		</div>
		<div class="navdiv">
		</div>
		<div class="navdiv">
		</div>
	</nav>
	
	<div class="underniv">
	<p style="position:relative; bottom:18px;">Version: 1.1;26.02.2022</p>
	</div>
	<section class="flexbox">
	<div class="header">
		<div class="header_title"><h2 id="mainName"><?php echo(main_name());?></h2></div>
		<form method="post" class="hiddenForm" id="changeForm">	
			<input class="hiddenInput" id="cId" name="cId" type="number" value="-1">
		</form>
		<form method="post" class="hiddenForm" id="deleteForm">	
			<input class="hiddenInput" id="quaryMode" name="quaryMode" type="number" value="2"> 
			<input class="hiddenInput" id="dId" name="dId" type="number">
		</form>
		<form method="post" id="arr-form" class="hiddenForm">
			<input class="hiddenInput" id="quaryMode" name="quaryMode" type="number" value="3"> 
			<input class="hiddenInput" hidden type="number"  id="uId" name="uId" value="-1">
			<input class="hiddenInput" hidden type="number"  id="arrowD" name="arrowD" value="-1">
		</form>
		<form method="post" id="state-form" class="hiddenForm">
			<input class="hiddenInput" id="quaryMode" name="quaryMode" type="number" value="4"> 
			<input class="hiddenInput" hidden type="number"  id="sId" name="sId" value="-1">
			<input class="hiddenInput" hidden type="number"  id="cstate" name="cstate" value="-1">
		</form>
		<form method="post" id="position-form" class="hiddenForm">
			<input class="hiddenInput" id="quaryMode" name="quaryMode" type="number" value="6"> 
			<input class="hiddenInput" hidden type="number"  id="pId" name="pId" value="-1">
			<input class="hiddenInput" hidden type="number"  id="pcol" name="pcol" value="-1">
			<input class="hiddenInput" hidden type="number"  id="prow" name="prow" value="-1">
		</form>
		
	</div>
		<main>
			<div id="back" onclick="getBack()">
				. . .
			</div>
			<div class="container" id="container" style="color:white;">
				
				

				<script type="text/javascript">
					var inputId = document.getElementById("cId");
					var isAdding=0;
					var positionMode=0;
					function compare_dates(date)
					{
						var curDate = new Date();
						var comDate =new Date(date);
						if(curDate>comDate)
							return 1;
						else
							return 0;
					}
					function switch_mode()
					{
						if(isAdding==1)
						{
							document.getElementById("fieldMonit").style.display = "none";
							
							isAdding=0;
						}
						else
						{
							document.getElementById("fieldMonit").style.display = "block";
							setTimeout(function(){isAdding=1;},100);
							
						}
						
					}
					
					function on_frame(col,row)
					{
						if(isAdding==1)
						{
							document.getElementById(col+"X"+row).style.background="#8d8cc2";
						}
					}
					
					function off_frame(col,row)
					{
						if(isAdding==1)
						{
							document.getElementById(col+"X"+row).style.background="";
						}
					}
					
					function pick_position(col,row)
					{
						if(isAdding==1)
						{
							if(positionMode==1)
							{
								document.getElementById(col+"X"+row).style.background="";
								document.getElementById("pcol").value=col;
								document.getElementById("prow").value=row;	
								switch_mode();	
								positionMode=0;
								document.getElementById("position-form").submit(); 
							}else{
								document.getElementById(col+"X"+row).style.background="";
								document.getElementById("col").value=col;
								document.getElementById("row").value=row;
								document.getElementById("parent").value=<?php  echo json_encode( $id);?>;
								
								switch_mode();
								new_form();
							}
						}
					}
					
					function submit_F(newParent)
					{
						inputId.value=newParent;
						document.getElementById("changeForm").submit(); 
					}
					function getBack()
					{
						
						var cParent=<?php  echo json_encode( $id);?>;
						var children=<?php echo json_encode (queryChildren());?>;
						for(var i=0;i<children.length;i++)
						{
							if(children[i][1]==cParent)
								submit_F(children[i][0]);
						}
						
					}
					
					function home()
					{
						inputId.value=0;
						document.getElementById("changeForm").submit(); 
					}
					
					
					function new_form()
					{
						document.getElementById("monit").style.display = "block";
					}

					function closeForm(monit) {
						document.getElementById(monit).style.display = "none";
					}
					
					function submitForm(monit)
					{
						document.getElementById(monit+"-form").submit(); 
						closeForm(monit);
					}
					function deleteTile(id)
					{
						document.getElementById("dId").value=id; 
						document.getElementById("deleteForm").submit(); 
					}
					
					function arrowMenu(mId,op)
					{
						document.getElementById(mId).style.opacity = op;
						document.getElementById(mId+"C").style.opacity = Math.abs(op-1);
					}
					
					function updateArrows(id,arrowD)
					{
						document.getElementById("uId").value=id; 
						document.getElementById("arrowD").value=arrowD; 
						document.getElementById("arr-form").submit(); 
					}
					
					function change_status(id,state)
					{
						document.getElementById("sId").value=id; 
						document.getElementById("cstate").value=state; 
						document.getElementById("state-form").submit(); 
					}
					
					function modify_project(i)
					{
						var projects=<?php echo json_encode (queryProjects());?>;
						document.getElementById("MfId").value=projects[i][0]; 
						document.getElementById("MfcId").value=projects[i][1]; 
						document.getElementById("MfTitle").value=projects[i][5]; 
						document.getElementById("MFT").innerHTML="Modyfikuj: "+projects[i][5]; 
						document.getElementById("Mfline1").value=projects[i][6]; 
						document.getElementById("Mfline2").value=projects[i][7]; 
						if(projects[i][9]!="brak")
							document.getElementById("Mfdate").value=projects[i][9]; 
						else document.getElementById("Mfdate").value="0000-00-00";
						document.getElementById("Mfstate").value=projects[i][10]; 
						document.getElementById("Mfpriority").value=projects[i][11]; 
						document.getElementById("Mfnote").value=projects[i][12]; 
						document.getElementById("ChangeMonit").style.display = "block";
					}
					function move_project(id)
					{
						document.getElementById("pId").value=id; 
						positionMode=1;
						switch_mode();
					}
					function show_details(id)
					{
						var projects=<?php echo json_encode (queryProjects());?>;
						var i=0;
						for(;i<projects.length;i++)
						{
							if(projects[i][0]==id)
								break;
						}
						function calculate_children(idt, type, mode=0)
						{
						var details=<?php echo json_encode (queryDetails());?>;
						var children=<?php echo json_encode (queryChildren());?>;
							var total=0;
							
							for(var j=0;j<details.length;j++)
							{
								if(details[j][0]==idt)
									total+=details[j][type+1]*1;
							}
							if(mode==0)
							for(var j=0;j<children.length;j++)
							{
								if(children[j][0]==idt)
									total+=calculate_children(children[j][1], type)*1;
							}
							return total;
						}
						if(projects[i][9]=="NULL" || projects[i][9]=="0000-00-00")
							projects[i][9]="brak";
						
						document.getElementById("details_title").innerHTML=projects[i][5];
						
						
						document.getElementById("details_cdateIn").innerHTML=projects[i][8];
						document.getElementById("details_edateIn").innerHTML=projects[i][9];
						var curDate = new Date();
						var cDate =new Date(projects[i][8]);
						var eDate =new Date(projects[i][9]);
						curDate=curDate.getTime();
						cDate=cDate.getTime();
						eDate=eDate.getTime();
						var daysPassed=((curDate-cDate)/ (1000 * 3600 * 24)).toFixed(1);
						var daysAhead=((eDate-curDate)/ (1000 * 3600 * 24)).toFixed(1);
						
							
						var dBar="<div style='background:grey; height:100%; width:"+((curDate-cDate)/(eDate-cDate)*100).toFixed(0)+"%;'>"+daysPassed+"</div> <div style='background:green; height:100%;  width:"+((eDate-curDate)/(eDate-cDate)*100).toFixed(0)+"%;'>"+daysAhead+"</div>";
						if(daysAhead=="NaN")
							dBar="<div style='background:grey; height:100%; text-align:center;  width:100%;'>"+daysPassed+" dni od utworzenia</div>";
						if(projects[i][10]*1==2)
							dBar="<div style='background:green; height:100%; text-align:center;  width:100%;'>Ukończony</div>";
						else if(daysAhead<0)
							dBar="<div style='background:red; height:100%; text-align:center;  width:100%;'>"+daysAhead*-1+" dni po terminie</div>";
						document.getElementById("details_date_bar").innerHTML=dBar;
						var total=calculate_children(id,0);
						var spacing="&emsp; &emsp;&emsp; &emsp;"
						var innertext=total+spacing+'  <br> <span style="color:grey;">'+calculate_children(id,1)+spacing+(calculate_children(id,1)/total*100).toFixed(0)+'%</span><br> <span style="color:blue;">'+calculate_children(id,2)+spacing+(calculate_children(id,2)/total*100).toFixed(0)+'%</span><br>';
						innertext+='<span style="color:green;">'+calculate_children(id,3)+spacing+(calculate_children(id,3)/total*100).toFixed(0)+'%</span><br> <span style="color:yellow;">'+calculate_children(id,4)+spacing+(calculate_children(id,4)/total*100).toFixed(0)+'%</span><br>';
						innertext+='<span style="color:red;">'+calculate_children(id,5)+spacing+(calculate_children(id,5)/total*100).toFixed(0)+'%</span>';
						document.getElementById("details_children_stats").innerHTML=innertext;
						
						document.getElementById("details_note").innerHTML=projects[i][12];
						
						document.getElementById("details_div").style.display="block";
					}
					function hide_details()
					{
						document.getElementById("details_div").style.display="none";
					}
					
					function render_projects()
					{
						
						var projects=<?php echo json_encode (queryProjects());?>;
						for(var i=0;i<projects.length;i++)
						{
							var dateColor= "white;"
							if(projects[i][9]=="NULL" || projects[i][9]=="0000-00-00")
							projects[i][9]="brak";
							else if(compare_dates(projects[i][9]) && projects[i][10]*1<2)
								dateColor="red";
							
								
							var state;
							var kolor;
							switch(projects[i][10]*1)
							{
								case 0:
								state="oczekujący";
								kolor="grey";
								break;
								
								case 1:
								state="w trakcie";
								kolor="blue";
								break;
								
								case 2:
								state="zakończony";
								kolor="green";
								break;
								
								case 3:
								state="anulowany";
								kolor="red";
								break;
								
								case 4:
								state="wstrzymany";
								kolor="yellow";
								break;
							}

							if(projects[i][14]==0)
							{
								if(projects[i][13]==0)
								{
									var string="<div class='tile' onmouseover='show_details("+projects[i][0]+")' onmouseout='hide_details()' onclick='submit_F("+projects[i][0]+")' id="+projects[i][0]+" > <div class='titleDiv'>"+projects[i][5]+"</div> <div class='statusDiv'><div style='width:130px; float:left; color:"+kolor+";'>"+state+"</div> <div style='width:130px; float:right; color:"+dateColor+";'>termin: "+projects[i][9]+"</div></div> <div class='infoDiv'><p class='infoTitle'>"+projects[i][6]+"</p><p class='infoContent'> "+projects[i][7]+"</p></div>  </div></div>";
									string=string+"<div class='menuDiv'><button  class='tileBtn' onclick=change_status("+projects[i][0]+",1) style='margin-left:130px;'>&#10711;</button><button onclick=change_status("+projects[i][0]+",4)  class='tileBtn'>&#10707;</button><button onclick=change_status("+projects[i][0]+",2)  class='tileBtn'>✓</button><button alt='usuń strzałki'  class='tileBtn' onclick=updateArrows("+projects[i][0]+",0)>⥇</button><button onclick='move_project("+projects[i][0]+")' class='tileBtn'>◻</button><button onclick=modify_project("+i+")   class='tileBtn'>✎</button><button class='tileBtn' onclick='deleteTile("+projects[i][0]+")'>✖</button></div>";
									document.getElementById(projects[i][3]+"X"+projects[i][4]).innerHTML="";
									document.getElementById(projects[i][3]+"X"+projects[i][4]).innerHTML=string;
									var empty="<div class='empDiv'></div>";
									amid="arrMenu"+projects[i][0];
									
										var la=0, ra=0, da=0;
										var string="<div id='"+amid+"' onmouseout=arrowMenu('"+amid+"',0) class='arrMenu' >";
										if(projects[i][15]%2!=1)
										{
											if(projects[i][3]!=1)
											 string=string+"<div id='"+amid+"L' class='arrBtn' onclick=updateArrows("+projects[i][0]+","+projects[i][15]+"*1+1) style='background-image: url(left.png);'></div>";
											else
												string=string+empty;
										}else
										{
											la=1;
											 string=string+empty;
											projects[i][15]--;
										}
										if(projects[i][15]%4!=2)
										{
											
											 string=string+"<div id='"+amid+"R' class='arrBtn' onclick=updateArrows("+projects[i][0]+","+projects[i][15]+"*1+2) style='background-image: url(right.png); position:relative; left:20px;'></div>";
											
										}else
										{
											
											ra=1;
											 string=string+empty;
											projects[i][15]-=2;
										}
										if(projects[i][15]!=4)
										{
											 string=string+"<div id='"+amid+"D' class='arrBtn' onclick=updateArrows("+projects[i][0]+","+projects[i][15]+"*1+4) style='background-image: url(down.png); position:relative; top:20px; right:20px;'></div>";
										}else
										{
											da=1;
											 string=string+empty;
										}
										var string=string+"</div>";
										if(ra+la+da!=3 && !(projects[i][3]==1 &&  ra+da==2 ))
										{	
											 string=string+"<div id='"+amid+"C' class='arrCtr' onmouseover=arrowMenu('"+amid+"',1) style='background-image: url(plus.png);'></div>";
										}else
											string=string+empty;
										
										document.getElementById(projects[i][3]+"X"+projects[i][4]).innerHTML+=string;
										
										
										
										
										var arrows="<img class='leftArrow' style='opacity:"+la+";' src='arrow.png'> <img class='rightArrow' style='opacity:"+ra+";' src='arrow.png'> <img class='downArrow' style='opacity:"+da+";' src='arrow.png'>";
										document.getElementById(projects[i][3]+"X"+projects[i][4]).innerHTML+=arrows;
									
								}
							}
						}
						
					}
					
					
					function generate_grid()
					{						
						//clear container
						document.getElementById("container").innerHTML="";
						//quary dimentions
						var maxcol=(<?php $dims=return_lvl_max(); echo json_encode($dims['cols']);?>)*1;
						var maxrow=(<?php echo json_encode($dims['rows']);?>)*1;
						//generate frames
						for(var i=1;i<=maxcol+1;i++)
						{
							for(var j=1;j<=maxrow+1;j++)
							{
								var string="<div class='frame' onmouseover=on_frame("+i+","+j+") onmouseout=off_frame("+i+","+j+") onclick=pick_position("+i+","+j+")  id='"+i+"X"+j+"' style='grid-column-start:"+i+"; grid-column-end:"+(i+1)+"; grid-row-start:"+j+"; grid-row-end:"+(j+1)+";'></div>";
								
								document.getElementById("container").innerHTML+=string;
							}
						}
					}
				
				
					window.onload = function() {
						
						generate_grid();
						render_projects();
						hide_details();

					};
				</script>

				
			</div>
		</main>
	</section>


</body>
</html>