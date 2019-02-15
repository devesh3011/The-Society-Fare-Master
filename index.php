<?php
require_once 'vendor/phpmailer/phpmailer/vendor/autoload.php';
require 'core.inc.php';
require 'connect.inc.php';
if (loggedin()) {
  $firstname = getfield('users','firstname');
  $lastname = getfield('users','lastname');
  
  echo '<div style="margin-left: 22.5%;"><h5 style = "color: #4d4dff;">Hello '.$firstname.' '.$lastname.'</h5></div>';   
}
?>

<?php
global $prependPath;
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $prependPath='';
} else {
    //This is a server not using Windows!;
    $prependPath='/var/www/html/sf/';
}
?>

<!--Logging Users IN-->
<?php
global $user_id;
if (isset($_POST['username']) && isset($_POST['password'])) {
  $newEventForm = 0;
  $newSubEventForm = 0;
  $username = $_POST['username'];
  $password = $_POST['password'];
  if (!empty($username) && !empty($password)) {
  $password_hash = md5($password);
    $query = @"SELECT `id` FROM `users` WHERE `username` = '".mysqli_real_escape_string($conn,$username)."' AND `password` = '".mysqli_real_escape_string($conn,$password_hash)."'; ";
    if ($query_run = @mysqli_query($conn,$query)) {
      $query_num_rows = @mysqli_num_rows($query_run);
      if ($query_num_rows==0) {
        echo '
        <script>
        document.write(alert("The username or password did not match"));
        </script>
        ';
      }
      elseif ($query_num_rows==1) {
        $row = mysqli_fetch_assoc($query_run);
        $user_id = $row['id'];
        $_SESSION['user_id'] = $user_id;
        header('Location: index.php');
      }
    }
  }
  else{
    echo "None of the fields can be empty";
  }
}
?>

<!--New Event Form Validation-->
<?php
  global $ccs_delete;
  global $ssa_delete;
  global $owasp_delete;
  global $tedx_delete;
  global $msc_delete;
  global $ieee_delete;
  $ccs_delete="none";
  $ssa_delete="none";
  $owasp_delete="none";
  $tedx_delete="none";
  $msc_delete="none";
  $ieee_delete="none";
