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
        $json = json_decode($json, true);
        // {"schoolId":"1111-2222-3333","fullName":"Kobid Macario","gender":"Female", "email":"kobid@gmail.com", "courseCode":"bsit", "yearLevel":1, "dateEnrolled": "03/03/2023", "address":"CDO"}
        $sql = "INSERT INTO tblstudents(stud_schoolId, stud_fullName, stud_gender, stud_email, stud_courseCode, stud_yearLevel, stud_dateEnrolled, stud_address) ";
        $sql .= " VALUES(:schoolId, :fullName, :gender, :email, :courseCode, :yearLevel, :dateEnrolled, :address) ";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":schoolId", $json["stud_schoolId"]);
        $stmt->bindParam(":fullName", $json["stud_fullName"]);
        $stmt->bindParam(":gender", $json["stud_gender"]);
        $stmt->bindParam(":email", $json["stud_email"]);
        $stmt->bindParam(":courseCode", $json["stud_courseCode"]);
        $stmt->bindParam(":yearLevel", $json["stud_yearLevel"]);
        $stmt->bindParam(":dateEnrolled", $json["stud_dateEnrolled"]);
        $stmt->bindParam(":address", $json["stud_address"]);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? 1 : 0;
      }

      function updateStudent($json){
        include "connection.php";
        $json = json_decode($json, true);
        // {"schoolId":"1111-2222-3333","fullName":"Kobid Macario","gender":"Female", "email":"kobid@gmail.com", "courseCode":"bsit", "yearLevel":1, "dateEnrolled": "03/03/2023", "address":"CDO"}
        $sql = "UPDATE tblstudents SET stud_schoolId = :schoolId, stud_fullName = :fullName, stud_gender = :gender, stud_email = :email, stud_courseCode = :courseCode, stud_yearLevel = :yearLevel, stud_dateEnrolled = :dateEnrolled, stud_address = :address ";
        $sql.= " WHERE stud_id = :studId ";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':studId', $json["stud_id"]);
        $stmt->bindParam(':schoolId', $json["stud_schoolId"]);
        $stmt->bindParam(':fullName', $json["stud_fullName"]);
        $stmt->bindParam(':gender', $json["stud_gender"]);
        $stmt->bindParam(':email', $json["stud_email"]);
        $stmt->bindParam(':courseCode', $json["stud_courseCode"]);
        $stmt->bindParam(':yearLevel', $json["stud_yearLevel"]);
        $stmt->bindParam(':dateEnrolled', $json["stud_dateEnrolled"]);
        $stmt->bindParam(':address', $json["stud_address"]);
        $stmt->execute();
        return $stmt->rowCount() > 0? 1 : 0;
      }

      function deleteStudent($json){
        //{"studId":1}
        include "connection.php";
         $json = json_decode($json, true);
         $sql = "DELETE FROM tblstudents WHERE stud_id = :studId";
         $stmt = $conn->prepare($sql);
         $stmt->bindParam(":studId", $json["studId"]);
         $stmt->execute();
         return $stmt->rowCount() > 0? 1 : 0;
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
      case "deleteStudent":
        echo $user->deleteStudent($json);
        break;
      case "getSelectedStudent":
        echo $user->getSelectedStudent($json);
        break;
    }
?>