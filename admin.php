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
        //{"userName":"joey", "password":"joey", "fullName":"Joey Joey", "staffId":"1221-2332-3443", "birthday":"01/02/2003"}
        $sql = "INSERT INTO tblusers(user_username, user_password , user_fullName, user_staffId, user_birthday) ";
        $sql .= " VALUES( :userName, :password, :fullName, :staffId, :birthday)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":userName", $json["userName"]);
        $stmt->bindParam(":password", $json["password"]);
        $stmt->bindParam(":fullName", $json["fullName"]);
        $stmt->bindParam(":staffId", $json["staffId"]);
        $stmt->bindParam(":birthday", $json["birthday"]);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? 1 : 0;
      }

      function updateStaff($json){
        include "connection.php";
        $json = json_decode($json, true);
        // {"userName":"joeeeee", "password":"joe123", "fullName":"Joey Joey", "staffId":"0000-2672-9443", "birthday":"01/02/2003", "userId":3}
        $sql = "UPDATE tblusers ";
        $sql .= "SET user_username=:userName, user_password=:password, user_fullName=:fullName, user_staffId=:staffId, user_birthday=:birthday ";
        $sql .= "WHERE user_id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":userName", $json["userName"]);
        $stmt->bindParam(":password", $json["password"]);
        $stmt->bindParam(":fullName", $json["fullName"]);
        $stmt->bindParam(":staffId", $json["staffId"]);
        $stmt->bindParam(":birthday", $json["birthday"]);
        $stmt->bindParam(":userId", $json["userId"]);
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