if (loggedin()) {
  global $society_name;
  global $society_name_CAPS;
  global $society_table_name;
  global $society_sub_event_table_name;
  global $event_gallery;
  if($_SESSION['user_id'] == 1 || $_SESSION['user_id'] == 2){
    $society_name = 'ccs';
    $society_name_CAPS = 'CCS';
    $society_table_name = 'ccs_events';
    $society_sub_event_table_name = 'ccs_sub_events';
    $event_gallery = 'ccs_event_gallery';
    $ccs_delete="inline-block";
  }
    if($_SESSION['user_id'] == 3 || $_SESSION['user_id'] == 4){
      $society_name = 'ssa';
      $society_name_CAPS = 'SSA';
      $society_table_name = 'ssa_events';
      $society_sub_event_table_name = 'ssa_sub_events';
      $event_gallery = 'ssa_event_gallery';
      $fb_link="https://www.facebook.com/ssathaparuniversity/";
      $ssa_delete="inline-block";
  }
    if($_SESSION['user_id'] == 5){
      $society_name = 'msc';
      $society_name_CAPS = 'MSC';
      $society_table_name = 'msc_events';
      $society_sub_event_table_name = 'msc_sub_events';
      $event_gallery = 'msc_event_gallery';
      $fb_link="https://www.facebook.com/msc2k17/";
      $msc_delete="inline-block";
  }
    if($_SESSION['user_id'] == 6 || $_SESSION['user_id'] == 7){
      $society_name = 'owasp';
      $society_name_CAPS = 'OWASP';
      $society_table_name = 'owasp_events';
      $society_sub_event_table_name = 'owasp_sub_events';
      $event_gallery = 'owasp_event_gallery';
      $fb_link="https://www.facebook.com/owasptsc/";
      $owasp_delete="inline-block";
  }
    if($_SESSION['user_id'] == 8 || $_SESSION['user_id'] == 9){
      $society_name = 'tedx';
      $society_name_CAPS = 'TEDx';
      $society_table_name = 'tedx_events';
      $society_sub_event_table_name = 'tedx_sub_events';
      $event_gallery = 'tedx_event_gallery';
      $fb_link="https://www.facebook.com/tedxthaparuniversity/";
      $tedx_delete="inline-block";
  }
    if ($_SESSION['user_id'] == 10){
      $society_name = 'ieee';
      $society_name_CAPS = 'IEEE';
      $society_table_name = 'ieee_events';
      $society_sub_event_table_name = 'ieee_sub_events';
      $event_gallery = 'ieee_event_gallery';
      $fb_link="https://www.facebook.com/ieee.thapar/";
      $ieee_delete="inline-block";
    }
    if (isset($_POST['event_name'])) {
      if (!empty($_POST['event_name'])) {
        $event_name = mysqli_real_escape_string($conn,$_POST['event_name']);
      }else{
        $event_name = NULL;
      }
      if (!empty($_POST['event_date'])) {
        $event_date = mysqli_real_escape_string($conn,$_POST['event_date']);
      }else{
        $event_date = NULL;
      }
      if (!empty($_POST['event_year'])) {
        $event_year = mysqli_real_escape_string($conn,$_POST['event_year']);
      }else{
        $event_year = NULL;
      }
      if (!empty($_POST['event_time'])) {
        $event_time = mysqli_real_escape_string($conn,$_POST['event_time']); 
      }else{
        $event_time = NULL;
      }
      if (!empty($_POST['event_venue'])) {
        $event_venue = mysqli_real_escape_string($conn,$_POST['event_venue']);   
      }else{
        $event_venue = NULL;
      }
      if (!empty($_POST['event_bio'])) {
        $event_bio = mysqli_real_escape_string($conn,$_POST['event_bio']);
      }else{
        $event_bio = NULL;
      }
      if (!empty($_POST['event_report'])) {
        $event_report = mysqli_real_escape_string($conn,$_POST['event_report']);
      }else{
        $event_report = NULL;
      }
      if (!empty($_POST['no_of_students'])) {
        $no_of_students = mysqli_real_escape_string($conn,$_POST['no_of_students']);
      }else{
        $no_of_students = NULL;
      }
      if (!empty($_POST['event_speaker'])) {
        $event_speaker = mysqli_real_escape_string($conn,$_POST['event_speaker']);
      }else{
        $event_speaker = NULL;
      }
      //Processing Images
      $msg = "";
      //POSTER  
        $max_file_size = 1024*1024*10; //10 mb
        $poster_name = NULL;
        if (@$_FILES['poster_image']['name']) {
        $poster_name = @$_FILES['poster_image']['name'];
        $poster_size = @$_FILES['poster_image']['size'];
        $poster_type = @$_FILES['poster_image']['type'];
        $poster_tmp_name = @$_FILES['poster_image']['tmp_name'];
        $extension = strtolower(substr($poster_name, strrpos($poster_name,'.') + 1));
        if (isset($poster_name)) {
          if(!empty($poster_name)){
            if (($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') && ($type = 'image/*') && ($poster_size < $max_file_size)) {
              $location = 'societies_data/'.$society_name_CAPS.'/uploads/posters/main_events/';
              if(move_uploaded_file($poster_tmp_name, $location.$poster_name)){}
              else{
                echo '
                  <script>
                    alert("There was an error in uploading POSTER. Redirecting... \nRemove the incorrect event and try again later!");
                    setTimeout(`window.location="index.php"`,1);
                  </script>
                ';
              }
            }
            else{
              echo '
                  <script>
                    alert("Poster must be jpg/jpeg/png and 10mb or less. Redirecting... \nRemove the incorrect event and try again later!");
                    setTimeout(`window.location="index.php"`,1);
                  </script>
                ';
            }
          }
        }
      }
      $query = "INSERT INTO $society_table_name (`event_name`, `event_date`, `event_time`, `event_year`, `event_venue`, `no_of_students`, `event_speaker`, `event_poster`, `brief_bio`, `event_report`) VALUES ('$event_name', '$event_date', '$event_time', '$event_year', '$event_venue', '$no_of_students', '$event_speaker', '$poster_name', '$event_bio', '$event_report')";
    
      if (mysqli_query($conn,$query)) {}
        else{
            echo '
                  <script>
                    alert("Insert query failed!. Redirecting... \nRemove the incorrect event and try again later!");
                    setTimeout(`window.location="index.php"`,1);
                  </script>
                ';
      }
        
  // GALLERY Images
        $valid_formats = array("jpg", "jpeg", "png", "gif", "zip", "bmp", "JPG", "JPEG", "PNG", "GIF", "ZIP", "BMP");
        $path = "societies_data/".$society_name_CAPS."/uploads/gallery/main_events/"; // Upload directory
        $count_gallery = 0;
        $error = 0;
        foreach ($_FILES['gallery_images']['name'] as $f => $name) {    
          if ($_FILES['gallery_images']['error'][$f] == 4) {
              $error = 1;
              echo '
              <script>
              alert("There was an error in inserting gallery images or no images were selected for event attendance. Redirecting... \nRemove the incorrect event and try again later!");
              setTimeout(`window.location="index.php"`,1);
              </script>
            ';
              //continue; // Skip file if any error found
          }
          if ($_FILES['gallery_images']['error'][$f] == 0) {             
              if ($_FILES['gallery_images']['size'][$f] > $max_file_size) {
                  $message[] = "$name is too large!.";
                  $error = 1;
                 echo '
                  <script>
                    alert("'.$name.' is too large!. \nRemove the incorrect event and proceed with smaller image");
                    setTimeout(`window.location="index.php"`,1);
                  </script>
                ';
                  //continue; // Skip large files
              }
          elseif( ! in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats) ){
            $error = 1;
            $message[] = "$name is not a valid format";
            echo '
              <script>
              alert("'.$name.' is not a valid format! \nRemove the incorrect event and proceed with valid format images i.e. jpg, jpeg, png, gif, zip, bmp");
              setTimeout(`window.location="index.php"`,1);
              </script>
            ';
            //continue; // Skip invalid file formats
          }
              else{ // No error found! Move uploaded files
                $event_id = NULL;
                  if(move_uploaded_file($_FILES["gallery_images"]["tmp_name"][$f], $path.$name)){
                                        $query = "select `event_id` from $society_table_name ORDER BY `event_id` desc LIMIT 1";
                    if($query_run = mysqli_query($conn,$query)){
                      if ($row = mysqli_fetch_assoc($query_run)) {
                        $event_id = $row['event_id'];
                      }else{
                        $error = 1;
                        echo '
                        <script>
                          alert("There was an error in uploading gallery images. Contact developer. Redirecting... \nRemove the incorrect event and try again later!");
                          setTimeout(`window.location="index.php"`,1);
                        </script>
                        ';
                      }
                    }else{
                      $error = 1;
                        echo '
                        <script>
                          alert("Contact developer. Check Query. Redirecting... \nRemove the incorrect event and try again later!");
                          setTimeout(`window.location="index.php"`,1);
                        </script>
                        ';
                    }
                    $sql = "INSERT INTO `$event_gallery` (`main_event_id`, `main_event_image`) VALUES ('$event_id', '$name')";
                    if(!mysqli_query($conn, $sql)){
                      $error = 1;
                        echo '
                        <script>
                          alert("Contact developer. Check gallery insertion query. Redirecting... \nRemove the incorrect event and try again later!");
                          setTimeout(`window.location="index.php"`,1);
                        </script>
                        ';
                    }else{
                      $count_gallery++; // Number of successfully uploaded file
                      
                    }
                  }
              }
          }
      }
        if($error == 1){
          echo '
              <script>
              alert("There was an error in inserting gallery images. Redirecting... \nRemove the incorrect event and try again later!");
              setTimeout(`window.location="index.php"`,1);
              </script>
            ';
        } 
        // ATTENDANCE Images
        //
        $path = "societies_data/".$society_name_CAPS."/uploads/attendance/main_events/"; // Upload directory
        $count_attendance = 0;
        $error = 0;
        foreach ($_FILES['event_attendance_image']['name'] as $f => $name) {  
          if ($_FILES['event_attendance_image']['error'][$f] == 4) {
              $error = 1;
              echo '
              <script>
              alert("There was an error in inserting attendance images. Redirecting... \nRemove the incorrect event and try again later!");
              setTimeout(`window.location="index.php"`,1);
              </script>
            ';
              //continue; // Skip file if any error found
          }
          if ($_FILES['event_attendance_image']['error'][$f] == 0) {             
              if ($_FILES['event_attendance_image']['size'][$f] > $max_file_size) {
                  $message[] = "$name is too large!.";
                  $error = 1;
                 echo '
                  <script>
                    alert("'.$name.' is too large!. \nRemove the incorrect event and proceed with smaller image");
                    setTimeout(`window.location="index.php"`,1);
                  </script>
                ';
                //continue; // Skip large files
              }
          elseif( ! in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats) ){
            $error = 1;
            $message[] = "$name is not a valid format";
            echo '
              <script>
              alert("'.$name.' is not a valid format! \nRemove the incorrect event and proceed with valid format images i.e. jpg, jpeg, png, gif, zip, bmp");
              setTimeout(`window.location="index.php"`,1);
              </script>
            ';
            //continue; // Skip invalid file formats
          }
              else{ // No error found! Move uploaded files
                $event_id = NULL;
                  if(move_uploaded_file($_FILES["event_attendance_image"]["tmp_name"][$f], $path.$name)){
                                        $query = "select `event_id` from $society_table_name ORDER BY `event_id` desc LIMIT 1";
                    if($query_run = mysqli_query($conn,$query)){
                      if ($row = mysqli_fetch_assoc($query_run)) {
                        $event_id = $row['event_id'];
                      }else{
                        $error = 1;
                        echo '
                        <script>
                          alert("There was an error in uploading attendance images. Contact developer. Redirecting... \nRemove the incorrect event and try again later!");
                          setTimeout(`window.location="index.php"`,1);
                        </script>
                      ';
                      }
                    }else{
                      $error = 1;
                        echo '
                        <script>
                          alert("Contact developer. Check Query. Redirecting... \nRemove the incorrect event and try again later!");
                          setTimeout(`window.location="index.php"`,1);
                        </script>
                        ';
                    }
                    $sql = "INSERT INTO `$event_gallery` (`main_event_id`, `main_event_attendance_image`) VALUES ('$event_id', '$name')";
                    if(!mysqli_query($conn, $sql)){
                      $error = 1;
                        echo '
                        <script>
                          alert("Contact developer. Check attendance insertion query. Redirecting... \nRemove the incorrect event and try again later!");
                          setTimeout(`window.location="index.php"`,1);
                        </script>
                        ';
                    }else{
                      $count_attendance++; // Number of successfully uploaded file
                      
                    }
                  }
              }
          }
      }
        if($error == 0){
          header('Location: index.php');
          exit; 
        }       
  }
    //$result = mysqli_query($conn, "SELECT `images` FROM $event_gallery, $society_table_name where $event_gallery.`event_id` = '$society_table_name.`event_id`'"  );
}
?>

<!--New sub-event Form Validation-->
<?php
if (loggedin()) {
    $check_main_events_query = "select `event_id` from $society_table_name";
    if ($check_main_events_query_run = mysqli_query($conn,$check_main_events_query)) {
    $query_num_rows = mysqli_num_rows($check_main_events_query_run);
      if ($query_num_rows ==0 ) {
        /*echo '
              <script>
                alert("There are no existing main events in the society database.");
              </script>
            ';*/
        //header('Location: index.php');
        //exit;
      }
    }
    if ( $query_num_rows > 0) {
    if (isset($_POST['sub_event_name']) && isset($_POST['sub_event_year'])) {
      
      if ($_POST['BIG_event_name'] != NULL) {
        $main_event_name = $_POST['BIG_event_name'];
      }
      else{
        echo '
          <script>
            alert("Please select a main event to which sub-event needs to be added from the list");
          </script>
        ';
      }
      if (!empty($_POST['sub_event_name'])) {
        $sub_event_name = mysqli_real_escape_string($conn,$_POST['sub_event_name']);
      }else{
        $sub_event_name = NULL;
      }
      if (!empty($_POST['sub_event_date'])) {
        $sub_event_date = mysqli_real_escape_string($conn,$_POST['sub_event_date']);
      }else{
        $sub_event_date = NULL;
      }
      if (!empty($_POST['sub_event_year'])) {
        $sub_event_year = mysqli_real_escape_string($conn,$_POST['sub_event_year']);
      }else{
        $sub_event_year = NULL;
      }
      if (!empty($_POST['sub_event_time'])) {
        $sub_event_time = mysqli_real_escape_string($conn,$_POST['sub_event_time']); 
      }else{
        $sub_event_time = NULL;
      }
      if (!empty($_POST['sub_event_venue'])) {
        $sub_event_venue = mysqli_real_escape_string($conn,$_POST['sub_event_venue']);   
      }else{
        $sub_event_venue = NULL;
      }
      if (!empty($_POST['sub_event_bio'])) {
        $sub_event_bio = mysqli_real_escape_string($conn,$_POST['sub_event_bio']);
      }else{
        $sub_event_bio = NULL;
      }
      if (!empty($_POST['sub_event_report'])) {
        $sub_event_report = mysqli_real_escape_string($conn,$_POST['sub_event_report']);
      }else{
        $sub_event_report = NULL;
      }
      if (!empty($_POST['sub_event_no_of_students'])) {
        $sub_event_no_of_students = mysqli_real_escape_string($conn,$_POST['sub_event_no_of_students']);
      }else{
        $sub_event_no_of_students = NULL;
      }
      if (!empty($_POST['sub_event_speaker'])) {
        $sub_event_speaker = mysqli_real_escape_string($conn,$_POST['sub_event_speaker']);
      }else{
        $sub_event_speaker = NULL;
      }
      //Processing Images
      $msg = "";
        $max_file_size = 1024*1024*10; //10 mb        
        //POSTER
        $sub_event_poster_name = NULL;
        if ($_FILES['sub_event_poster_image']['name']) {
        $sub_event_poster_name = @$_FILES['sub_event_poster_image']['name'];
        $sub_event_poster_size = @$_FILES['sub_event_poster_image']['size'];
        $sub_event_poster_type = @$_FILES['sub_event_poster_image']['type'];
        $sub_event_poster_tmp_name = @$_FILES['sub_event_poster_image']['tmp_name'];
        $sub_event_extension = strtolower(substr($sub_event_poster_name, strrpos($sub_event_poster_name,'.') + 1));
        if (isset($sub_event_poster_name)) {
          if(!empty($sub_event_poster_name)){
            if (($sub_event_extension == 'jpg' || $sub_event_extension == 'jpeg' || $sub_event_extension == 'png') && ($type = 'image/*') && ($sub_event_poster_size < $max_file_size)) {
              $location = 'societies_data/'.$society_name_CAPS.'/uploads/posters/sub_events/';
              if(move_uploaded_file($sub_event_poster_tmp_name, $location.$sub_event_poster_name)){}
              else{
                echo '
                  <script>
                    alert("There was an error uploading poster. Redirecting... \nRemove the incorrect event and try again later!");
                    setTimeout(`window.location="index.php"`,1);
                  </script>
                ';
              }
            }
            else{
              echo '
                  <script>
                    alert("Poster must be jpg/jpeg/png and 10mb or less. Redirecting... \nRemove the incorrect event and try again later!");
                    setTimeout(`window.location="index.php"`,1);
                  </script>
                ';
            }
          }
        }
      }
      $sub_event_query = "select `event_id`,`curr_no_of_sub_events` from $society_table_name where `event_name` = '$main_event_name' ";
      $sub_event_query_run = @mysqli_query($conn,$sub_event_query);
      if (($query_num_rows = @mysqli_num_rows($sub_event_query_run)) != 1) {
        if($query_num_rows == 0) {
            echo '
                  <script>
                    alert("No such main event exists!. Redirecting... \nRemove the incorrect event and try again later!");
                    setTimeout(`window.location="index.php"`,1);
                  </script>
                ';
        }
        if ($query_num_rows > 1) {
            echo '
                  <script>
                    alert("Multiple main events of same name exist!. Redirecting... \nRemove the incorrect event and try again later!");
                    setTimeout(`window.location="index.php"`,1);
                  </script>
                ';
        }
      }else{
       while ($row = mysqli_fetch_assoc($sub_event_query_run)) {
              $main_event_id = $row['event_id'];
              $sub_event_id = $row['curr_no_of_sub_events'] + 1;
            } 
      } 
      if ($sub_event_id == 0 || $main_event_id == 0) {
            echo '
                  <script>
                    alert("Please select a main event to which sub-event needs to be added from the list!. Redirecting... \nRemove the incorrect event and try again later!");
                    setTimeout(`window.location="index.php"`,1);
                  </script>
                ';
      }
      $query = "INSERT INTO $society_sub_event_table_name (`main_event_id`, `sub_event_id`, `sub_event_name`, `sub_event_date`, `sub_event_time`, `sub_event_year`, `sub_event_venue`, `sub_event_no_of_students`, `sub_event_speaker`, `sub_event_poster`, `sub_event_brief_bio`, `sub_event_report`) VALUES ('$main_event_id', '$sub_event_id', '$sub_event_name', '$sub_event_date', '$sub_event_time', '$sub_event_year', '$sub_event_venue', '$sub_event_no_of_students', '$sub_event_speaker', '$sub_event_poster_name', '$sub_event_bio', '$sub_event_report')";
    
      if (mysqli_query($conn,$query)) {
        $another_query = "UPDATE $society_table_name SET `curr_no_of_sub_events` = $sub_event_id where `event_id` = '$main_event_id'";
        if (!mysqli_query($conn,$another_query)) {
           echo '
                  <script>
                    alert("There was an error in updating the main event table!. Redirecting... \nRemove the incorrect event and try again later!");
                    setTimeout(`window.location="index.php"`,1);
                  </script>
                ';
        }
      }
        else{
           echo '
                  <script>
                    alert("There was an error in adding the sub-event!!. Redirecting... \nRemove the incorrect event and try again later!");
                    setTimeout(`window.location="index.php"`,1);
                  </script>
                ';
      }
        
  // GALLERY Images
        $valid_formats = array("jpg", "jpeg", "png", "gif", "zip", "bmp", "JPG", "JPEG", "PNG", "GIF", "ZIP", "BMP");
        $path = "societies_data/".$society_name_CAPS."/uploads/gallery/sub_events/"; // Upload directory
        $count = 0;
        $error = 0;
        foreach ($_FILES['sub_event_gallery_images']['name'] as $f => $name) {     
          if ($_FILES['sub_event_gallery_images']['error'][$f] == 4) {
            $error = 1;
              echo '
              <script>
              alert("There was an error in inserting gallery images. Redirecting... \nRemove the incorrect event and try again later!");
              setTimeout(`window.location="index.php"`,1);
              </script>
            ';
              //continue; // Skip file if any error found
          }
          if ($_FILES['sub_event_gallery_images']['error'][$f] == 0) {             
              if ($_FILES['sub_event_gallery_images']['size'][$f] > $max_file_size) {
                  $message[] = "$name is too large!.";
                  $error = 1;
                 echo '
                  <script>
                    alert("'.$name.' is too large!. \nRemove the incorrect event and proceed with smaller image");
                    setTimeout(`window.location="index.php"`,1);
                  </script>
                ';
                  //continue; // Skip large files
              }
          elseif( ! in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats) ){
            $error = 1;
            $message[] = "$name is not a valid format";
            echo '
              <script>
              alert("'.$name.' is not a valid format! \nRemove the incorrect event and proceed with valid format images i.e. jpg, jpeg, png, gif, zip, bmp");
              setTimeout(`window.location="index.php"`,1);
              </script>
            ';
            //continue; // Skip invalid file formats
          }
              else{ // No error found! Move uploaded files
                $sub_event_id = NULL;
                  if(move_uploaded_file($_FILES["sub_event_gallery_images"]["tmp_name"][$f], $path.$name)){
                    $sql_query = "select `sub_event_id` from $society_sub_event_table_name where `main_event_id` = '$main_event_id' ORDER BY sub_event_id desc LIMIT 1";
                    if($sql_query_run = mysqli_query($conn,$sql_query)){
                      $query_num_rows = mysqli_num_rows($sql_query_run);
                      if ($query_num_rows == 1) {
                      while ($row = mysqli_fetch_assoc($sql_query_run)) {
                        $sub_event_id = $row['sub_event_id'];
                      }
                    }else{
                        $error = 1;
                        echo '
                        <script>
                          alert("There was an error in uploading gallery images. Contact developer. Redirecting... \nRemove the incorrect event and try again later!");
                          setTimeout(`window.location="index.php"`,1);
                        </script>
                        ';
                      }
                    }else{
                      echo '
                        <script>
                          alert("Contact developer. Check Query. Redirecting... \nRemove the incorrect event and try again later!");
                          setTimeout(`window.location="index.php"`,1);
                        </script>
                        ';
                    }
                    $sql = "INSERT INTO `$event_gallery` (`main_event_id`, `sub_event_id`, `sub_event_image`) VALUES ('$main_event_id', '$sub_event_id', '$name')";
                    if(!mysqli_query($conn, $sql)){
                      $error = 1;
                        echo '
                        <script>
                          alert("Contact developer. Check gallery insertion query. Redirecting... \nRemove the incorrect event and try again later!");
                          setTimeout(`window.location="index.php"`,1);
                        </script>
                        ';
                    }else{
                      $count++; // Number of successfully uploaded file
                    }
                      
                  }
              }
          }
      }
      if($error == 1){
          echo '
              <script>
              alert("There was an error in inserting gallery images. Redirecting... \nRemove the incorrect event and try again later!");
              setTimeout(`window.location="index.php"`,1);
              </script>
            ';
        } 
      // sub-event ATTENDANCE Images
        $path = "societies_data/".$society_name_CAPS."/uploads/attendance/sub_events/"; // Upload directory
        $count_sub_event_attendance = 0;
        $error = 0;
        foreach ($_FILES['sub_event_attendance_image']['name'] as $f => $name) {     
          if ($_FILES['sub_event_attendance_image']['error'][$f] == 4) {
            $error = 1;
              echo '
              <script>
              alert("There was an error in inserting attendance images. Redirecting... \nRemove the incorrect event and try again later!");
              setTimeout(`window.location="index.php"`,1);
              </script>
            ';
              //continue; // Skip file if any error found
          }
          if ($_FILES['sub_event_attendance_image']['error'][$f] == 0) {             
              if ($_FILES['sub_event_attendance_image']['size'][$f] > $max_file_size) {
                  $message[] = "$name is too large!.";
                  $error = 1;
                 echo '
                  <script>
                    alert("'.$name.' is too large!. \nRemove the incorrect event and proceed with smaller image");
                    setTimeout(`window.location="index.php"`,1);
                  </script>
                ';
                  //continue; // Skip large files
              }
          elseif( ! in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats) ){
            $error = 1;
            $message[] = "$name is not a valid format";
            echo '
              <script>
              alert("'.$name.' is not a valid format! \nRemove the incorrect event and proceed with valid format images i.e. jpg, jpeg, png, gif, zip, bmp");
              setTimeout(`window.location="index.php"`,1);
              </script>
            ';
            //continue; // Skip invalid file formats
          }
              else{ // No error found! Move uploaded files
                $sub_event_id = NULL;
                  if(move_uploaded_file($_FILES["sub_event_attendance_image"]["tmp_name"][$f], $path.$name)){
                    $sql_query = "select `sub_event_id` from $society_sub_event_table_name where `main_event_id` = '$main_event_id' ORDER BY sub_event_id desc LIMIT 1";
                    if($sql_query_run = mysqli_query($conn,$sql_query)){
                      $query_num_rows = mysqli_num_rows($sql_query_run);
                      if ($query_num_rows == 1) {
                      while ($row = mysqli_fetch_assoc($sql_query_run)) {
                        $sub_event_id = $row['sub_event_id'];
                      }
                    }else{
                      $error = 1;
                        echo '
                        <script>
                          alert("There was an error in uploading attendance images. Contact developer. Redirecting... \nRemove the incorrect event and try again later!");
                          setTimeout(`window.location="index.php"`,1);
                        </script>
                      ';
                      }
                    }else{
                     $error = 1;
                        echo '
                        <script>
                          alert("Contact developer. Check Query. Redirecting... \nRemove the incorrect event and try again later!");
                          setTimeout(`window.location="index.php"`,1);
                        </script>
                        ';
                    }
                    $sql = "INSERT INTO `$event_gallery` (`main_event_id`, `sub_event_id`, `sub_event_attendance_image`) VALUES ('$main_event_id', '$sub_event_id', '$name')";
                    if(!mysqli_query($conn, $sql)){
                      $error = 1;
                        echo '
                        <script>
                          alert("Contact developer. Check attendance insertion query. Redirecting... \nRemove the incorrect event and try again later!");
                          setTimeout(`window.location="index.php"`,1);
                        </script>
                        ';
                    }else{
                      $count_sub_event_attendance++; // Number of successfully uploaded file
                    }
                      
                  }
              }
          }
      }
        
    if($error == 0){
          header('Location: index.php');
          exit; 
        } 
  }
 }
}
?>


<html>
<head>
<title>The Society Fair</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
<link rel="stylesheet" type="text/css" href="css/homePageStyles.css">
<link rel="stylesheet" type="text/css" href="css/homePageStylesAfterLogin_1.css">
<link rel="stylesheet" type="text/css" href="css/homePageStylesAfterLogin_2.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<!--LIGHTBOX-->
<link rel="stylesheet" type="text/css" href="lightbox2-master/dist/css/lightbox.css">
<script src="lightbox2-master/dist/js/lightbox-plus-jquery.js"></script>

<!-- DELETING a sub-event-->
<script type="text/javascript">
  var delete_main_event_id;
  var delete_sub_event_id;
function delete_sub_event(main_event_id, sub_event_id)
{
  /*window.alert(""+main_event_id+" "+sub_event_id+" ");*/
     if(confirm('Are you sure about removing this record?'))
     {
        window.location.href="index.php?delete_main_event_id="+main_event_id+"&delete_sub_event_id="+sub_event_id;
     }
     //delete_main_event_id=main_event_id;
     //delete_sub_event_id=sub_event_id;
     //+"&delete_sub_event_id="+sub_event_id
}
</script>

<?php
if(loggedin()){
  
    if(isset($_GET['delete_main_event_id']) && isset($_GET['delete_sub_event_id']))
  {
       $delete_main_event_id = $_GET['delete_main_event_id'];
       $delete_sub_event_id = $_GET['delete_sub_event_id'];
        $delete_sub_event_query_1 = "DELETE FROM `$society_sub_event_table_name` WHERE `main_event_id` = '$delete_main_event_id' and `sub_event_id` = '$delete_sub_event_id'";
        $delete_sub_event_query_2 = "DELETE FROM `$event_gallery` where `main_event_id` = '$delete_main_event_id' and `sub_event_id` = '$delete_sub_event_id'";
        $help_query = "select `curr_no_of_sub_events` from $society_table_name where `event_id` = '$delete_main_event_id' ";
        $help_query_run = mysqli_query($conn,$help_query);
        $curr_no_of_sub_events = 0;
        if(mysqli_num_rows($help_query_run) == 1){
          while ($row = mysqli_fetch_assoc($help_query_run)) {
            $curr_no_of_sub_events = $row['curr_no_of_sub_events'];
          }
        }
        $curr_no_of_sub_events--;
        $delete_sub_event_query_3 = "UPDATE `$society_table_name` SET `curr_no_of_sub_events` = '$curr_no_of_sub_events' where   `event_id` = '$delete_main_event_id';
       ";
       
       if(!(mysqli_query($conn,$delete_sub_event_query_1) && mysqli_query($conn,$delete_sub_event_query_2) && mysqli_query($conn,$delete_sub_event_query_3))){
          echo '
          <script>
          alert("ERROR deleting sub-event!");
          </script>
        ';
        }
       header("Location: index.php");
  }
}
?>

<!-- DELETING an event-->
<script type="text/javascript">
  var delete_MAIN_event_id;
function delete_main_event(main_event_id)
{
  /*window.alert(""+main_event_id+"");*/
     if(confirm('Sure To Remove This Record ?'))
     {
        window.location.href="index.php?delete_MAIN_event_id="+main_event_id;
     }
     //delete_main_event_id=main_event_id;
     //delete_sub_event_id=sub_event_id;
     //+"&delete_sub_event_id="+sub_event_id
}
</script>

<?php
if(loggedin()){
  
    if(isset($_GET['delete_MAIN_event_id']))
  {
       $delete_MAIN_event_id = $_GET['delete_MAIN_event_id'];
        $delete_main_event_query_1 = "DELETE FROM `$society_table_name` WHERE `event_id` = '$delete_MAIN_event_id'";
        $delete_main_event_query_2 = "DELETE FROM `$event_gallery` WHERE `main_event_id` = '$delete_MAIN_event_id'";
        
       if((!mysqli_query($conn,$delete_main_event_query_1) || !mysqli_query($conn,$delete_main_event_query_2) ) ){
          echo '
          <script>
          alert("ERROR deleting event!");
          </script>
        ';
        }
       header("Location: index.php");
  }
}
?>

</head>
<body onload="hideLoader()">
<div id="loading"></div>
<!-- Sidebar/menu -->
<nav class="w3-sidebar w3-red w3-collapse w3-top w3-large w3-padding" style="z-index:3; width:300px; font-weight:bold;" id="mySidebar"><br>
  <a href="javascript:void(0)" onclick="w3_close()" class="w3-button w3-hide-large" style="width:100%;font-size:22px; margin-top: -10%; margin-bottom: 5%; margin-left: -18%">Close Menu</a>
  <div class="w3-container">
    <a class="navbar-brand pull-left visible-sm visible-md visible-lg" target="_blank" href="http://www.thapar.edu/" style="text-decoration: none;">
      <div class="LOGO img-responsive" style="padding-bottom: 5%">
        <img src="images/tulogo2.png" style="width:80%; height: 20%; border-radius: 12px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
      </div>
      <h3 class="w3-padding-44" style="font-size: 20px;">THAPAR INSTITUE OF ENGINEERING &AMP TECHNOLOGY</h3>
    </a>
    <br>
  </div>
  <div class="w3-bar-block">
    <!--a href="#" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Home</a-->
    <a href="#showcase" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Showcase</a>

    <?php
    if (!loggedin()) {
      echo '<a onclick="document.getElementById(`id01`).style.display=`block`; w3_close();" class="w3-bar-item w3-button w3-hover-white">Society Coordinator Login</a>';
    }else{
      echo '<a onclick="document.getElementById(`ADD_EVENT`).style.display=`block`" class="w3-bar-item w3-button w3-hover-white">Add new event</a>';
      echo '<a onclick="document.getElementById(`ADD_SUB_EVENT`).style.display=`block`" class="w3-bar-item w3-button w3-hover-white">Add new sub - event</a>';
     echo '<a href="logout.php" class="w3-bar-item w3-button w3-hover-white">Logout</a>';
    }
    ?>  
    <a href="#contact" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white">Contact</a>
  </div>
  <div class="footer-copyright" style="margin-right:45%; margin-top:64%;">
            <div class="container" style="font-size:60%; color: black; padding-left: 2%;"
            style="border-style: solid; border-width: 1px; border-color: black; padding-left: 10%; padding-right: 10%">Developer : @Prayas Arora
            </div>
          </div>
</nav>

<!-- Top menu on small screens -->
<header class="w3-container w3-hide-large w3-red w3-xlarge w3-padding">
  <a href="javascript:void(0)" class="w3-button w3-red w3-margin-right" onclick="w3_open()">☰</a>
  <span>THAPAR INSTITUE OF ENGINEERING &AMP TECHNOLOGY</span>
</header>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- Login Form-->
<div id="loginForm">
  <div id="id01" class="modal">
    <form class = "modal-content animate" action = "<?php echo $current_file; ?>" method = "POST">
      <div class="imgcontainer">
        <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close">&times;</span>
        <img src="images/Avatar/admin.png" alt="Avatar" class="avatar">
      </div>

      <div class="container-fluid">
          <label><b>Username</b></label>
          <input type="text" placeholder="Enter Username" name="username" minlength="4" maxlength="50" autocomplete="off" required>

          <label><b>Password</b></label>
          <input type="password" placeholder="Enter Password" name="password" minlength="6" autocomplete="off" required>
            
          <button type="submit">Login</button>
      
          <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
        </div>
    </form>
  </div>
</div>

<!-- Add Event Form-->
<div id="newEventForm">
  <div id="ADD_EVENT" class="modal">
    <form class = "modal-content animate" action = "<?php echo $current_file; ?>" method = "POST" enctype="multipart/form-data">
      <div class="imgcontainer">
        <span onclick="document.getElementById('ADD_EVENT').style.display='none'" class="close" title="Close">&times;</span>
      </div>

      <div class="container-fluid">
        <input type="hidden" name="newEventForm" value="true" autocomplete="off">
          <label><b>Event Name</b></label>
          <input type="text" placeholder="Enter Event Name" name="event_name" maxlength="100" autocomplete="off" required >

          <label><b>Date</b></label>
          <input type="text" placeholder="Enter Event Date" name="event_date" maxlength="30" autocomplete="off">

          <label><b>Time</b></label>
          <input type="text" placeholder="Enter Event Time" name="event_time" maxlength="30" autocomplete="off">

          <label><b>Year of Event</b></label>
          <input type="text" placeholder="Enter Year of Event (2016,2017..)" name="event_year" maxlength="10" autocomplete="off" required>

          <label><b>Venue</b></label>
          <input type="text" placeholder="Enter Event Venue" name="event_venue" maxlength="30" autocomplete="off">

          <label><b>No. of students</b></label>
          <input type="text" placeholder="Enter the no. of students who attended the event" name="no_of_students"autocomplete="off">

          <label><b>Speaker</b></label>
          <input type="text" placeholder="Enter Speaker Name" name="event_speaker" autocomplete="off">
          
          <label><b>Brief Bio:</b></label>
          <textarea id="bio_text" cols="69%" rows="8" style="width: 100%;" name="event_bio" placeholder="Enter a brief bio of the event" ></textarea><br><br>

          <label><b>Event Report:</b><br></label>
          <textarea id="report_text" cols="69%" rows="8" style="width: 100%;" name="event_report" placeholder="Enter a brief report of the event" ></textarea><br><br>

          <label><b>Attendance &nbsp&nbsp</b></label>
          <input type="file" name="event_attendance_image[]"  multiple="multiple" accept="image/*" autocomplete="off"><br><br>

          <label><b>Event poster &nbsp</b></label>
          <input type="file" name="poster_image" accept="image/*" autocomplete="off"><br><br>

          <label><b>Event images</b></label>
          <input type="file" name="gallery_images[]" multiple="multiple" autocomplete="off" accept="image/*"/><br><br>

          <button type="submit" name = "submit_newEventForm">Add Event</button>
      
          <button type="button" onclick="document.getElementById('ADD_EVENT').style.display='none'" class="cancelbtn">Cancel</button>
        </div>
    </form>
  </div>
</div>

<!-- Add sub-event Form-->
<div id="newSubEventForm">
  <div id="ADD_SUB_EVENT" class="modal">
    <form class = "modal-content animate" action = "<?php echo $current_file; ?>" method = "POST" enctype="multipart/form-data">
      <div class="imgcontainer">
        <span onclick="document.getElementById('ADD_SUB_EVENT').style.display='none'" class="close" title="Close">&times;</span>
      </div>

      <div class="container-fluid">
        <input type="hidden" name="newSubEventForm" value="true" autocomplete="off">
          <label><b>Select main event</b></label>
          <br>
          
            <select name="BIG_event_name">
            <option>Select</option>
            <?php
              $query = "select `event_name` from $society_table_name";
              $query_run = mysqli_query($conn,$query);
              while ($fetched_row = mysqli_fetch_assoc($query_run)) {
                $BIG_event_name = $fetched_row['event_name'];
                
                echo '<option value="'.$BIG_event_name.'">'.$BIG_event_name.'</option>';
              }
            ?>
        </select>
        <br><br>

          <label><b>Sub Event Name</b></label>
          <input type="text" placeholder="Enter Event Name" name="sub_event_name" maxlength="100" autocomplete="off" required>
          
          <label><b>Date</b></label>
          <input type="text" placeholder="Enter Event Date" name="sub_event_date" maxlength="30" autocomplete="off" required>

          <label><b>Year of Event</b></label>
          <input type="text" placeholder="Enter Year of Event (2016,2017..)" name="sub_event_year" autocomplete="off" maxlength="10" required>

          <label><b>Time</b></label>
          <input type="text" placeholder="Enter Event Time" name="sub_event_time" autocomplete="off" maxlength="30">

          <label><b>Venue</b></label>
          <input type="text" placeholder="Enter Event Venue" name="sub_event_venue" autocomplete="off" maxlength="30">

          <label><b>No. of students</b></label>
          <input type="text" placeholder="Enter the no. of students who attended the event" name="sub_event_no_of_students" autocomplete="off">

          <label><b>Speaker</b></label>
          <input type="text" placeholder="Enter Speaker Name" name="sub_event_speaker" autocomplete="off">
          
          <label><b>Brief Bio:</b></label>
          <textarea id="bio_text" cols="69%" rows="8" style="width: 100%;" name="sub_event_bio" placeholder="Enter a brief bio about the event" ></textarea><br><br>

          <label><b>Event Report:</b></label>
          <textarea id="sub_event_report_text" cols="69%" rows="8" style="width: 100%;" name="sub_event_report" placeholder="Enter a brief report of the sub-event" ></textarea><br><br>

          <label><b>Attendance &nbsp&nbsp</b></label>
          <input type="file" name="sub_event_attendance_image[]" multiple="multiple" accept="image/*" autocomplete="off" required><br><br>

          <label><b>Event poster &nbsp</b></label>
          <input type="file" name="sub_event_poster_image" accept="image/*" autocomplete="off" required><br><br>

          <label><b>Event images</b></label>
          <input type="file" name="sub_event_gallery_images[]" multiple="multiple" autocomplete="off" accept="image/*"/><br><br>

          <button type="submit"  name = "submit_newSubEventForm">Add sub Event</button>
      
          <button type="button" onclick="document.getElementById('ADD_SUB_EVENT').style.display='none'" class="cancelbtn">Cancel</button>
        </div>
    </form>
  </div>
</div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:340px;margin-right:40px">

  <!-- Header -->
  <div class="w3-container" style="margin-top:10px" id="showcase">
    <h1 class="w3-jumbo"><b>The Society Fair</b></h1>
    <h1 class="w3-xxxlarge w3-text-red"><b>Showcase.</b></h1>
    <hr style="width:50px;border:5px solid red" class="w3-round">
  </div>
  
  <!-- Photo grid (modal) -->
  <div class="w3-row-padding">
    <div class="w3-third" >
      <a href="#CCS" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white"> <img src="images/CCS/ccslogo.png" style="width:110%; height:35%; padding-right: 5%; " alt="Creative Computing Society"> </a>
    </div>

    <div class="w3-third">
      <a href="#TEDx" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white"> <img src="images/TEDx/tedxlogo.png" style="padding-top: 30%;  width:100%;" alt="TEDx"> </a>
    </div>

    <div class="w3-third">
        <a href="#MSC" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white"> <img src="images/MSC/logomsc.png" style="width:100%;" alt="Microsoft Student Chapter"> </a>
    </div>
  </div>

  <div class="w3-row-padding">
    <div class="w3-third">
      <a href="#OWASP" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white"> <img src="images/OWASP/owasplogo.png" style="width:75%;"  alt="Open Web Application Security Project"> </a>
    </div>

    <div class="w3-third">
        <a href="#IEEE" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white"> <img src="images/IEEE/ieeelogo.jpg" style="width:60%;" alt="IEEE"> </a>
    </div>

    <div class="w3-third">
        <a href="#SSA" onclick="w3_close()" class="w3-bar-item w3-button w3-hover-white"> <img src="images/SSA/ssa.png" style="width:100%;"  alt="Spiritual Scientist Alliance"> </a>
    </div>

  </div>

  <!-- CCS -->
    <div class="w3-container" style="margin-top:75px">
      <h1 class="w3-xxxlarge w3-text-red" id = "CCS"><b>Creative Computing Society.</b></h1>
      <hr style="width:50px; border:5px solid red" class="w3-round">
      <p style="text-align: justify; text-align-last: left;"">The Creative Computing Society of Thapar University, commonly known as CCS, has been founded with the vision of nurturing and fostering the youth generation and creating awareness about the reigning era of technology. The student run committee tirelessly works to achieve it's ambition of bridging the gap between students, computers and technology. A highly active group of people, we organize two major events in a year, namely Chakravyuh and Helix. Apart from that we are operational over the year and various technical and ethical learning workshops on hacking, coding, and ethical learning are conducted by experts of the field. CCS is pledged and devoted to provide a strong rostrum for all the budding and aspiring technocrats of Thapar University, so that they can fulfill our aim to make mankind a developed, prosperous and connect it with the trends of tomorrow.
      </p>
      </div>
      <?php eventScript($conn, 'ccs', 'CCS', 'Creative Computing Society, Thapar University', 'ccs_events', 'ccs_sub_events', 'ccs_event_gallery', 'https://www.facebook.com/CCSTU/', $ccs_delete); ?>
      
  
  <!-- Spiritual Scientist Alliance -->
  <div class="w3-container" id="SSA" style="margin-top:75px">
    <h1 class="w3-xxxlarge w3-text-red"><b>Spiritual Scientist Alliance.</b></h1>
    <hr style="width:50px;border:5px solid red" class="w3-round">
    <p style="text-align: justify; text-align-last: left;"">SSA – Spiritual Scientist’s Alliance is one of the healthiest functioning societies of Thapar. The official traditional fest of Thapar also known VIRSA – The essence of Punjab was very efficiently organised and managed by this society. The fest commenced with the Musical night on its first day. It comprised the perfect mixture traditional as well as western music. On the second day there was Bhangra wars between various teams, it definitely elated the Punjabi crowd. It was followed was Jalwa - e- virasat, a fashion show with a traditional pinch to encourage and support Indian traditional wears. On the finale day of the fest there was great Punjabi fair within the university premises opened for all its staff and students. There was also a gatka performance in the fest performed by trained experts. There were rides, Dj, Punjabi dhaba various stalls selling numerous  items. The fair witnessed the presence of officials of Thapar and their applause.<br><br>
    Gurpurab is very well known festival for Punjab. Under the presidency of Dr Maninder Singh and General Secretary Bhavneet kaur  SSA organised Gurpurab celebrations for the college students and staff. A sukhmani sahib path was arranged with the campus followed by a customary langar. The students themselves took the various responsibilities like serving the food, washing dishes, taking care of the footwear. It was a treat for the students and the faculties in charge  to be a part of this genuine cause.
    </p>
  </div>
  <?php eventScript($conn, 'ssa', 'SSA', 'Spiritual Scientist Alliance, Thapar University', 'ssa_events', 'ssa_sub_events', 'ssa_event_gallery', 'https://www.facebook.com/ssathaparuniversity/', $ssa_delete); ?>
  <!-- TEDx -->
  <div class="w3-container" id="TEDx" style="margin-top:75px">
    <h1 class="w3-xxxlarge w3-text-red"><b>TEDx.</b></h1>
    <hr style="width:50px;border:5px solid red" class="w3-round">
    <p style="text-align: justify; text-align-last: left;"">In the spirit of ideas worth spreading, TEDx is a program of local, self-organized events that bring people together to share a TED-like experience. At a TEDx event, TED Talks video and live speakers combine to spark deep discussion and connection. These local, self-organized events are branded TEDx, where x = independently organized TED event. The TED Conference provides general guidance for the TEDx program, but individual TEDx events are self-organized.<br>
    (Subject to certain rules and regulations.)<br><br>

    Our event is called TEDxThaparUniversity, where x = independently organized TED event. At our TEDxThaparUniversity event, TEDTalks video and live speakers will combine to spark deep discussion and connection in a small group.
    </p>
  </div>
  <?php eventScript($conn, 'tedx', 'TEDx', 'TEDx, Thapar University', 'tedx_events', 'tedx_sub_events', 'tedx_event_gallery', 'https://www.facebook.com/tedxthaparuniversity/', $tedx_delete); ?>


  <!-- IEEE -->
  <div class="w3-container" id="IEEE" style="margin-top:75px">
    <h1 class="w3-xxxlarge w3-text-red"><b>IEEE.</b></h1>
    <hr style="width:50px;border:5px solid red" class="w3-round">
    <p style="text-align: justify; text-align-last: left;"">
                Our mission is to move ahead with all student branches together. We strive to make IEEE Thapar Student Chapter improve and progress by leaps and bounds, and at the same time make it more useful and valuable to the young student members and experienced faculty members. We do this by conducting seminars on Machine Learning and Data Analytics, events like project showcase and coding competitions.<br>It is headed by Dr Ashima Singh.<br>
    </p>
  </div>
      <?php eventScript($conn, 'ieee', 'IEEE', 'IEEE Student Chapter, Thapar University', 'ieee_events', 'ieee_sub_events', 'ieee_event_gallery', 'https://www.facebook.com/ieee.thapar/', $ieee_delete); ?>
  <!-- MSC -->
  <div class="w3-container" id="MSC" style="margin-top:75px">
    <h1 class="w3-xxxlarge w3-text-red"><b>Microsoft Student Chapter.</b></h1>
    <hr style="width:50px;border:5px solid red" class="w3-round">
    <p style="text-align: justify; text-align-last: left;"">Microsoft Student Chapter is a computing society formed by technology enthusiast in order to create a programming culture in the college.<br>
    It conducts various programming fests throughout the year some of them being :<br>
    MS Week<br>
    Inspirus<br>
    Rumble<br>
    All these fests have various events ranging from technical workshop to coding contests.
    </p>
  </div>
      <?php eventScript($conn, 'msc', 'MSC', 'Microsoft Student Chapter, Thapar University', 'msc_events', 'msc_sub_events', 'msc_event_gallery', 'https://www.facebook.com/msc2k17/', $msc_delete); ?>

  <!-- OWASP -->
  <div class="w3-container" id="OWASP" style="margin-top:75px">
    <h1 class="w3-xxxlarge w3-text-red"><b>OWASP.</b></h1>
    <hr style="width:50px;border:5px solid red" class="w3-round">
    <p style="text-align: justify; text-align-last: left;"">The Open Web Application Security Project (OWASP) is a worldwide not-for-profit organization with the main objective of increasing awareness about application software security.<br><br>
    The OWASP thapar student chapter mainly focuses on helping aspiring security geeks to learn and grow. Regular sessions such as meetings,workshops and events helps to share ideas frequently and implementing those ideas.
    Besides this, OWASP thapar student chapter also promotes students to work on projects.<br>
    We believe Deuteronomy Rabbah rightly said "In vain have you acquired knowledge if you have not imparted it to others."
    OWASP thapar student chapter encourages students to work more on their ideas and provides them with the platform to share their knowledge and experiences with others.
    </p>
  </div>
  <?php eventScript($conn, 'owasp', 'OWASP', 'OWASP Student Chapter, Thapar University', 'owasp_events', 'owasp_sub_events', 'owasp_event_gallery', 'https://www.facebook.com/owasptsc/', $owasp_delete); ?>
  <!-- The Team -->
  <!--div class="w3-row-padding w3-grayscale">
    <div class="w3-col m4 w3-margin-bottom">
      <div class="w3-light-grey">
        <img src="/w3images/team2.jpg" alt="John" style="width:100%">
        <div class="w3-container">
          <h3>John Doe</h3>
          <p class="w3-opacity">CEO & Founder</p>
          <p>Phasellus eget enim eu lectus faucibus vestibulum. Suspendisse sodales pellentesque elementum.</p>
        </div>
      </div>
    </div>
    <div class="w3-col m4 w3-margin-bottom">
      <div class="w3-light-grey">
        <img src="/w3images/team1.jpg" alt="Jane" style="width:100%">
        <div class="w3-container">
          <h3>Jane Doe</h3>
          <p class="w3-opacity">Designer</p>
          <p>Phasellus eget enim eu lectus faucibus vestibulum. Suspendisse sodales pellentesque elementum.</p>
        </div>
      </div>
    </div>
    <div class="w3-col m4 w3-margin-bottom">
      <div class="w3-light-grey">
        <img src="/w3images/team3.jpg" alt="Mike" style="width:100%">
        <div class="w3-container">
          <h3>Mike Ross</h3>
          <p class="w3-opacity">Architect</p>
          <p>Phasellus eget enim eu lectus faucibus vestibulum. Suspendisse sodales pellentesque elementum.</p>
        </div>
      </div>
    </div>
  </div-->
  <!-- Contact -->
  <div class="w3-container" id="contact" style="margin-top:75px">
    <h1 class="w3-xxxlarge w3-text-red"><b>Contact.</b></h1>
    <hr style="width:50px;border:5px solid red" class="w3-round">
    <p>Do you want to contact a society coordinator ? Fill out the form and fill in the details :) We'll deliver your message!</p>
    <form action = "SMTP_send_email.php" method = "POST" enctype="multipart/form-data">
      <div class="w3-section">
        <label><b>Name</b></label>
        <input class="w3-input w3-border" type="text" name="contact_name" placeholder="Your Name" autocomplete="off" required>
      </div>
      <div class="w3-section">
        <label><b>Email Address</b></label>
        <input class="w3-input w3-border" type="email" name="sender_email" placeholder="Your email address" autocomplete="off" required>
      </div>
      <div class="w3-section">
        <!--class="w3-input w3-border"-->
          <label><b>Select society</b></label>
          <br>
          
            <select name="contact_coordinator_id">
            <option>Select</option>
            <?php
              $query = "select  `id`, `firstname`, `lastname`, `society` from `users`";
              $query_run = mysqli_query($conn,$query);
              while ($fetched_row = mysqli_fetch_assoc($query_run)) {
                $society_name = $fetched_row['society'];
                $coordinator_name = "".$fetched_row['firstname']." ".$fetched_row['lastname']."";
                $id = $fetched_row['id'];
                echo '<option value="'.$id.'">'.$society_name.' ('.$coordinator_name.')';
              }
            ?>
            
        </select>
        <br><br>
      <div class="w3-section">
        <label><b>Message</b></label>
        <input class="w3-input w3-border" type="text" name="contact_message" autocomplete="off" required>
      </div>
      <button type="submit" class="w3-button w3-block w3-padding-large w3-red w3-margin-bottom" onclick="showLoader()">Send Message</button>
    </form>  
  </div>
<!-- End page content -->
</div>
<script>
  function showLoader() {
    document.getElementById("loading").style.display = "block";
  }
  function hideLoader() {
    document.getElementById("loading").style.display = "none";
  }
// Script to open and close sidebar
function w3_open() {
    document.getElementById("mySidebar").style.display = "block";
    document.getElementById("myOverlay").style.display = "block";
}
function w3_close() {
    document.getElementById("mySidebar").style.display = "none";
    document.getElementById("myOverlay").style.display = "none";
}
// Get the Login Form modal
var modal_1 = document.getElementById('id01');
// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal_1) {
        modal_1.style.display = "none";
    }
}
// Get the ADD_EVENT Form modal
var modal_2 = document.getElementById('ADD_EVENT');
// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal_2) {
      modal_2.style.display = "none";
  }
}
// Modal Image Gallery
//function onClick(element) {
  //document.getElementById("img01").src = element.src;
  //document.getElementById("modal01").style.display = "block";
  //var captionText = document.getElementById("caption");
  //captionText.innerHTML = element.alt;
//}
function toggle(temp) {
    var x = document.getElementById(temp);
    if (x.style.display === 'none') {
        x.style.display = 'block';
    } else {
        x.style.display = 'none';
    }
}
function mess(){
  alert("The event has been successfully added!");
  return true;
}
lightbox.option({
      'fitImagesInViewport': true,
      'wrapAround': false,
      'resizeDuration': 400,
      'maxWidth': .7*(screen.width),
      'maxHeight': .7*(screen.height)
    })
//Jquery
$("a > li").click(function(event){
            event.stopPropagation();
        });

</script>
</body>
</html>