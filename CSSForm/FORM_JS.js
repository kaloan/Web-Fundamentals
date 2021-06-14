errorDivs = [];

function invalid(errElId, errFieldGender)
{
  var errText;
  switch (errFieldGender)
  {
    case "m":
      errText = "Невалиден ";
      break;
    case "f":
      errText = "Невалидна ";
      break;
    case "n":
      errText = "Невалидно ";
      break;
    default:
      console.log("ERROR: WRONG GIVEN GRAMMATICAL GENDER OF ERROR FIELD!");
      break;
  }
  var errEl = document.getElementById(errElId);
  errText += errEl.placeholder.toLowerCase();
  var errDiv = document.createElement('div');
  errDiv.classList.add("invalidField");
  errDiv.appendChild(document.createTextNode(errText));
  errEl.insertAdjacentElement("afterEnd", errDiv);

  errEl.oldStyleBorder = new String(errEl.style.border);
  errEl.style.border = "var(--bordSz) solid #B0706D";
  errorDivs.push(errDiv);
}

//Тук взима елементите от изборното меню за по-нататъшна проверка дали някой бара HTML-а преди да пусне заявката. В реална ситуация по-скоро бихме взели масива с валидни стойности директно от базата данни.
selOptions = [];
function captSel()
{
  var selOptionsElements = document.getElementsByTagName('option');
  for(var i = 0; i < selOptionsElements.length; i++)
  {
    selOptions.push(selOptionsElements[i].value);
  }
}

function checkForm()
{
  //Премахва старите дивове за грешка
  errorDivs.forEach((item, i) => {
    item.remove();
  });

  //Възстановява стила на невалидните полета
  var inputFields = document.getElementsByTagName("input");
  for(var i=0; i<inputFields.length; i++)
  {
    inputFields[i].style = null;
  }


  //Данни от формата
  var username = document.getElementById('username').value;
  var password = document.getElementById('password').value;
  var phone = document.getElementById('phone').value;
  var email = document.getElementById('email').value;
  var job = document.getElementById('job').value;
  console.log(job);
  var apartment = document.getElementById('apartment').value;

  //За потребителско име и парола правя проверките като за миналото домашно

  //потребителско име
  if((username === null || username === "") || (username.length < 3 || username.length > 10))
  {
    passingTests = false;
    invalid("username", "n");
  }

  //парола
  passwordRegExpNumbers = new RegExp("[0-9]");
  passwordRegExpCapital = new RegExp("[A-Z]");
  passwordRegExpLowercase = new RegExp("[a-z]");
  if
  (
    (password === null || password === "") ||
    (password.length < 6 || password.length > 10) ||
    (!passwordRegExpNumbers.test(password) || !passwordRegExpCapital.test(password) || !passwordRegExpLowercase.test(password))
  )
  {
    passingTests = false;
    invalid("password", "f");
  }

  //телефонен номер
  phoneRegExpShort = new RegExp("0[0-9]{9}$");
  phoneRegExpFull = new RegExp("\\+(([1-9][0-9])|([1-9][0-9]{2}))[0-9]{9}$");
  if
  (
    (phone === null || phone === "") ||(
    !phoneRegExpShort.test(phone) &&
    !phoneRegExpFull.test(phone))
  )
  {
    passingTests = false;
    invalid("phone", "m");
  }

  //имейл
  emailRegExp = new RegExp("[0-9A-Za-z]+((\\-|\\.)[0-9A-Za-z]+)*@((([0-9A-Za-z]+)|([0-9A-Za-z]+\\-[0-9A-Za-z]+)+)\\.)+[0-9A-Za-z]+");
  if((email === null || email === "") || !emailRegExp.test(email))
  {
    passingTests = false;
    invalid("email", "m");
  }

  //изборно меню
  //Само ако някой бара HTML-a
  if(selOptions.filter(option => option === job).length == 0)
  {
    passingTests = false;
    alert("\"Noli me tangere!\" - HTML, 2021A.D.");
  }

  //апартамент
  //Нямам представа дали има някаква стандартизация и просто гледам за число
  aptRegExp = new RegExp("(^[1-9]$)|(^[1-9][0-9]+$)");
  if((apartment === null || apartment === "") || !aptRegExp.test(apartment))
  {
    passingTests = false;
    invalid("apartment", "m");
  }

  if(!passingTests)
  {
    //Тука може евентуално в по-развит проект да се промени и облика на бутона
    var regBtn = document.getElementById('regBtn');
    //...
  }
}
