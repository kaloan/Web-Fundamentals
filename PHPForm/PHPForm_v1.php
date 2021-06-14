<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Form</title>
    <style media="screen">
      label
      {
        font-size: 25px;
      }
    </style>
  </head>
  <body>
    <h1>Форма за добавяне на избираеми дисциплини</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
      <label for="subjectName">Име на предмета:</label>
      <br>
      <input type="text" id="subjectName" name="subjectName" maxlength="150" size="80" required>
      <br>
      <label for="tutor">Преподавател: </label>
      <br>
      <input type="text" id="tutor" name="tutor" maxlength="200" size="80" required>
      <br>
      <label for="description">Описание: </label>
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

    <p style="color: red; font-size: 25px">
    <?php
      if($_SERVER["REQUEST_METHOD"] == "POST"){
        if($_POST["subjectName"] || $_POST["tutor"] || $_POST["description"] || $_POST["subjectType"] || $_POST["credits"])
        {
          //Поради "защитата" в HTML-а, повечето проверки се случват само ако някой е променял HTML-a
          $properForm=true;

          //Тъпо направено, но не успях да подкарам с други RegEx-ове
          $notBgName="/[^ЯяВвЕеРрТтЪъУуИиОоПпАаСсДдФфГгХхЙйКкЛлЗзѝьЦцЖжБбНнМмЧчШшЩщЮю\s]/";
          $notBgText="/[^ЯяВвЕеРрТтЪъУуИиОоПпАаСсДдФфГгХхЙйКкЛлЗзѝьЦцЖжБбНнМмЧчШшЩщЮю0-9,;\s\t\.]/";


          //Проверки за предмет
          if(empty($_POST['subjectName']))
          {
            echo "Липсва име на предмет!";
            echo "<br>";
            $properForm=false;
          }
          else
          {
            if(strlen($_POST['subjectName'])>150)
            {
              echo "Прекалено дълго име на предмета!";
              echo "<br>";
              $properForm=false;
            }

            if (preg_match($notBgName, $_POST['subjectName'])) {
              echo "Името на предмета не е на кирилица!";
              echo "<br>";
              $properForm=false;
            }
          }


          //Проверки за преподавател
          if(empty($_POST['tutor']))
          {
            echo "Липсва име на преподавател!";
            echo "<br>";
            $properForm=false;
          }
          else
          {
            if(strlen($_POST['tutor'])>200)
            {
              echo "Прекалено дълго име на преподавателя!";
              echo "<br>";
              $properForm=false;
            }

            if (preg_match($notBgName, $_POST['tutor'])) {
              echo "Преподавателското име не е на кирилица!";
              echo "<br>";
              $properForm=false;
            }
          }


          //Проверки за описание
          if(empty($_POST['description']))
          {
            echo "Липсва описание!";
            echo "<br>";
            $properForm=false;
          }
          else
          {
            if(strlen($_POST['description'])<10)
            {
              echo "Прекалено късо описание!";
              echo "<br>";
              $properForm=false;
            }

            if (preg_match($notBgText, $_POST['description']))
            {
              echo "Описанието не е на кирилица!";
              echo "<br>";
              $properForm=false;
            }
          }


          //Проверка за група на дисциплина
          $subjectType=$_POST["subjectType"];
          if(strcmp($subjectType,"М")!=0&&strcmp($subjectType,"ПМ")!=0&&strcmp($subjectType,"ОКН")!=0&&strcmp($subjectType,"ЯКН")!=0)
          {
            echo "Избрана несъществуваща дисциплина!";
            echo "<br>";
            $properForm=false;
          }


          //Проверка за кредити
          $credits=intval($_POST["credits"]);
          if(!is_int($credits))
          {
            echo "Кредитите не са цяло число!";
            echo "<br>";
            $properForm=false;
          }

          if($credits<=0)
          {
            echo "Кредитите не са положително число!";
            echo "<br>";
            $properForm=false;
          }


          //Ако всичко е валидирано
          if($properForm)
          {
            "Избираемата дисциплина е добавена успешно!";
          }
        }
      }
     ?>
   </p>
  </body>
</html>