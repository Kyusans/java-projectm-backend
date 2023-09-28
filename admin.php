<?php 
    include "headers.php";

    class Admin{

      function getAllStaff(){
        include "connection.php";
        $sql = "SELECT * FROM tblusers WHERE user_level < 100 ORDER BY user_fullName";
        $stmt = $conn->prepare($sql);
        $returnValue = 0;
        if($stmt->execute()){
            if($stmt->rowCount() > 0){
                $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $returnValue = json_encode($rs);
            }
        }
        return $returnValue;
      }

      function addStaff($json){
        include "connection.php";
        $json = json_decode($json, true);
        //{"userName":"joey", "password":"joey", "fullName":"Joey Joey", "email":"jioe@gmail.com"}
        $sql = "INSERT INTO tblusers(user_username, user_password , user_fullName, user_email, user_level) ";
        $sql .= " VALUES( :userName, :password, :fullName, :email, 90)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":userName", $json["user_username"]);
        $stmt->bindParam(":password", $json["user_password"]);
        $stmt->bindParam(":fullName", $json["user_fullName"]);
        $stmt->bindParam(":email", $json["user_email"]);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? 1 : 0;
      }

      function updateStaff($json){
        include "connection.php";
        // {"userName":"joeeeee", "password":"joe123", "fullName":"Joey Joey", "email":"jioe@gmail.com", "userId":3}
        $json = json_decode($json, true);
        $sql = "UPDATE tblusers ";
        $sql .= "SET user_username=:userName, user_password=:password, user_fullName=:fullName, user_email=:email ";
        $sql .= "WHERE user_id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":userName", $json["user_userName"]);
        $stmt->bindParam(":password", $json["user_password"]);
        $stmt->bindParam(":fullName", $json["user_fullName"]);
        $stmt->bindParam(":email", $json["user_email"]);
        $stmt->bindParam(":userId", $json["user_userId"]);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? 1 : 0;
      }

      function deleteStaff($json){
        //{"userId":3}
        include "connection.php";
        $json = json_decode($json, true);
        $sql = "DELETE FROM tblusers WHERE user_id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":userId", $json["userId"]);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? 1 : 0;
      }

      function getUpdateHistory() {
        include "connection.php";
        $oneMonthAgo = date('Y-m-d', strtotime('-1 month'));
        $sql = "SELECT a.uphist_dateUpdated, b.user_fullName, c.stud_fullName ";
        $sql .= "FROM tblupdatestudenthistory as a ";
        $sql .= "INNER JOIN tblusers as b ON a.uphist_userId = b.user_id ";
        $sql .= "INNER JOIN tblstudents as c ON a.uphist_studId = c.stud_id ";
        $sql .= "WHERE a.uphist_dateUpdated >= :oneMonthAgo";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':oneMonthAgo', $oneMonthAgo, PDO::PARAM_STR);
        $stmt->execute();
        $returnValue = 0;
        if ($stmt->rowCount() > 0) {
            $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $returnValue = json_encode($rs);
        }
        return $returnValue;
      }
    
      function getAddStudentHistory() {
        include "connection.php";
        $oneMonthAgo = date('Y-m-d', strtotime('-1 month'));
        $sql = "SELECT a.addhist_dateAdded, b.user_fullName, c.stud_fullName ";
        $sql .= "FROM tbladdstudenthistory as a ";
        $sql .= "INNER JOIN tblusers as b ON a.addhist_userId = b.user_id ";
        $sql .= "INNER JOIN tblstudents as c ON a.addhist_studSchoolId = c.stud_school_id ";
        $sql .= "WHERE a.addhist_dateAdded >= :oneMonthAgo";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':oneMonthAgo', $oneMonthAgo, PDO::PARAM_STR);
        $stmt->execute();
        $returnValue = 0;
        if ($stmt->rowCount() > 0) {
            $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $returnValue = json_encode($rs);
        }
        return $returnValue;
      }
    
      function getDeleteHistory() {
        include "connection.php";
        $oneMonthAgo = date('Y-m-d', strtotime('-1 month'));
        $sql = "SELECT a.delhist_id, a.delhist_dateDeleted, a.delhist_fullName, b.user_fullName ";
        $sql .= "FROM tbldeletehistory as a ";
        $sql .= "INNER JOIN tblusers as b ON a.delhist_userId = b.user_id ";
        $sql .= "WHERE a.delhist_dateDeleted >= :oneMonthAgo";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':oneMonthAgo', $oneMonthAgo, PDO::PARAM_STR);
    
        $stmt->execute();
        $returnValue = 0;
        if ($stmt->rowCount() > 0) {
            $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $returnValue = json_encode($rs);
        }
        return $returnValue;
      }

      function updateAdmin($json){
        // {"user_id": 1, "user_username" : "admin", "user_password" : "admin"}
        include "connection.php";
        $jsonData = json_decode($json, true);
    
        $sql = "UPDATE tblusers SET user_username = :username, user_password = :password ";
        $sql .= "WHERE user_id = :userId";
    
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId', $jsonData['user_id']);
        $stmt->bindParam(':password', $jsonData['user_password']);
        $stmt->bindParam(':username', $jsonData['user_username']);
        
        try {
            $stmt->execute();
            return $stmt->rowCount() > 0 ? 1 : 0;
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
      }

      function getSelectedDeletedStudent($json){
        include "connection.php";
        $json = json_decode($json, true);
        $sql = "SELECT b.* ";
        $sql .= "FROM tbldeletehistory as a ";
        $sql .= "INNER JOIN tbldeletedstudent as b ON a.delhist_fullName = b.delstud_fullName ";
        $sql .= "WHERE a.delhist_id = :delhistId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':delhistId', $json["delhist_id"]);
        $stmt->execute();
        $returnValue = 0;
        if ($stmt->rowCount() > 0){
          $rs = $stmt->fetch(PDO::FETCH_ASSOC);
          $returnValue = json_encode($rs);
        }
        return $returnValue;
      }

      function retrieveStudent($json){
        include "connection.php";
        $json = json_decode($json, true);
        $conn->beginTransaction();
        try {
          $sql = "INSERT INTO tblstudents(stud_id, stud_schoolId, stud_fullName, stud_birthday, stud_birthplace, stud_gender, stud_religion, stud_address, stud_email, ";
          $sql .= "stud_contactNumber, stud_prevSchool, stud_course, stud_gradeLevel, stud_yearGraduated, stud_fatherName, stud_fatherOccupation, stud_fatherContactNumber, ";
          $sql .= "stud_motherName, stud_motherOccupation, stud_motherContactNumber, stud_emergencyName, stud_emergencyRelationship, stud_emergencyPhone, stud_emergencyAddress, ";
          $sql .= "stud_school_id) ";
          $sql .= "VALUES(:studId, :schoolId, :fullName, :birthday, :birthplace, :gender, :religion, :address, :email, ";
          $sql .= ":contactNumber, :prevSchool, :course, :gradeLevel, :yearGraduated, :fatherName, :fatherOccupation, :fatherContactNumber, ";
          $sql .= ":motherName, :motherOccupation, :motherContactNumber, :emergencyName, :emergencyRelationship, :emergencyPhone, :emergencyAddress, :schoolId) ";
          $stmt = $conn->prepare($sql);
          $stmt->bindParam("studId", $json["stud_id"]);
          $stmt->bindParam(":schoolId", $json["stud_schoolId"]);
          $stmt->bindParam(":fullName", $json["stud_fullName"]);
          $stmt->bindParam(":birthday", $json["stud_birthday"]);
          $stmt->bindParam(":birthplace", $json["stud_birthplace"]);
          $stmt->bindParam(":gender", $json["stud_gender"]);
          $stmt->bindParam(":religion", $json["stud_religion"]);
          $stmt->bindParam(":address", $json["stud_address"]);
          $stmt->bindParam(":email", $json["stud_email"]);
          $stmt->bindParam(":contactNumber", $json["stud_contactNumber"]);
          $stmt->bindParam(":prevSchool", $json["stud_prevSchool"]);
          $stmt->bindParam(":course", $json["stud_course"]);
          $stmt->bindParam(":gradeLevel", $json["stud_gradeLevel"]);
          $stmt->bindParam(":yearGraduated", $json["stud_yearGraduated"]);
          $stmt->bindParam(":fatherName", $json["stud_fatherName"]);
          $stmt->bindParam(":fatherOccupation", $json["stud_fatherOccupation"]);
          $stmt->bindParam(":fatherContactNumber", $json["stud_fatherContactNumber"]);
          $stmt->bindParam(":motherName", $json["stud_motherName"]);
          $stmt->bindParam(":motherOccupation", $json["stud_motherOccupation"]);
          $stmt->bindParam(":motherContactNumber", $json["stud_motherContactNumber"]);
          $stmt->bindParam(":emergencyName", $json["stud_emergencyName"]);
          $stmt->bindParam(":emergencyRelationship", $json["stud_emergencyRelationship"]);
          $stmt->bindParam(":emergencyPhone", $json["stud_emergencyPhone"]);
          $stmt->bindParam(":emergencyAddress", $json["stud_emergencyAddress"]);
          // echo "Sql: " . $sql . "<br/>";
          $stmt->execute();
          if($stmt->rowCount() <= 0) {
            $conn->rollBack();
            return 0;
          }
          $sql1 = "DELETE FROM tbldeletedstudent WHERE delstud_id = :delStudId";
          $stmt1 = $conn->prepare($sql1);
          $stmt1->bindParam(":delStudId", $json["delstud_id"]);
          $stmt1->execute();
        } catch (PDOException $e) {
          $conn->rollBack();
          return 0;
        }

        $sql2 = "DELETE FROM tbldeletedhistory WHERE delhist_fullName = :fullName";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bindParam(":fullName", $json["delstud_fullName"]);
        $stmt2->execute();
        $conn->commit();
        return 1;
      }
  }    

    $json = isset($_POST["json"]) ? $_POST["json"] : "0";
    $operation = isset($_POST["operation"]) ? $_POST["operation"] : "0";
    $admin = new Admin();

    switch($operation){
      case "getAllStaff":
        echo $admin->getAllStaff();
        break;
      case "addStaff":
        echo $admin->addStaff($json);
        break;
      case "updateStaff":
        echo $admin->updateStaff($json);
        break;
      case "deleteStaff":
        echo $admin->deleteStaff($json);
        break;
      case "getUpdateHistory":
        echo $admin->getUpdateHistory();
        break;
      case "getAddStudentHistory":
        echo $admin->getAddStudentHistory();
        break;
      case "getDeleteHistory":
        echo $admin->getDeleteHistory();
        break;
      case "updateAdmin":
        echo $admin->updateAdmin($json);
        break;
      case "retrieveStudent":
        echo $admin->retrieveStudent($json);
        break;
      case "getSelectedDeletedStudent":
        echo $admin->getSelectedDeletedStudent($json);
        break;
    }
?>