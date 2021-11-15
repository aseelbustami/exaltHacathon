<?php


$Sunday = array();
$Monday = array();
$Tuesday = array();
$Wednesday = array();
$Thursday = array();
$notFoundRooms = array();
$result = array();
$sundayFlag = false;
$mondayFlag = false;
$tuesdayFlag = false;
$wednesdayFlag = false;
$ThursdayFlag = false;



class availableReservation
{


    public $startTimeReservation;
    public $day;
    public $roomId;
    public $id;
}



function findElement($array, $element)
{

    foreach ($array as $slot) {

        if ($slot['start_time'] == $element) {

            return 1;
        }
    }

    return -1;
}

function findPlaceConflict($conn, $startTimeReservation, $date, $roomId)

{
    $roomReservation = "select * from reservation where date='$date' and room_id='$roomId' and start_time='$startTimeReservation';";
    $flag = mysqli_query($conn, $roomReservation);
    $count = 0;
    while ($placeConflict = mysqli_fetch_array($flag)) {
        $count++;
    }


    return $count;
}


if (isset($_POST['login'])) {
    $username = $_POST["user"];
    $pass = $_POST["pass"];
    $branch = $_POST['branch'];
    $subject = $_POST['subject'];
    $date = $_POST['date'];
    $conn = mysqli_connect("localhost", "root", "", "hackathon");
    if (!$conn) {
        echo "connection failed";
        die();
    }
    $sql2 = "select * from applicant where name='$username' and password='$pass';";
    $res = mysqli_query($conn, $sql2);

    if (mysqli_num_rows($res) == 0) {
?><script>
            alert("not authinticated");
            window.location = "index.php";
        </script>
<?php
    } else {

        $employeeQuery = "select * from employee where  subject='$subject';";
        $employy = mysqli_query($conn, $employeeQuery);
        while ($row = mysqli_fetch_array($employy)) {
            $id = $row['id'];
            $employeeSlotQuery = "select * from relation where  employee_id='$id' and reserved=0 ;";
            $slots = mysqli_query($conn, $employeeSlotQuery);
            while ($rowSlot = mysqli_fetch_array($slots)) {
                $idSlot = $rowSlot['slot_id'];
                $freeSlotQuery = "select * from slot where  id='$idSlot' ;";

                $freeSlot = mysqli_query($conn, $freeSlotQuery);
                while ($rowFreeSlot = mysqli_fetch_array($freeSlot)) {
                    $day = $rowFreeSlot['day'];
                    if ($day == 'Sunday')
                        array_push($Sunday, $rowFreeSlot);



                    if ($day == 'Monday')
                        array_push($Monday, $rowFreeSlot);


                    if ($day == 'Tuesday')
                        array_push($Tuesday, $rowFreeSlot);
                    if ($day == 'Wednesday')
                        array_push($Wednesday, $rowFreeSlot);
                    if ($day == 'Thursday')
                        array_push($Thursday, $rowFreeSlot);
                }
            }



            $reservationQuery = "select * from reservation where  Done=0 ;";

            $allReservation = mysqli_query($conn, $reservationQuery);
            while ($rowReservation = mysqli_fetch_array($allReservation)) {
                $dateReservation = $rowReservation['date'];
                $startTimeReservation = $rowReservation['start_time'];
                $roomId = $rowReservation['room_id'];
                // assume the interview time is 1 hour 



                $timestamp = strtotime($dateReservation);
                $day = date('l',  $timestamp);

                if ($day == "Monday") {

                    $mondayFlag = true;

                    $first = findElement($Monday, $startTimeReservation - 1);
                    $second = findElement($Monday, $startTimeReservation - .5);
                    if ((($first > -1) && ($second > -1))) {
                        $temptime = $startTimeReservation - 1;
                        $roomReservation = "select * from reservation where date='$dateReservation' and room_id='$roomId' and start_time='$temptime';";
                        $flag = mysqli_query($conn, $roomReservation);
                        $count = 0;
                        while ($placeConflict = mysqli_fetch_array($flag)) {
                            $count++;
                        }

                        if ($count == 0) {

                            $choice = new availableReservation;
                            $choice->$startTimeReservation = $startTimeReservation - 1;
                            $choice->$day = $day;
                            $choice->$roomId = $roomId;
                            $choice->$id = $id;
                            array_push($result, $choice);



                            $allRoomsQuery = "select * from room ;";
                            $allRooms = mysqli_query($conn,  $allRoomsQuery);

                            $flagg =  false;
                            while ($roomFree = mysqli_fetch_array($allRooms)) {
                                $reservedR = "select * from reservation where date='$dateReservation' ;";
                                $freeRoomFinding = mysqli_query($conn, $reservedR);

                                while ($reservedRoom = mysqli_fetch_array($freeRoomFinding)) {
                                    if ($roomFree['id'] == $reservedRoom['room_id']) {
                                        $flagg =  true;
                                    }
                                }
                                if ($flagg == false) {

                                    array_push($notFoundRooms, $roomFree['id']);
                                }
                            }
                            for ($y = 0; $y < count($notFoundRooms); $y++) {
                                $choice = new availableReservation;
                                $choice->$startTimeReservation = $startTimeReservation - 1;
                                $choice->$day = $day;
                                $choice->$roomId = $notFoundRooms[$y];
                                $choice->$id = $id;
                                array_push($result, $choice);
                            }
                        }
                    } else {
                        $third = findElement($Monday, $startTimeReservation + 1);
                        $fourth = findElement($Monday, $startTimeReservation + 1.5);
                        if ((($third > -1) && ($fourth > -1))) {
                            $placeConflict2 = findPlaceConflict($conn, $startTimeReservation + 1, $dateReservation, $roomId);
                            if (!$placeConflict2) {

                                $choice = new availableReservation;
                                $choice->$startTimeReservation = $startTimeReservation + 1;
                                $choice->$day = $day;
                                $choice->$roomId = $roomId;
                                $choice->$id = $id;
                                array_push($result, $choice);



                                $allRoomsQuery = "select * from room ;";
                                $allRooms = mysqli_query($conn,  $allRoomsQuery);

                                $flagg =  false;
                                while ($roomFree = mysqli_fetch_array($allRooms)) {
                                    $reservedR = "select * from reservation where date='$dateReservation' ;";
                                    $freeRoomFinding = mysqli_query($conn, $reservedR);

                                    while ($reservedRoom = mysqli_fetch_array($freeRoomFinding)) {
                                        if ($roomFree['id'] == $reservedRoom['room_id']) {
                                            $flagg =  true;
                                        }
                                    }
                                    if ($flagg == false) {

                                        array_push($notFoundRooms, $roomFree['id']);
                                    }
                                }
                                for ($y = 0; $y < count($notFoundRooms); $y++) {
                                    $choice = new availableReservation;
                                    $choice->$startTimeReservation = $startTimeReservation - 1;
                                    $choice->$day = $day;
                                    $choice->$roomId = $notFoundRooms[$y];
                                    $choice->$id = $id;
                                    array_push($result, $choice);
                                }
                            }
                        }
                    }
                }

                if ($day == "Sunday") {
                    $sundayFlag = true;
                    $first = findElement($Sunday, $startTimeReservation - 1);
                    $second = findElement($Sunday, $startTimeReservation - .5);
                    if ((($first > -1) && ($second > -1))) {
                        $temptime = $startTimeReservation - 1;
                        $roomReservation = "select * from reservation where date='$dateReservation' and room_id='$roomId' and start_time='$temptime';";
                        $flag = mysqli_query($conn, $roomReservation);
                        $count = 0;
                        while ($placeConflict = mysqli_fetch_array($flag)) {
                            $count++;
                        }

                        if ($count == 0) {

                            $choice = new availableReservation;
                            $choice->$startTimeReservation = $startTimeReservation - 1;
                            $choice->$day = $day;
                            $choice->$roomId = $roomId;
                            $choice->$id = $id;
                            array_push($result, $choice);



                            $allRoomsQuery = "select * from room ;";
                            $allRooms = mysqli_query($conn,  $allRoomsQuery);

                            $flagg =  false;
                            while ($roomFree = mysqli_fetch_array($allRooms)) {
                                $reservedR = "select * from reservation where date='$dateReservation' ;";
                                $freeRoomFinding = mysqli_query($conn, $reservedR);

                                while ($reservedRoom = mysqli_fetch_array($freeRoomFinding)) {
                                    if ($roomFree['id'] == $reservedRoom['room_id']) {
                                        $flagg =  true;
                                    }
                                }
                                if ($flagg == false) {

                                    array_push($notFoundRooms, $roomFree['id']);
                                }
                            }
                            for ($y = 0; $y < count($notFoundRooms); $y++) {
                                $choice = new availableReservation;
                                $choice->$startTimeReservation = $startTimeReservation - 1;
                                $choice->$day = $day;
                                $choice->$roomId = $notFoundRooms[$y];
                                $choice->$id = $id;
                                array_push($result, $choice);
                            }
                        }
                    } else {
                        $third = findElement($Sunday, $startTimeReservation + 1);
                        $fourth = findElement($Sunday, $startTimeReservation + 1.5);
                        if ((($third > -1) && ($fourth > -1))) {
                            $placeConflict2 = findPlaceConflict($conn, $startTimeReservation + 1, $dateReservation, $roomId);
                            if (!$placeConflict2) {

                                $choice = new availableReservation;
                                $choice->$startTimeReservation = $startTimeReservation + 1;
                                $choice->$day = $day;
                                $choice->$roomId = $roomId;
                                $choice->$id = $id;
                                array_push($result, $choice);


                                $allRoomsQuery = "select * from room ;";
                                $allRooms = mysqli_query($conn,  $allRoomsQuery);

                                $flagg =  false;
                                while ($roomFree = mysqli_fetch_array($allRooms)) {
                                    $reservedR = "select * from reservation where date='$dateReservation' ;";
                                    $freeRoomFinding = mysqli_query($conn, $reservedR);

                                    while ($reservedRoom = mysqli_fetch_array($freeRoomFinding)) {
                                        if ($roomFree['id'] == $reservedRoom['room_id']) {
                                            $flagg =  true;
                                        }
                                    }
                                    if ($flagg == false) {

                                        array_push($notFoundRooms, $roomFree['id']);
                                    }
                                }
                                for ($y = 0; $y < count($notFoundRooms); $y++) {
                                    $choice = new availableReservation;
                                    $choice->$startTimeReservation = $startTimeReservation - 1;
                                    $choice->$day = $day;
                                    $choice->$roomId = $notFoundRooms[$y];
                                    $choice->$id = $id;
                                    array_push($result, $choice);
                                }
                            }
                        }
                    }
                }
                if ($day == "Tuesday") {
                    $tuesdayFlag = true;
                    $first = findElement($Tuesday, $startTimeReservation - 1);
                    $second = findElement($Tuesday, $startTimeReservation - .5);
                    if ((($first > -1) && ($second > -1))) {
                        $temptime = $startTimeReservation - 1;
                        $roomReservation = "select * from reservation where date='$dateReservation' and room_id='$roomId' and start_time='$temptime';";
                        $flag = mysqli_query($conn, $roomReservation);
                        $count = 0;
                        while ($placeConflict = mysqli_fetch_array($flag)) {
                            $count++;
                        }

                        if ($count == 0) {

                            $choice = new availableReservation;
                            $choice->$startTimeReservation = $startTimeReservation - 1;
                            $choice->$day = $day;
                            $choice->$roomId = $roomId;
                            $choice->$id = $id;
                            array_push($result, $choice);


                            $allRoomsQuery = "select * from room ;";
                            $allRooms = mysqli_query($conn,  $allRoomsQuery);

                            $flagg =  false;
                            while ($roomFree = mysqli_fetch_array($allRooms)) {
                                $reservedR = "select * from reservation where date='$dateReservation' ;";
                                $freeRoomFinding = mysqli_query($conn, $reservedR);

                                while ($reservedRoom = mysqli_fetch_array($freeRoomFinding)) {
                                    if ($roomFree['id'] == $reservedRoom['room_id']) {
                                        $flagg =  true;
                                    }
                                }
                                if ($flagg == false) {

                                    array_push($notFoundRooms, $roomFree['id']);
                                }
                            }
                            for ($y = 0; $y < count($notFoundRooms); $y++) {
                                $choice = new availableReservation;
                                $choice->$startTimeReservation = $startTimeReservation - 1;
                                $choice->$day = $day;
                                $choice->$roomId = $notFoundRooms[$y];
                                $choice->$id = $id;
                                array_push($result, $choice);
                            }
                        }
                    } else {
                        $third = findElement($Tuesday, $startTimeReservation + 1);
                        $fourth = findElement($Tuesday, $startTimeReservation + 1.5);
                        if ((($third > -1) && ($fourth > -1))) {
                            $placeConflict2 = findPlaceConflict($conn, $startTimeReservation + 1, $dateReservation, $roomId);
                            if (!$placeConflict2) {

                                $choice = new availableReservation;
                                $choice->$startTimeReservation = $startTimeReservation + 1;
                                $choice->$day = $day;
                                $choice->$roomId = $roomId;
                                $choice->$id = $id;
                                array_push($result, $choice);


                                $allRoomsQuery = "select * from room ;";
                                $allRooms = mysqli_query($conn,  $allRoomsQuery);

                                $flagg =  false;
                                while ($roomFree = mysqli_fetch_array($allRooms)) {
                                    $reservedR = "select * from reservation where date='$dateReservation' ;";
                                    $freeRoomFinding = mysqli_query($conn, $reservedR);

                                    while ($reservedRoom = mysqli_fetch_array($freeRoomFinding)) {
                                        if ($roomFree['id'] == $reservedRoom['room_id']) {
                                            $flagg =  true;
                                        }
                                    }
                                    if ($flagg == false) {

                                        array_push($notFoundRooms, $roomFree['id']);
                                    }
                                }
                                for ($y = 0; $y < count($notFoundRooms); $y++) {
                                    $choice = new availableReservation;
                                    $choice->$startTimeReservation = $startTimeReservation - 1;
                                    $choice->$day = $day;
                                    $choice->$roomId = $notFoundRooms[$y];
                                    $choice->$id = $id;
                                    array_push($result, $choice);
                                }
                            }
                        }
                    }
                }
                if ($day == "Wednesday") {
                    $wednesdayFlag = true;
                    $first = findElement($Wednesday, $startTimeReservation - 1);
                    $second = findElement($Wednesday, $startTimeReservation - .5);
                    if ((($first > -1) && ($second > -1))) {
                        $temptime = $startTimeReservation - 1;
                        $roomReservation = "select * from reservation where date='$dateReservation' and room_id='$roomId' and start_time='$temptime';";
                        $flag = mysqli_query($conn, $roomReservation);
                        $count = 0;
                        while ($placeConflict = mysqli_fetch_array($flag)) {
                            $count++;
                        }

                        if ($count == 0) {

                            $choice = new availableReservation;
                            $choice->$startTimeReservation = $startTimeReservation - 1;
                            $choice->$day = $day;
                            $choice->$roomId = $roomId;
                            $choice->$id = $id;
                            array_push($result, $choice);



                            $allRoomsQuery = "select * from room ;";
                            $allRooms = mysqli_query($conn,  $allRoomsQuery);

                            $flagg =  false;
                            while ($roomFree = mysqli_fetch_array($allRooms)) {
                                $reservedR = "select * from reservation where date='$dateReservation' ;";
                                $freeRoomFinding = mysqli_query($conn, $reservedR);

                                while ($reservedRoom = mysqli_fetch_array($freeRoomFinding)) {
                                    if ($roomFree['id'] == $reservedRoom['room_id']) {
                                        $flagg =  true;
                                    }
                                }
                                if ($flagg == false) {

                                    array_push($notFoundRooms, $roomFree['id']);
                                }
                            }
                            for ($y = 0; $y < count($notFoundRooms); $y++) {
                                $choice = new availableReservation;
                                $choice->$startTimeReservation = $startTimeReservation - 1;
                                $choice->$day = $day;
                                $choice->$roomId = $notFoundRooms[$y];
                                $choice->$id = $id;
                                array_push($result, $choice);
                            }
                        }
                    } else {
                        $third = findElement($Wednesday, $startTimeReservation + 1);
                        $fourth = findElement($Wednesday, $startTimeReservation + 1.5);
                        if ((($third > -1) && ($fourth > -1))) {
                            $placeConflict2 = findPlaceConflict($conn, $startTimeReservation + 1, $dateReservation, $roomId);
                            if (!$placeConflict2) {
                                $choice = new availableReservation;
                                $choice->$startTimeReservation = $startTimeReservation + 1;
                                $choice->$day = $day;
                                $choice->$roomId = $roomId;
                                $choice->$id = $id;
                                array_push($result, $choice);



                                $allRoomsQuery = "select * from room ;";
                                $allRooms = mysqli_query($conn,  $allRoomsQuery);

                                $flagg =  false;
                                while ($roomFree = mysqli_fetch_array($allRooms)) {
                                    $reservedR = "select * from reservation where date='$dateReservation' ;";
                                    $freeRoomFinding = mysqli_query($conn, $reservedR);

                                    while ($reservedRoom = mysqli_fetch_array($freeRoomFinding)) {
                                        if ($roomFree['id'] == $reservedRoom['room_id']) {
                                            $flagg =  true;
                                        }
                                    }
                                    if ($flagg == false) {

                                        array_push($notFoundRooms, $roomFree['id']);
                                    }
                                }
                                for ($y = 0; $y < count($notFoundRooms); $y++) {
                                    $choice = new availableReservation;
                                    $choice->$startTimeReservation = $startTimeReservation - 1;
                                    $choice->$day = $day;
                                    $choice->$roomId = $notFoundRooms[$y];
                                    $choice->$id = $id;
                                    array_push($result, $choice);
                                }
                            }
                        }
                    }
                }

                if ($day == "Thursday") {
                    $thursdayFlag = true;
                    $first = findElement($Thursday, $startTimeReservation - 1);
                    $second = findElement($Thursday, $startTimeReservation - .5);
                    if ((($first > -1) && ($second > -1))) {
                        $temptime = $startTimeReservation - 1;
                        $roomReservation = "select * from reservation where date='$dateReservation' and room_id='$roomId' and start_time='$temptime';";
                        $flag = mysqli_query($conn, $roomReservation);
                        $count = 0;
                        while ($placeConflict = mysqli_fetch_array($flag)) {
                            $count++;
                        }

                        if ($count == 0) {

                            $choice = new availableReservation;
                            $choice->$startTimeReservation = $startTimeReservation - 1;
                            $choice->$day = $day;
                            $choice->$roomId = $roomId;
                            $choice->$id = $id;
                            array_push($result, $choice);


                            $allRoomsQuery = "select * from room ;";
                            $allRooms = mysqli_query($conn,  $allRoomsQuery);

                            $flagg =  false;
                            while ($roomFree = mysqli_fetch_array($allRooms)) {
                                $reservedR = "select * from reservation where date='$dateReservation' ;";
                                $freeRoomFinding = mysqli_query($conn, $reservedR);

                                while ($reservedRoom = mysqli_fetch_array($freeRoomFinding)) {
                                    if ($roomFree['id'] == $reservedRoom['room_id']) {
                                        $flagg =  true;
                                    }
                                }
                                if ($flagg == false) {

                                    array_push($notFoundRooms, $roomFree['id']);
                                }
                            }
                            for ($y = 0; $y < count($notFoundRooms); $y++) {
                                $choice = new availableReservation;
                                $choice->$startTimeReservation = $startTimeReservation - 1;
                                $choice->$day = $day;
                                $choice->$roomId = $notFoundRooms[$y];
                                $choice->$id = $id;
                                array_push($result, $choice);
                            }
                        }
                    } else {
                        $third = findElement($Thursday, $startTimeReservation + 1);
                        $fourth = findElement($Thursday, $startTimeReservation + 1.5);
                        if ((($third > -1) && ($fourth > -1))) {
                            $placeConflict2 = findPlaceConflict($conn, $startTimeReservation + 1, $dateReservation, $roomId);
                            if (!$placeConflict2) {

                                $choice = new availableReservation;
                                $choice->$startTimeReservation = $startTimeReservation + 1;
                                $choice->$day = $day;
                                $choice->$roomId = $roomId;
                                $choice->$id = $id;
                                array_push($result, $choice);



                                $allRoomsQuery = "select * from room ;";
                                $allRooms = mysqli_query($conn,  $allRoomsQuery);

                                $flagg =  false;
                                while ($roomFree = mysqli_fetch_array($allRooms)) {
                                    $reservedR = "select * from reservation where date='$dateReservation' ;";
                                    $freeRoomFinding = mysqli_query($conn, $reservedR);

                                    while ($reservedRoom = mysqli_fetch_array($freeRoomFinding)) {
                                        if ($roomFree['id'] == $reservedRoom['room_id']) {
                                            $flagg =  true;
                                        }
                                    }
                                    if ($flagg == false) {

                                        array_push($notFoundRooms, $roomFree['id']);
                                    }
                                }
                                for ($y = 0; $y < count($notFoundRooms); $y++) {
                                    $choice = new availableReservation;
                                    $choice->$startTimeReservation = $startTimeReservation - 1;
                                    $choice->$day = $day;
                                    $choice->$roomId = $notFoundRooms[$y];
                                    $choice->$id = $id;
                                    array_push($result, $choice);
                                }
                            }
                        }
                    }
                }
            }
          
            



           

        }
    }
}






?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    for ($y = 0; $y < count($result); $y++) {
    ?>

        <input type="checkbox" id="o" name="o" value="<?php echo $result[$y]->$startTimeReservation; ?> ">
        <label for="o"> <?php 
                   
                   echo $result[$y]->$startTimeReservation;
                   
               
    
                 ?> </label><br>
    <?php
    }

    ?>

    <button type="submit"> Submit</button>
</body>

</html>
