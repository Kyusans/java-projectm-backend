<?php 
    include "headers.php";

    class Admin{

      function getAllStaff(){
        include "connection.php";
        $sql = "SELECT * FROM tblusers WHERE user_level = 90 ORDER BY user_fullName";
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

      function getSelectedStaff($json){
        include "connection.php";
        $json = json_decode($json, true);
        $sql = "SELECT * FROM tblusers WHERE user_id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId',$json['user_id']);
        $stmt->execute();
        $returnValue = 0;
        if($stmt->rowCount() > 0){
          $rs = $stmt->fetch(PDO::FETCH_ASSOC);
          $returnValue = json_encode($rs);
        }
        return $returnValue;
      }

      function addStaff($json){
        include "connection.php";
        $json = json_decode($json, true);
        //{"userName":"joey", "password":"joey", "fullName":"Joey Joey", "email":"jioe@gmail.com"}
        $sql = "INSERT INTO tblusers(user_username, user_password , user_fullName, user_email, user_level, user_contactNumber, user_address) ";
        $sql .= " VALUES( :userName, :password, :fullName, :email, 90, :contactNumber, :address)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":userName", $json["user_username"]);
        $stmt->bindParam(":password", $json["user_password"]);
        $stmt->bindParam(":fullName", $json["user_fullName"]);
        $stmt->bindParam(":contactNumber", $json["user_contactNumber"]);
        $stmt->bindParam(":address", $json["user_address"]);
        $stmt->bindParam(":email", $json["user_email"]);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? 1 : 0;
      }

      function updateStaff($json){
        include "connection.php";
        // {"userName":"joeeeee", "password":"joe123", "fullName":"Joey Joey", "email":"jioe@gmail.com", "userId" : 3}
        $json = json_decode($json, true);
        $sql = "UPDATE tblusers ";
        $sql .= "SET user_username=:userName, user_password=:password, user_fullName=:fullName, user_email=:email, user_address=:address, user_contactNumber=:contactNumber ";
        $sql .= "WHERE user_id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":userName", $json["user_username"]);
        $stmt->bindParam(":password", $json["user_password"]);
        $stmt->bindParam(":fullName", $json["user_fullName"]);
        $stmt->bindParam(":email", $json["user_email"]);
        $stmt->bindParam(":address", $json["user_address"]);
        $stmt->bindParam(":contactNumber", $json["user_contactNumber"]);
        $stmt->bindParam(":userId", $json["user_id"]);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? 1 : 0;
      }

      function deleteStaff($json){
        //{"userId":3}
        include "connection.php";
        $json = json_decode($json, true);
        $sql = "DELETE FROM tblusers WHERE user_id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":userId", $json["user_id"]);
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
        $sql .= "WHERE a.uphist_dateUpdated >= :oneMonthAgo ";
        $sql .= "ORDER BY a.uphist_dateUpdated ASC"; 
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
        $sql .= "INNER JOIN tblstudents as c ON a.addhist_studId = c.stud_id ";
        $sql .= "WHERE a.addhist_dateAdded >= :oneMonthAgo ";
        $sql .= "ORDER BY a.addhist_id ASC"; 
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
        $sql = "SELECT a.delhist_id, a.delhist_dateDeleted, b.user_fullName, c.delstud_fullName ";
        $sql .= "FROM tbldeletehistory as a ";
        $sql .= "INNER JOIN tblusers as b ON a.delhist_userId = b.user_id ";
        $sql .= "INNER JOIN tbldeletedstudent as c ON a.delhist_delStudId = c.delstud_id ";
        $sql .= "WHERE a.delhist_dateDeleted >= :oneMonthAgo";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':oneMonthAgo', $oneMonthAgo);
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
        $sql = "SELECT b.* 
        FROM tbldeletehistory as a 
        INNER JOIN tbldeletedstudent as b ON a.delhist_delStudId = b.delstud_id 
        WHERE a.delhist_id = :delhistId";
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
        // {"delstud_id":4,"delstud_fullName":"mel","delstud_schoolId":"12312321","delstud_birthday":"12\/12\/12","delstud_birthplace":"cdo","delstud_gender":"male","delstud_religion":"inc","delstud_address":"cdo","delstud_email":"mel@gmail.con","delstud_contactNumber":"0912312312","delstud_prevSchool":"mcs","delstud_course":"0","delstud_gradeLevel":"1","delstud_yearGraduated":"2019","delstud_fatherName":"adormie","delstud_fatherOccupation":"teacher","delstud_fatherContactNumber":"anita","delstud_motherName":"housewife","delstud_motherOccupation":"123","delstud_motherContactNumber":"123213","delstud_emergencyName":"kobid","delstud_emergencyRelationship":"dog","delstud_emergencyPhone":"0129312","delstud_emergencyAddress":"cdo","delstud_school_Id":"12312321","delstud_studId":40}
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
          $stmt->bindParam(":schoolId", $json["delstud_schoolId"]);
          $stmt->bindParam(":fullName", $json["delstud_fullName"]);
          $stmt->bindParam(":birthday", $json["delstud_birthday"]);
          $stmt->bindParam(":birthplace", $json["delstud_birthplace"]);
          $stmt->bindParam(":gender", $json["delstud_gender"]);
          $stmt->bindParam(":religion", $json["delstud_religion"]);
          $stmt->bindParam(":address", $json["delstud_address"]);
          $stmt->bindParam(":email", $json["delstud_email"]);
          $stmt->bindParam(":contactNumber", $json["delstud_contactNumber"]);
          $stmt->bindParam(":prevSchool", $json["delstud_prevSchool"]);
          $stmt->bindParam(":course", $json["delstud_course"]);
          $stmt->bindParam(":gradeLevel", $json["delstud_gradeLevel"]);
          $stmt->bindParam(":yearGraduated", $json["delstud_yearGraduated"]);
          $stmt->bindParam(":fatherName", $json["delstud_fatherName"]);
          $stmt->bindParam(":fatherOccupation", $json["delstud_fatherOccupation"]);
          $stmt->bindParam(":fatherContactNumber", $json["delstud_fatherContactNumber"]);
          $stmt->bindParam(":motherName", $json["delstud_motherName"]);
          $stmt->bindParam(":motherOccupation", $json["delstud_motherOccupation"]);
          $stmt->bindParam(":motherContactNumber", $json["delstud_motherContactNumber"]);
          $stmt->bindParam(":emergencyName", $json["delstud_emergencyName"]);
          $stmt->bindParam(":emergencyRelationship", $json["delstud_emergencyRelationship"]);
          $stmt->bindParam(":emergencyPhone", $json["delstud_emergencyPhone"]);
          $stmt->bindParam(":emergencyAddress", $json["delstud_emergencyAddress"]);
          $stmt->bindParam(":studId", $json["delstud_studId"]);
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
          // echo "Sql1: " . $sql1 . "<br/>";
        } catch (PDOException $e) {
          $conn->rollBack();
          return 0;
        }

        $sql2 = "DELETE FROM tbldeletehistory WHERE delhist_delStudId = :studId";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bindParam(":studId", $json["delstud_id"]);
        $stmt2->execute();
        // echo "Sql2: " . $sql2 . "<br/>";
        $conn->commit();
        return 1;
      }

      function getFaculty(){
        include "connection.php";
        $sql = "SELECT * FROM tblusers WHERE user_level = 80 ORDER BY user_fullName";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $returnValue = 0;
        if($stmt->rowCount() > 0){
          $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
          $returnValue = json_encode($rs);
        }
        return $returnValue;
      }

      function addFaculty($json){
        include "connection.php";
        $json = json_decode($json, true);
        $sql = "INSERT INTO tblusers(user_username, user_password, user_fullName, user_email, user_contactNumber, user_address, user_level) ";
        $sql .= "VALUES(:username, :password, :fullName, :email, :contactNumber, :address, 80)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":username", $json["user_username"]);
        $stmt->bindParam(":password", $json["user_password"]);
        $stmt->bindParam(":fullName", $json["user_fullName"]);
        $stmt->bindParam(":email", $json["user_email"]);
        $stmt->bindParam(":contactNumber", $json["user_contactNumber"]);
        $stmt->bindParam(":address", $json["user_address"]);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? 1 : 0;
      }
  }    

    $json = isset($_POST["json"]) ? $_POST["json"] : "0";
    $operation = isset($_POST["operation"]) ? $_POST["operation"] : "0";
    $admin = new Admin();

    switch($operation){
      case "getAllStaff":
        echo $admin->getAllStaff();
        break;
      case "getSelectedStaff":
        echo $admin->getSelectedStaff($json);
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
      case "getFaculty":
        echo $admin->getFaculty($json);
        break;
      case "addFaculty":
        echo $admin->addFaculty($json);
        break;
    }
?>