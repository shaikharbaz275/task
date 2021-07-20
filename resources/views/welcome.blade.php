<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
<div class="row">
  <div class="col-sm-3"></div>
  <div class="col-sm-6">
  <h2>User Form</h2>
  <form action="/form">
  @csrf

  <div class="form-group">
      <label for="email">Name:</label>
      <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name">
    </div>
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
    </div>
    <div class="form-group">
      <label for="pwd">Mobile:</label>
      <input type="text" class="form-control" id="mobile" placeholder="Enter Mobile No" name="mobile">
      <input type="hidden" class="form-control"  id="get_otp" name="otp">
    </div>
    <button type="button" class="btn btn-success" id="store">submi</button>
    <button type="button" class="btn btn-info"  id="otp">Send OTP</button>
  </form><br>

  <table class="table">
  <thead>
    <tr>
      <th scope="col">name</th>
      <th scope="col">Email</th>
      <th scope="col">Mobile No</th>
    </tr>
    <tbody id="show">
    </tbody>
  </thead>
  
</table>


  <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <span id="success" style="color:green; font-size: 20px"></span><br>
      </div>
      <div class="modal-body">
        <p>We Have Send 5 Digits OTP Please Verify Your Mobile Number.</p><br>
        <input type="text" class="form-control" name="otp" id="otp_no" placeholder="Enter The OTP">
        <span id="error" style="color:red"></span><br>
        <button type="button" class="btn btn-info"  id="Verify">Verify</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

    

  </div>
</div>
</div>
</div>
</div>

<script type="text/javascript">

    $(document).ready(function() {
        // get data form database table
        var mobile_format = "/^\d{10}$/"; 
        data()
        function data(){
        $.ajax({
                url: '/get-data',
                method: 'get',             
                data: {
                 _token: $('input[name="_token"]').val()},
                success: function(data){
                    if(data.data)
                    {
                        var el = document.getElementById('show');
                        data.data.map((val)=>{
                        el.innerHTML = el.innerHTML +
                        '<tr>'+
                         '<td>'+val.name+'</td>'+
                         '<td>'+val.email+'</td>'+
                          '<td>'+val.mobile+'</td>'+
                            '</tr>'
                        })
                    }
                    else{
                        document.getElementById('error').innerHTML="Incorrect OTP";
                    console.log(data.failed)
                    }
                },
                error: function(){},
            });
        }

        //send otp 
        $('#otp').click(function(){  
            var mobile =  document.getElementById("mobile").value;
        if(mobile.length != 10)
        {
            alert("Invalid number; must be ten digits")
        }
        else{                 
            $.ajax({
                url: '/send_otp',
                method: 'post',             
                data: {mobile: mobile,
                 _token: $('input[name="_token"]').val()},
                success: function(data){
                    console.log(data)
                    $('#myModal').modal('toggle');
                },
                error: function(){},
            });
        } 
        });   

        //verify OTP
        $('#Verify').click(function(){  
            var otp =  document.getElementById("otp_no").value;
        if(otp=="")
        {
            alert("Please enter Your OTP")
        }
        
        else{                 
            $.ajax({
                url: '/verify_otp',
                method: 'post',             
                data: {otp: otp,
                 _token: $('input[name="_token"]').val()},
                success: function(data){
                    if(data.success)
                    {
                        document.getElementById('success').innerHTML="Successfully Verify";
                        document.getElementById('get_otp').value = data.otp; 
                    }
                    else{
                        document.getElementById('error').innerHTML="Incorrect OTP";
                    console.log(data.failed)
                    }
                },
                error: function(){},
            });
        } 
        });   

         //store data 
         $('#store').click(function(){  
            var otp =  document.getElementById("get_otp").value;
            var name =  document.getElementById("name").value;
            var mobile =  document.getElementById("mobile").value;
            var email =  document.getElementById("email").value;
            console.log(otp)
        if(otp=="")
        {
            alert("Please Verify Your Mobile Number")

        }
        else if(name=="")
        {
            alert("Please enter Your Name")

        }
        else if(email=="")
        {
            alert("Please enter Your Email")

        }
        else if(mobile.length != 10)
        {
            alert("Invalid number; must be ten digits")

        }
      
        else{                 
            $.ajax({
                url: '/store',
                method: 'post',             
                data: {
                  otp: otp,
                  email: email,
                  mobile: mobile,
                  name: name,
                 _token: $('input[name="_token"]').val()},
                success: function(data){
                    var otp =  document.getElementById("get_otp").value="";
                     var name =  document.getElementById("name").value="";
                    var mobile =  document.getElementById("mobile").value="";
                    var email =  document.getElementById("email").value="";
                    if(data.data)
                    {
                        var val = data.data;
                        var el = document.getElementById('show');
                        $('#show').append('' +
                        '<tr>'+
                         '<td>'+val.name+'</td>'+
                         '<td>'+val.email+'</td>'+
                          '<td>'+val.mobile+'</td>'+
                            '</tr>');
                    }
                },
                error: function(){},
            });
        } 
        });   
            
    });
</script>
</body>
</html>