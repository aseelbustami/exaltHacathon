
    <?php 
    
    $conn=mysqli_connect("localhost","root","","hackathon");
    if(!$conn){echo"connection failed";die();}
   ?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Forms</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="app.css">
</head>

<body>
    <div class="container">
        <h1 >Where do you prefer to make your interview?</h1>
        <form action="home.php" method="post" >
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="user">User name</label>
                    <input type="user" class="form-control" id="password" placeholder="usernamr" name="user" value="user">
                </div>
                <div class="form-group col-md-6">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="password" name="pass" value="pass">
                </div>
            </div>
          
            <div class="form-row">
                
                <div class="form-group col-md-3 col-6">
                    <label for="state">Branch</label>
                    <select class="form-control" name="branch" id="branch">
                        <option value="ramalla">Ramalla</option>
                        <option value="nablus">Nablus</option>
                        <option value="does not matter">does not matter</option>
                    </select>
                </div>
               
            </div>
            <div class="form-row">
                
                <div class="form-group col-md-3 col-6">
                    <label for="subject">Subject</label>
                    <select class="form-control" name="subject" id="subject">
                        <option value="front end">front end</option>
                        <option value="back end">back end</option>
                      
                    </select>
                </div>
               
            </div>
            <div class="form-row">
                
                <div class="form-group col-md-3 col-6">
                    <label for="date">date</label>
                    <input type="date" id="date" value="date" name="date">
                </div>
               
            </div>
            

            <button type="submit" class="btn btn-success" id="login" value="login" name="login">Log in</button>



        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>
</body>

</html>
