<?php 
    include "headers.php";

    class User{

      function login($json){
        include "connection.php";
        //{"username":"kobi", "password":"kobi123"}
        $json = json_decode($json, true);
        $sql = "SELECT * FROM tblusers WHERE user_username = :username AND user_password = :password";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":username", $json["user_username"]);
        $stmt->bindParam(":password", $json["user_password"]);
        $returnValue = 0;

        if($stmt->execute()){
          if($stmt->rowCount() > 0){
            $rs = $stmt->fetch(PDO::FETCH_ASSOC);
            $returnValue = json_encode($rs);
          }
        }

        return $returnValue;
      }

      function getAllStudent(){
        include "connection.php";
        $sql = "SELECT * FROM tblstudents ORDER BY stud_fullName";
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

      function getSelectedStudent($json){
        include "connection.php";
        $json = json_decode($json, true);
        $sql = "SELECT * FROM tblstudents WHERE stud_id = :studId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":studId", $json["stud_Id"]);
        $returnValue = 0;
        if($stmt->execute()){
          if($stmt->rowCount() > 0){
            $rs = $stmt->fetch(PDO::FETCH_ASSOC);
            $returnValue = json_encode($rs);
          }
        }

        return $returnValue;
      }

      function addStudent($json){
        include "connection.php";
        // {"schoolId":"948576","fullName":"Kobid Rogan","gender":"Female", "email":"kobid123@gmail.com", "courseCode":"bsit", "yearLevel":1, "address":"CDO", "userId":1}
        $json = json_decode($json, true);
        $conn->beginTransaction();
        try {
          $sql = "INSERT INTO tblstudents(stud_schoolId, stud_fullName, stud_gender, stud_email, stud_courseCode, stud_yearLevel, stud_address) ";
          $sql .= " VALUES(:schoolId, :fullName, :gender, :email, :courseCode, :yearLevel, :address) ";
          $stmt = $conn->prepare($sql);
          $stmt->bindParam(":schoolId", $json["stud_schoolId"]);
          $stmt->bindParam(":fullName", $json["stud_fullName"]);
          $stmt->bindParam(":gender", $json["stud_gender"]);
          $stmt->bindParam(":email", $json["stud_email"]);
          $stmt->bindParam(":courseCode", $json["stud_courseCode"]);
          $stmt->bindParam(":yearLevel", $json["stud_yearLevel"]);
          $stmt->bindParam(":address", $json["stud_address"]);
          $stmt->execute();
          if($stmt->rowCount() <= 0) {
            $conn->rollBack();
            return 0;
          }
          $sql2 = "INSERT INTO tbladdstudenthistory(addhist_userId, addhist_studSchoolId) VALUES(:userId, :studentId)";
          $stmt2 = $conn->prepare($sql2);
          $stmt2->bindParam(':userId', $json["user_id"]);
          $stmt2->bindParam(':studentId', $json["stud_schoolId"]);
          $stmt2->execute();
          $conn->commit();
          return 1;
        } catch (PDOException $e) {
          $conn->rollBack();
          return 0;
        }
      }

      function updateStudent($json){
        // {"schoolId":"1111-2222-3333","fullName":"Kobid Macario","gender":"Female", "email":"kobid@gmail.com", "courseCode":"bsit", "yearLevel":1, "address":"CDO", "userId":1}   
        include "connection.php";    
        $json = json_decode($json, true);
        $conn->beginTransaction();
        try {
          $sql = "UPDATE tblstudents SET stud_schoolId = :schoolId, stud_fullName = :fullName, stud_gender = :gender, stud_email = :email, ";
          $sql .= "stud_courseCode = :courseCode, stud_yearLevel = :yearLevel, stud_address = :address ";
          $sql .= "WHERE stud_id = :studId ";
          $stmt = $conn->prepare($sql);
          $stmt->bindParam(':studId', $json["stud_id"]);
          $stmt->bindParam(':schoolId', $json["stud_schoolId"]);
          $stmt->bindParam(':fullName', $json["stud_fullName"]);
          $stmt->bindParam(':gender', $json["stud_gender"]);
          $stmt->bindParam(':email', $json["stud_email"]);
          $stmt->bindParam(':courseCode', $json["stud_courseCode"]);
          $stmt->bindParam(':yearLevel', $json["stud_yearLevel"]);
          $stmt->bindParam(':address', $json["stud_address"]);
          $stmt->execute();
          if($stmt->rowCount() <= 0) {
            $conn->rollBack();
            return 0;
          }
          $sql2 = "INSERT INTO tblupdatestudenthistory(uphist_userId, uphist_studId) VALUES(:userId, :studentId)";
          $stmt2 = $conn->prepare($sql2);
          $stmt2->bindParam(':userId', $json["user_id"]);
          $stmt2->bindParam(':studentId', $json["stud_id"]);
          $stmt2->execute();
          $conn->commit();
          return 1;
        } catch (PDOException $e) {
          $conn->rollBack();
          return 0;
        }
      }

      function deleteStudent($json){
        //{"studId":3, "studFullName":"kobi","userId":1}
        include "connection.php";
        $json = json_decode($json, true);
        $conn->beginTransaction();
        try {
          $sql = "DELETE FROM tblstudents WHERE stud_id = :studId";
          $stmt = $conn->prepare($sql);
          $stmt->bindParam(":studId", $json["stud_id"]);
          $stmt->execute();
          // echo "Sql1: " . $sql . "<br/>";
          if($stmt->rowCount() <= 0){
            $conn->rollBack();
            return "sql1 diri";
          }
          $sql2 = "INSERT INTO tbldeletehistory(delhist_userId, delhist_studFullName) VALUES(:userId, :fullName)";
          $stmt2 = $conn->prepare($sql2);
          $stmt2->bindParam(":userId", $json["user_id"]);
          $stmt2->bindParam(":fullName", $json["stud_fullName"]);
          $stmt2->execute();
          // echo "Sql2: " . $sql2 . "<br/>";
          $conn->commit();
          return 1;
        } catch (PDOException $e) {
          $conn->rollBack();
          return 0;
        }
      }
    }
    $json = isset($_POST["json"]) ? $_POST["json"] : "0";
    $operation = isset($_POST["operation"]) ? $_POST["operation"] : "0";
    $user = new User();

    switch($operation){
      case "login":
        echo $user->login($json);
        break;
      case "getAllStudent":
        echo $user->getAllStudent();
        break;
      case "addStudent":
        echo $user->addStudent($json);
        break;
      case "updateStudent":
        echo $user->updateStudent($json);
        break;
      case "getSelectedStudent":
        echo $user->getSelectedStudent($json);
        break;
      case "deleteStudent":
        echo $user->deleteStudent($json);
        break;
    }
?>