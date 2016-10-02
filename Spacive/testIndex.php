<!-- <!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>

<form id="myForm">
  <input type="submit" name="Submit">
</form>

<table id="Space" border='1'>
   <tbody>
    <tr>
        <td>Name</td>
         <td>Location</td>
    </tr>
    </tbody>
</table>

<script src="website/js/jquery.js"></script>


<script>
  $("#myForm").submit(function(e) {

    var url = "API/v1/co-space.php/getSpaces"; // the script where you handle the form input.

    $.ajax({
           type: "POST",
           url: url,
           dataType : 'json', // data type
           data: "", // serializes the form's elements.
           success: function(data)
           {              
              var table = document.getElementById('Space');
              var row = table.insertRow(0);
              var cell1 = row.insertCell(0);
              var cell2 = row.insertCell(1);

              cell1.innerHTML = "NEW CELL1";
              cell2.innerHTML = "NEW CELL1";
           }  
         });

      e.preventDefault(); // avoid to execute the actual submit of the form.
  });
</script>
</body>
</html> -->



<?php
$headers =  'MIME-Version: 1.0' . "\r\n"; 
$headers .= 'From: Ahmed Fekry <ahmedfikry78@gmail.com>' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n"; 

mail('ahmedfikry@stud.fci-cu.edu.eg', 'test main', 'hello there', $headers);
?>