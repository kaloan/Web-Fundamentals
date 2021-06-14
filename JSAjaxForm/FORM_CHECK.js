//Връзка към сървъра
const url='https://jsonplaceholder.typicode.com/users';
xhr = new XMLHttpRequest();
xhr.onload = function() {
    if (xhr.status === 200) {
        jsonData = JSON.parse(xhr.responseText);
    }
    else {
        console.error(xhr.responseText);
    }
}
xhr.open('GET', url, true);
xhr.send(null);


function invalid(strArg)
{
  document.getElementsByClassName('error')[0].innerHTML = "Невалидно " + strArg;
}


function checkForm()
{
  //Изчисти полетата
  document.getElementsByClassName('error')[0].innerHTML = "";
  document.getElementById('success').innerHTML = "";


  //Данни от формата
  var username = document.getElementById('username').value;
  var name = document.getElementById('name').value;
  var familyName = document.getElementById('family-name').value;
  var email = document.getElementById('email').value;
  var password = document.getElementById('password').value;
  var street = document.getElementById('street').value;
  var city = document.getElementById('city').value;
  var postalCode = document.getElementById('postal-code').value;

  var passingTests = true;

  //потребителско име
  if(username === null || username === "") passingTests = false;
  else if (username.length < 3 || username.length > 10) passingTests = false;
  if(!passingTests)
  {
    invalid("потребителско име");
    return;
  }
  //име
  //namesRegExp = new RegExp("\w+");
  if(name === null || name === "") passingTests = false;
  else if (name.length > 50) passingTests = false;
  if(!passingTests)
  {
    invalid("име");
    return;
  }
  //фамилия
  if(familyName === null || familyName === "") passingTests = false;
  else if (familyName.length > 50) passingTests = false;
  if(!passingTests)
  {
    invalid("фамилия");
    return;
  }
  //имейл
  emailRegExp = new RegExp("[0-9A-Za-z]+@[0-9A-Za-z]+\\.[0-9A-Za-z]+");
  if(email === null || email === "") passingTests = false;
  else if (!emailRegExp.test(email)) passingTests = false;
  if(!passingTests)
  {
    invalid("имейл");
    return;
  }
  //парола
  passwordRegExpNumbers = new RegExp("[0-9]");
  passwordRegExpCapital = new RegExp("[A-Z]");
  passwordRegExpLowercase = new RegExp("[a-z]");
  if(password === null || password === "") passingTests = false;
  else if (password.length < 6 || password.length > 10) passingTests = false;
  else if (!passwordRegExpNumbers.test(password) || !passwordRegExpCapital.test(password) || !passwordRegExpLowercase.test(password)) passingTests = false;
  if(!passingTests)
  {
    invalid("парола");
    return;
  }

  //пощенски код
  if(postalCode !== null && postalCode !== "")
  {
    postalCodeRegExp = new RegExp("[0-9]{5}-[0-9]{4}");
    if(postalCode.length != 10) passingTests = false;
    else if(!postalCodeRegExp.test(postalCode)) passingTests = false;
    if(!passingTests)
    {
      invalid("пощенски код");
      return;
    }
  }


  //Проверка за съществуващ потребител
  if(passingTests)
  {
    console.log("in tests")
    if(jsonData.some(jsonObj => jsonObj["username"] === username))
    {
      document.getElementsByClassName('error')[0].innerHTML = "Потребителското име е заето";
      passingTests = false;
    }
  }


  //Ако е минало тестовете
  if(passingTests) document.getElementById('success').innerHTML = "Регистрирани сте.";

}
