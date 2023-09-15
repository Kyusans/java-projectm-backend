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

      function addStudent($json){
        include "connection.php";
        $json = json_decode($json, true);
        // {"schoolId":"1111-2222-3333","fullName":"Kobid Macario","gender":"Female", "email":"kobid@gmail.com", "courseCode":"bsit", "yearLevel":1, "dateEnrolled": "03/03/2023", "address":"CDO"}
        $sql = "INSERT INTO tblstudents(stud_schoolId, stud_fullName, stud_gender, stud_email, stud_courseCode, stud_yearLevel, stud_dateEnrolled, stud_address) ";
        $sql .= " VALUES(:schoolId, :fullName, :gender, :email, :courseCode, :yearLevel, :dateEnrolled, :address) ";
        $stmt = $conn->prepare($sql);
        // $stmt->bindParam(":schoolId", $json["schoolId"]);
        // $stmt->bindParam(":fullName", $json["fullName"]);
        // $stmt->bindParam(":gender", $json["gender"]);
        // $stmt->bindParam(":email", $json["email"]);
        // $stmt->bindParam(":courseCode", $json["courseCode"]);
        // $stmt->bindParam(":yearLevel", $json["yearLevel"]);
        // $stmt->bindParam(":dateEnrolled", $json["dateEnrolled"]);
        // $stmt->bindParam(":address", $json["address"]);
        
        // for java
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
        // {"fullName":"Kobid Mac", "schoolId":"1441-2552-3663", "birthday":"01/02/2003", "course":"bwesit", "lrn":"122346789012", "studId": 1}
        $sql = "UPDATE tblstudents ";
        $sql .= "SET stud_fullName=:fullName, stud_schoolId=:schoolId, stud_birthday=:birthday, stud_course=:course, stud_lrn=:lrn ";
        $sql .= "WHERE stud_id = :studId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":schoolId", $json["stud_schoolId"]);
        $stmt->bindParam(":lname", $json["stud_lname"]);
        $stmt->bindParam(":fname", $json["stud_fname"]);
        $stmt->bindParam(":mname", $json["stud_mname"]);
        $stmt->bindParam(":email", $json["stud_email"]);
        $stmt->bindParam(":courseCode", $json["stud_courseCode"]);
        $stmt->bindParam(":yearLevel", $json["stud_yearLevel"]);
        $stmt->bindParam(":dateEnrolled", $json["stud_dateEnrolled"]);
        $stmt->bindParam(":address", $json["stud_address"]);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? 1 : 0;
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
    }
?>