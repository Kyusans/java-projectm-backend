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
    }
?>