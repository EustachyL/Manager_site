<?php
$parent=$_POST['parent'];
$col=$_POST['col'];
$row=$_POST['row'];
$Title=$_POST['Title'];
$line1=$_POST['line1'];
$line2=$_POST['line2'];

$polaczenie = mysqli_connect('localhost', 'root','','mngsitedb');
$quarry = "INSERT INTO projekty(parent_id,pos_col,pos_row,Title,line1,line2) VALUES('$parent','$col','$row','$Title','$line1','$line2')" ;
$sql = mysqli_query($polaczenie, $quarry);
@$polaczenie->close();		
if (URL::previous() === URL::route('companies.index')) { 
   return redirect()->back();
} else {
   return redirect()->route('someroute');
}
?>