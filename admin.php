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
        $sql = "SELECT a.delhist_dateDeleted, a.delhist_fullName, b.user_fullName ";
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
    }
?>