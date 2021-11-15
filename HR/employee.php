<?php

include('includes/header.php');
include('includes/navbar.php');
?>


<div class="container-fluid">
 

    <div class="card shadow mb-4">
        <?php
        require 'connection.php';
        $query = "SELECT * FROM employee   ";
        $query_run = mysqli_query($conn, $query);
        ?>
        <div class="card-body">
            <div class="title" > Employees  </div>
            <table class="table table-bordered" id="myTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                <th> Employee id </th>
                    <th> Employee Name </th>
                    <th> Subject-specific  </th>
                    <th>Branch</th>
                   
                  
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
                            <td><?php  echo $row['branch']; ?></td>
                            
                           

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
