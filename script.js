var inputId = document.getElementById("cId");
					var isAdding=0;
					
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
							isAdding=1;
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
							document.getElementById(col+"X"+row).style.background="";
							document.getElementById("col").value=col;
							document.getElementById("row").value=row;
							document.getElementById("parent").value=<?php  echo json_encode( $id);?>;
							
							switch_mode();
							new_form();
						}
					}
					
					function submit_F(newParent)
					{
						inputId.value=newParent;
						document.getElementById("changeForm").submit(); 
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

					function closeForm() {
						document.getElementById("monit").style.display = "none";
					}
					
					function submitForm()
					{
						document.getElementById("monit-form").submit(); 
						closeForm();
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

