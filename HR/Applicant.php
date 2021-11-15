<?php

include('includes/header.php');

include('includes/navbar.php');
?>


<div class="container-fluid">
 

    <div class="card shadow mb-4">
        <?php
        require 'connection.php';
        $query = "SELECT * FROM applicant   ";
        $query_run = mysqli_query($conn, $query);
        ?>
        <div class="card-body">
            <div class="title" > Applicants </div>
            <table class="table table-bordered" id="myTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                <th> Applicant id </th>
                    <th> Applicant Name </th>
                    <th> Subject-specific  </th>
                    <th>status</th>
                   
                  
                </tr>
                </thead>
                <?php
                if(mysqli_num_rows($query_run) > 0)
                {
                    while($row = mysqli_fetch_assoc($query_run))
                    {
                      

                        ?>
                        <tr class="text-center">
                        <td><?php  echo $row['id']; ?></td>
                            <td><?php  echo $row['name']; ?></td>
                            <td><?php  echo $row['subject']; ?></td>
                            <td><?php 
                            $status=$row['status'];
                            if($status==0) echo ' to do interview 1 ';
                           else  if($status==1) echo ' to do interview 2 ';
                            else if($status==2) echo ' to do interview 3 ';
                            else if($status==3) echo ' Accepted ';
                            else echo ' Rejected ';
                            ?></td>
                            
                           

                        </tr>
                        <?php
                    }}
                else {
                    echo "No Record Found";
                }
                ?>
           
            </table>
        </div>
    </div>


  

    <?php
    
    include('includes/footer.php');



    ?>
