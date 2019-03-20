<html>
<head>
<title>Princess Park Admin</title>
<link rel="shortcut icon" href="images/logo.png">
        <!-- js -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
            <!-- css -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
<link rel="stylesheet" href="assets/custom/style.css">
<style>
body
{
 margin:0;
 padding:0;
 background-color:#f1f1f1;
}
.box
{
 width:1220px;
 padding:10px;
 background-color:#fff;
 border:1px solid #ccc;
 border-radius:5px;
 margin-top:50px;
}
</style>
</head>
<body>
  <?php include 'include/food_header.php'; ?>
<div class="container box">
<h1 align="center">Food Table</h1>
<div align="right">
 <button type="button" id="modal_button" class="btn btn-info">Add New Food</button>
 <!-- It will show Modal for Add Foods !-->
</div>
<br />
<div id="result" class="table-responsive"> <!-- Data will load under this tag!-->

</div>
</div>
</body>
</html>

<!-- This is food Modal. It will be use for Add Foods and Update Existing Records!-->
<div id="foodModal" class="modal fade">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
 <h4 class="modal-title">Add Foods</h4>
</div>
<div class="modal-body">
 <label>Food Name</label>
 <input type="text" name="name" id="name" class="form-control" />
 <br />
 <label>Category</label>
 <input type="text" name="categories" id="categories" class="form-control" />
 <br />
 <label>Price</label>
 <input type="text" name="price" id="price" class="form-control" />
 <br />
 <label>Description</label>
 <textarea name="discription" id="discription" rows="4" cols="75"></textarea>
</div>
<div class="modal-footer">
 <input type="hidden" name="id" id="id" />
 <input type="submit" name="action" id="action" class="btn btn-success" />
 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>

<script>
$(document).ready(function(){
fetchFood(); //This function will load all data on web page when page load
function fetchFood() // This function will fetch data from table and display under <div id="result">
{
var action = "Load";
$.ajax({
url : "action_Foods.php", //Request send to "action_Foods.php page"
method:"POST", //Using of Post method for send data
data:{action:action}, //action variable data has been send to server
success:function(data){
 $('#result').html(data); //It will display data under div tag with id result
}
});
}

//This JQuery code will Reset value of Modal item when modal will load for create new records
$('#modal_button').click(function(){
$('#foodModal').modal('show'); //It will load modal on web page
$('#name').val(''); //This will clear Modal food name textbox
$('#categories').val(''); //This will clear Modal categories textbox
$('#price').val(''); //This will clear Modal price textbox
$('#discription').val(''); //This will clear Modal description textbox
$('.modal-title').text("Add Foods"); //It will change Modal title to Add Foods
$('#action').val('Create'); //This will reset Button value ot Create
});

//This JQuery code is for Click on Modal action button for Add foods or Update existing records. This code will use for both Create and Update of data through modal
$('#action').click(function(){
var name = $('#name').val(); //This will clear Modal food name textbox
var categories = $('#categories').val(); //This will clear Modal categories textbox
var price = $('#price').val(); //This will clear Modal price textbox
var discription = $('#discription').val(); //This will clear Modal description textbox
var id = $('#id').val();  //Get the value of hidden field id
var action = $('#action').val();  //Get the value of Modal Action button and stored into action variable
if(name != '' && categories != '' && price != '' && discription != '') //This condition will check variables have some value
 {
$.ajax({
 url : "action_Foods.php",    //Request send to "action_Foods.php page"
 method:"POST",     //Using of Post method for send data
 data:{name:name, categories:categories, price:price, discription:discription, id:id, action:action}, //Send data to server
 success:function(data){
  alert(data);    //It will pop up which data it was received from server side
  $('#foodModal').modal('hide'); //It will hide food Modal from webpage.
  fetchFood();    // Fetch User function has been called and it will load data under divison tag with id result
 }
});
}
else
{
alert("All Fields are Required"); //If both or any one of the variable has no value them it will display this message
}
});

//This JQuery code is for Update food data. If we have click on any food row update button then this code will execute
$(document).on('click', '.update', function(){
var id = $(this).attr("id"); //This code will fetch any food id from attribute id with help of attr() JQuery method
var action = "Select";   //We have define action variable value is equal to select
$.ajax({
url:"action_Foods.php",   //Request send to "action_Foods.php page"
method:"POST",    //Using of Post method for send data
data:{id:id, action:action},//Send data to server
dataType:"json",   //Here we have define json data type, so server will send data in json format.
success:function(data){
 $('#foodModal').modal('show');   //It will display modal on webpage
 $('.modal-title').text("Update Records"); //This code will change this class text to Update records
 $('#action').val("Update");     //This code will change Button value to Update
 $('#id').val(id);     //It will define value of id variable to this food id hidden field
 $('#name').val(data.name);  //It will assign value to modal name texbox
 $('#categories').val(data.categories);  //It will assign value of modal categories textbox
 $('#price').val(data.price);  //It will assign value of modal price textbox
 $('#discription').val(data.discription);  //It will assign value of modal categories textbox
}
});
});

//This JQuery code is for Delete food data. If we have click on any food row delete button then this code will execute
$(document).on('click', '.delete', function(){
var id = $(this).attr("id"); //This code will fetch any food id from attribute id with help of attr() JQuery method
if(confirm("Are you sure you want to remove this data?")) //Confim Box if OK then
{
var action = "Delete"; //Define action variable value Delete
$.ajax({
 url:"action_Foods.php",    //Request send to "action_Foods.php page"
 method:"POST",     //Using of Post method for send data
 data:{id:id, action:action}, //Data send to server from ajax method
 success:function(data)
 {
  fetchFood();    // fetchFood() function has been called and it will load data under divison tag with id result
  alert(data);    //It will pop up which data it was received from server side
 }
})
}
else  //Confim Box if cancel then
{
return false; //No action will perform
}
});
});
</script>
