<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Form</title>
    <style media="screen">
      label, p
      {
        font-size: 25px;
      }
      .req
      {
          color: red;
      }
    </style>
  </head>
  <body>
    <h1>Форма за добавяне на избираеми дисциплини</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
      <label for="subjectName">Име на предмета: <span class="req">*</span></label>
      <br>
      <input type="text" id="subjectName" name="subjectName" maxlength="150" size="80" required>
      <br>
      <label for="tutor">Преподавател: <span class="req">*</span></label>
      <br>
      <input type="text" id="tutor" name="tutor" maxlength="200" size="80" required>
      <br>
      <label for="description">Описание: <span class="req">*</span></label>
      <br>
      <!--<input type="text" id="description" value="" minlength="10" size="150">-->
      <textarea id="description" name="description" rows="8" cols="80" minlength="10" required></textarea>
      <br>
      <label for="subjectType">Група: </label>
      <select id="subjectType" name="subjectType">
        <option value="М">М</option>
        <option value="ПМ">ПМ</option>
        <option value="ОКН">ОКН</option>
        <option value="ЯКН">ЯКН</option>
      </select>
      <br>
      <label for="credits">Кредити: </label>
      <input type="number" id="credits" name="credits" min="1" step="1">
      <br>
      <input type="submit" id="submitForm">
    </form>
    <p aria-hidden="true">
      <span class="req">*</span>Задължително
    </p>
    <?php
      echo "<p style=\"color: red\">";
      if($_SERVER["REQUEST_METHOD"] == "POST")
      {
        if($_POST["subjectName"] || $_POST["tutor"] || $_POST["description"] || $_POST["subjectType"] || $_POST["credits"])
        {
          //Поради "защитата" в HTML-а, повечето проверки се случват само ако някой е променял HTML-a
          $properForm=true;

          //Тъпо направено, но не успях да подкарам с други RegEx-ове
          $notBgName="/[^ЯяВвЕеРрТтЪъУуИиОоПпАаСсДдФфГгХхЙйКкЛлЗзѝьЦцЖжБбНнМмЧчШшЩщЮю\s]/";
          $notBgText="/[^ЯяВвЕеРрТтЪъУуИиОоПпАаСсДдФфГгХхЙйКкЛлЗзѝьЦцЖжБбНнМмЧчШшЩщЮю0-9,;!?\s\t\.]/";


          //Проверки за предмет
          if(empty($_POST["subjectName"]))
          {
            echo "Липсва име на предмет!<br>";
            $properForm=false;
          }
          else
          {
            if(strlen($_POST["subjectName"])>150)
            {
              echo "Прекалено дълго име на предмета!<br>";
              $properForm=false;
            }

            if (preg_match($notBgName, $_POST["subjectName"])) {
              echo "Името на предмета не е на кирилица!<br>";
              $properForm=false;
            }
          }


          //Проверки за преподавател
          if(empty($_POST["tutor"]))
          {
            echo "Липсва име на преподавател!<br>";
            $properForm=false;
          }
          else
          {
            if(strlen($_POST["tutor"])>200)
            {
              echo "Прекалено дълго име на преподавателя!<br>";
              $properForm=false;
            }

            if (preg_match($notBgName, $_POST["tutor"])) {
              echo "Преподавателското име не е на кирилица!<br>";
              $properForm=false;
            }
          }


          //Проверки за описание
          if(empty($_POST["description"]))
          {
            echo "Липсва описание!<br>";
            $properForm=false;
          }
          else
          {
            if(strlen($_POST["description"])<10)
            {
              echo "Прекалено късо описание!<br>";
              $properForm=false;
            }

            if (preg_match($notBgText, $_POST["description"]))
            {
              echo "Описанието не е на кирилица!<br>";
              $properForm=false;
            }
          }


          //Проверка за група на дисциплина
          $subjectType=$_POST["subjectType"];
          if(strcmp($subjectType,"М")!=0&&strcmp($subjectType,"ПМ")!=0&&strcmp($subjectType,"ОКН")!=0&&strcmp($subjectType,"ЯКН")!=0)
          {
            echo "Избрана несъществуваща дисциплина!<br>";
            $properForm=false;
          }


          //Проверка за кредити
          $credits=intval($_POST["credits"]);
          if(!is_int($credits))
          {
            echo "Кредитите не са цяло число!<br>";
            $properForm=false;
          }

          if($credits<=0)
          {
            echo "Кредитите не са положително число!<br>";
            $properForm=false;
          }

          echo "</p>";

          //Ако всичко е точно се опитваме да вкараме в базата
          if($properForm)
          {
            //Общи параметри
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "HW2_81609";
            $noproblem = true;
            //Създаване на база данни, ако я няма
            try
            {
              $conn = new PDO("mysql:host=$servername", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $sql = "CREATE DATABASE ". $dbname;
              $conn->exec($sql);
            }
            catch(PDOException $e)
            {
              //Ако вече съществува базата данни дава следният exception:
              //SQLSTATE[HY000]: General error: 1007 Can't create database $dbname; database exists
              //Просто търсим дали кодът 1007 се среща в съобщението за грешка и ако да, не ни интересува
              if(preg_match('/1007/',$e->getMessage())==0)
              {
                echo $sql . "<br>" . $e->getMessage() . "<br>";
                $noproblem=false;
              }
            }

            //Създаване на релационна схема, ако я няма
            //Слагаме някакъв максимум на текста, който може да се даде като описание, за да може да го сложим в text тип
            try
            {
              $connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password,
                  array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

              $sql = "CREATE TABLE subjects(
                  id INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                  name VARCHAR(150) NOT NULL,
                  tutor VARCHAR(200) NOT NULL,
                  description TEXT(3000) NOT NULL,
                  subjectType VARCHAR(3) CHECK (subjectType in ('М','ПМ','ОКН','ЯКН')),
                  credits INT(8) UNSIGNED CHECK (credits>0)
              )";
              $connection->exec($sql);
            }
            catch(PDOException $e)
            {
              //Поради някаква причина не дава exception, ако вече съществува таблицата?
              echo $sql . "<br>" . $e->getMessage() . "<br>";
              $noproblem=false;
            }

            // Вкарваме информацията
            try
            {
              $connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password,
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
              $sql = "INSERT INTO subjects(name, tutor, description, subjectType, credits) VALUES(?, ?, ?, ?, ?)";
              $statement = $connection->prepare($sql);
              $statement->execute([$_POST['subjectName'], $_POST['tutor'], $_POST['description'], $_POST['subjectType'], $_POST['credits']]);
            }
            catch(PDOException $e)
            {
              echo $sql . "<br>" . $e->getMessage() . "<br>";
              $noproblem=false;
            }

            //Проверяваме дали е настъпил проблем с базата данни
            if($noproblem) echo "<p>Избираемата дисциплина е добавена успешно!</p>";
            else echo "<p style=\"color: red\">Проблем с вкарването на данни в базата данни!</p>";
          }
        }
      }
     ?>
  </body>
</html>
