<?php 
    include "headers.php";

    class User{

      function login($json) {
        include "connection.php";
        $json = json_decode($json, true);
        $username = strtolower($json["user_username"]);   
        $sql = "SELECT * FROM tblusers WHERE LOWER(user_username) = :username AND BINARY user_password = :password";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $json["user_password"]);
        $returnValue = 0;
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
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
        // {"stud_schoolId": "948576", "stud_fullName": "Kobid Rogan", "stud_birthday": "1995-05-15", "stud_birthplace": "City Name", "stud_gender": "Female", "stud_religion": "Christian", "stud_address": "123 Main Street", "stud_email": "kobid123@gmail.com", "stud_contactNumber": "1234567890", "stud_prevSchool": "Previous School Name", "stud_course": "bsit", "stud_gradeLevel": "12th Grade", "stud_yearGraduated": "2020", "stud_fatherName": "John Rogan", "stud_fatherOccupation": "Engineer", "stud_fatherContactNumber": "9876543210", "stud_motherName": "Mary Rogan", "stud_motherOccupation": "Teacher", "stud_motherContactNumber": "9876543211", "stud_emergencyName": "Emergency Contact Name", "stud_emergencyRelationship": "Relative", "stud_emergencyPhone": "9998887777", "stud_emergencyAddress": "Emergency Contact Address", "user_id": 4}
        $json = json_decode($json, true);
        $conn->beginTransaction();
        try {
          $sql = "INSERT INTO tblstudents(stud_schoolId, stud_fullName, stud_birthday, stud_birthplace, stud_gender, stud_religion, stud_address, stud_email, ";
          $sql .= "stud_contactNumber, stud_prevSchool, stud_course, stud_gradeLevel, stud_yearGraduated, stud_fatherName, stud_fatherOccupation, stud_fatherContactNumber, ";
          $sql .= "stud_motherName, stud_motherOccupation, stud_motherContactNumber, stud_emergencyName, stud_emergencyRelationship, stud_emergencyPhone, stud_emergencyAddress, ";
          $sql .= "stud_school_id) ";
          $sql .= "VALUES(:schoolId, :fullName, :birthday, :birthplace, :gender, :religion, :address, :email, ";
          $sql .= ":contactNumber, :prevSchool, :course, :gradeLevel, :yearGraduated, :fatherName, :fatherOccupation, :fatherContactNumber, ";
          $sql .= ":motherName, :motherOccupation, :motherContactNumber, :emergencyName, :emergencyRelationship, :emergencyPhone, :emergencyAddress, :schoolId) ";
          $stmt = $conn->prepare($sql);
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
          $sql2 = "INSERT INTO tbladdstudenthistory(addhist_userId, addhist_studSchoolId) VALUES(:userId, :studentId)";
          $stmt2 = $conn->prepare($sql2);
          $stmt2->bindParam(':userId', $json["user_id"]);
          $stmt2->bindParam(':studentId', $json["stud_schoolId"]);
          $stmt2->execute();
          // echo "Sql2: " . $sql2 . "<br/>";
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
          $sql = "UPDATE tblstudents SET ";
          $sql .= "stud_schoolId = :schoolId, stud_fullName = :fullName, stud_birthday = :birthday, stud_birthplace = :birthplace, ";
          $sql .= "stud_gender = :gender, stud_religion = :religion, stud_address = :address, stud_email = :email, ";
          $sql .= "stud_contactNumber = :contactNumber, stud_prevSchool = :prevSchool, stud_course = :course, ";
          $sql .= "stud_gradeLevel = :gradeLevel, stud_yearGraduated = :yearGraduated, ";
          $sql .= "stud_fatherName = :fatherName, stud_fatherOccupation = :fatherOccupation, ";
          $sql .= "stud_fatherContactNumber = :fatherContactNumber, stud_motherName = :motherName, ";
          $sql .= "stud_motherOccupation = :motherOccupation, stud_motherContactNumber = :motherContactNumber, ";
          $sql .= "stud_emergencyName = :emergencyName, stud_emergencyRelationship = :emergencyRelationship, ";
          $sql .= "stud_emergencyPhone = :emergencyPhone, stud_emergencyAddress = :emergencyAddress ";
          $sql .= "WHERE stud_id = :studId";
          
          $stmt = $conn->prepare($sql);
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
          $stmt->bindParam(":schoolId", $json["stud_schoolId"]);
          $stmt->bindParam(':studId', $json["stud_id"]);
          $stmt->execute();
          // echo "Sql1: " . $sql . "<br/>";
          if($stmt->rowCount() <= 0) {
            $conn->rollBack();
            return 0;
          }

          $sql2 = "INSERT INTO tblupdatestudenthistory(uphist_userId, uphist_studId) VALUES(:userId, :studId)";
          $stmt2 = $conn->prepare($sql2);
          $stmt2->bindParam(':userId', $json["user_id"]);
          $stmt2->bindParam(':studId', $json["stud_id"]);
          $stmt2->execute();
          // echo "Sql2: " . $sql2 . "<br/>";
          $conn->commit();
          return 1;
        } catch (PDOException $e) {
          $conn->rollBack();
          return 0;
        }

      }

      function deleteStudent($json){
        //{"stud_id":41,"user_id":4,"stud_fullName":"Joe","stud_schoolId":"9123821","stud_birthday":"02/23/2001","stud_birthplace":"CDO","stud_gender":"FEMALE","stud_religion":"inc","stud_address":"cdo","stud_email":"joe@gmail.com","stud_contactNumber":"0912312312","stud_prevSchool":"mcs","stud_course":"0","stud_gradeLevel":"11","stud_yearGraduated":"2019\\","stud_fatherName":"joe","stud_fatherOccupation":"drive","stud_fatherContactNumber":"012312321","stud_motherName":"mama","stud_motherOccupation":"driver","stud_motherContactNumber":"021321321","stud_emergencyName":"kobid","stud_emergencyRelationship":"dog","stud_emergencyPhone":"01232131","stud_emergencyAddress":"cdo"}
        include "connection.php";
        $json = json_decode($json, true);
        $conn->beginTransaction();
        try {
          $sql = "INSERT INTO tbldeletedstudent(delstud_schoolId, delstud_fullName, delstud_birthday, delstud_birthplace, delstud_gender, delstud_religion, delstud_address, delstud_email, ";
          $sql .= "delstud_contactNumber, delstud_prevSchool, delstud_course, delstud_gradeLevel, delstud_yearGraduated, delstud_fatherName, delstud_fatherOccupation, delstud_fatherContactNumber, ";
          $sql .= "delstud_motherName, delstud_motherOccupation, delstud_motherContactNumber, delstud_emergencyName, delstud_emergencyRelationship, delstud_emergencyPhone, delstud_emergencyAddress, ";
          $sql .= "delstud_school_id, delstud_studId) ";
          $sql .= "VALUES(:schoolId, :fullName, :birthday, :birthplace, :gender, :religion, :address, :email, ";
          $sql .= ":contactNumber, :prevSchool, :course, :gradeLevel, :yearGraduated, :fatherName, :fatherOccupation, :fatherContactNumber, ";
          $sql .= ":motherName, :motherOccupation, :motherContactNumber, :emergencyName, :emergencyRelationship, :emergencyPhone, :emergencyAddress, ";
          $sql .= ":school_id, :studId)";
          $stmt = $conn->prepare($sql);
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
          $stmt->bindParam(":school_id", $json["stud_school_id"]);
          $stmt->bindParam(":studId", $json["stud_id"]);
          $stmt->execute();
          // echo "Sql: " . $sql . "<br/>";
          if($stmt->rowCount() <= 0){
            $conn->rollback();
            return 0;
          }
          $sql1 = "DELETE FROM tblstudents WHERE stud_id = :studId";
          $stmt1 = $conn->prepare($sql1);
          $stmt1->bindParam(":studId", $json["stud_id"]);
          $stmt1->execute();
          // echo "Sql1: " . $sql1 . "<br/>";
          if($stmt1->rowCount() <= 0){
            $conn->rollBack();
            return 0;
          }
          $sql2 = "INSERT INTO tbldeletehistory(delhist_userId, delhist_fullName) VALUES(:userId, :fullName)";
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
          // "error siya " . $e->getMessage();
        }
      }

      function searchStudent($json){
        include "connection.php";
        $json = json_decode($json, true);
        $sql = "SELECT * FROM tblstudents WHERE stud_schoolId = :schoolId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':schoolId',$json["stud_schoolId"]);
        $stmt->execute();
        $returnValue = 0;
        if($stmt->rowCount() > 0){
          $rs = $stmt->fetch(PDO::FETCH_ASSOC);
          $returnValue = json_encode($rs);
        }
        return $returnValue;
      }

      function getAllStudentByStrand($json){
        include "connection.php";
        $json = json_decode($json, true);
        $sql = "SELECT * FROM tblstudents WHERE stud_course = :studCourse";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':studCourse',$json["stud_course"]);
        $stmt->execute();
        $returnValue = 0;
        if($stmt->rowCount() > 0){
          $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
          $returnValue = json_encode($rs);
        }
        return $returnValue;
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
      case "searchStudent":
        echo $user->searchStudent($json);
        break;
      case "getAllStudentByStrand":
        echo $user->getAllStudentByStrand($json);
        break;
    }
?>