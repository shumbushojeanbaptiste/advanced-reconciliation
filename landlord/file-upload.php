

<?php  

if(isset($_POST["submit"])) {
    	$id1=$_POST['id1'];
        // Set image placement folder
        $target_dir = "../img/profile_pics/";
        // Get file path
        $target_file = $target_dir . basename($_FILES["profileImage"]["name"]);
        // Get file extension
        $imageExt = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Allowed file types
        $allowd_file_ext = array("jpg", "jpeg", "png");
        

        if (!file_exists($_FILES["profileImage"]["tmp_name"])) {
            echo '<script language="javascript">
                  alert("Select new image to upload. ");
                  window.location.href = "?file=profile";
                  </script>';
        } else if (!in_array($imageExt, $allowd_file_ext)) {
            echo '<script language="javascript">
                  alert("Allowed file formats .jpg, .jpeg and .png. ");
                  window.location.href = "?file=profile";
                  </script>';
        } else if ($_FILES["profileImage"]["size"] > 2097152) {
            echo '<script language="javascript">
                  alert("File is too large. File size should be less than 2 megabytes. ");
                  window.location.href = "?file=profile";
                  </script>';
        } else {
            if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $target_file)) {
                $sql1 = "SELECT *from tbl_admins where id = '$id1'";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->execute(); 
                echo $stmt1->rowCount();
                if($stmt1->rowCount() > 0) {
                    $sql2 = "UPDATE tbl_admins set profile_pic = '$target_file' where id='$id1'";
                    $stmt2 = $conn->prepare($sql2);
                     if($stmt2->execute()){
                        echo '<script language="javascript">
                              alert("Profile Updated successfully. ");
                              window.location.href = "?file=profile";
                              </script>';
                        echo'<meta http-equiv="refresh"'.'content="1;URL=?file=profile">'; 
                     }
                }
                
            } else {
                echo '<script language="javascript">
                      alert("Image coudn\'t be uploaded. ");
                      window.location.href = "?file=profile";
                      </script>';
            }
        }

    }
?>