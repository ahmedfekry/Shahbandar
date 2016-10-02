<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>


		<!-- names =  $r->space->name;
        $governorate = $r->space->governorate;
        $location = $r->space->location;
        $detailed_address =  $r->space->detailed_address;
        $phone_number = $r->space->phone_number;
        $capacity_per_room = $r->space->capacity_per_room;
 -->
<form  id="myForm">
    Name: <input type="text" name="name"><br>
    governorate: <input type="text" name="governorate"><br>
	location: <input type="text" name="location"><br>
	detailed_address: <input type="text" name="detailed_address"><br>
	phone_number: <input type="text" name="phone_number"><br>
	capacity_per_room: <input type="number" name="capacity_per_room"><br>
<!-- 
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload"><br> -->
    <input type="submit" value="Submit" name="submit">
</form>

<script src="js/jquery.js"></script>

<script type="text/javascript">
    $("#myForm").submit(function(e) {

    var name = $("[name='name']").val();
    var governorate = $("[name='governorate']").val();
    var location = $("[name='location']").val();
    var detailed_address = $("[name='detailed_address']").val();
	var phone_number = $("[name='phone_number']").val();
	var capacity_per_room = $("[name='capacity_per_room']").val();

    // alert(name);
	var postObject = new Object();
	postObject.name = name;
	postObject.governorate = governorate;
	postObject.location = location;
	postObject.detailed_address = detailed_address;
	postObject.phone_number = phone_number;
	postObject.capacity_per_room = capacity_per_room;

    var url = "API/v1/co-space.php/create"; // the script where you handle the form input.

    $.ajax({
           type: "POST",
           url: url,
           dataType : 'json', // data type
           data: JSON.stringify({space: postObject}), // serializes the form's elements.
           success: function(data)
           {
         		
           }
         });

    	e.preventDefault(); // avoid to execute the actual submit of the form.
	});
</script>
</body>
</html>