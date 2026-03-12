

<!DOCTYPE html>
<html>
<head>

<title>Emergency Report Form</title>

<style>

body{
font-family:Arial;
text-align:center;
}

textarea{
width:300px;
height:100px;
}

button{
padding:10px 20px;
margin:10px;
}

</style>

</head>

<body>

<h2>Emergency Report Form</h2>

<p id="type"></p>

<textarea placeholder="Write comment..."></textarea>
<br><br>

<input type="file">

<br><br>

<button onclick="sendSMS()">Send SMS</button>
<button onclick="sendEmail()">Send Email</button>

<br><br>

<button onclick="sendReport()">SEND</button>

<script>

document.getElementById("type").innerHTML =
"Emergency Type: " + localStorage.getItem("emergencyType");

function sendSMS(){
alert("SMS Sent to Emergency Hotline");
}

function sendEmail(){
alert("Email Sent to Emergency Department");
}

function sendReport(){
alert("Emergency Report Submitted Successfully");
}

</script>

</body>
</html>