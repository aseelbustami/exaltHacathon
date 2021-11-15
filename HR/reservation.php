<?php

include('includes/header.php');
include('includes/navbar.php');
?>
 



<div class="container-fluid">
 
    
    <div class="card shadow mb-4">
        <?php
        require 'connection.php';
        $query = "SELECT * FROM reservation   ";
        $query_run = mysqli_query($conn, $query);
        ?>
        <div class="card-body">
            <div class="title"> Reservation Table  </div>
            <div ><p> meeting period is assumed to be 1 hour </p></div>
            <table class="table table-bordered" id="myTable" width="100%" >
                <thead>
                <tr>
                <th> Reservation  id </th>
                   
                    <th>Date of interview  </th>
                    <th> Start time   </th>
                    <th>Room Id /Branch</th>
                    <th>Applicant's Name  </th>
                    <th>Interviwer's Name </th>
                    <th> Status </th>
                </tr>
                </thead>
               
                <?php
                if(mysqli_num_rows($query_run) > 0)
                {
                    while($row = mysqli_fetch_assoc($query_run))
                    {
                      

                        ?>
                        <tr class="text-center">
                        <td><?php  echo $row['id_res']; ?></td>
                            <td><?php  echo $row['date']; ?></td>
                            <td><?php  echo $row['start_time']; ?></td>

                            <td>
                                <?php  
                            
                             $room_id =$row['room_id'];
                             $query2 = "SELECT * FROM room where id = $room_id";
                             $query_run2 = mysqli_query($conn, $query2);
                             $row2 = mysqli_fetch_assoc($query_run2);
                             echo $row['room_id'] .'/'.$row2['branch'];
                                 ?></td>
                                 
                            <td>
                             <?php 
                                                       
                             $user_id =$row['user_id'];
                             $query2 = "SELECT * FROM applicant where id = $user_id";
                             $query_run2 = mysqli_query($conn, $query2);
                             $row2 = mysqli_fetch_assoc($query_run2); 
                             echo $row2['name'];?>
                             </td>

                            <td>
                            <?php 
                             $employee_id =$row['employee_id'];
                             $query2 = "SELECT * FROM employee where id = $employee_id";
                             $query_run2 = mysqli_query($conn, $query2);
                             $row2 = mysqli_fetch_assoc($query_run2);
                            echo $row2['name']; ?>
                            </td>
                            <td><?php  
                            $user_status= $row['Done'];
                            if ($user_status==0)echo'not finished';
                            else 
                            echo'finished';
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
