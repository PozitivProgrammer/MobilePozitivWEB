Alek Veritov
http://easywebscripts.net


настройки размеров - в первых строчках secpic.php

fonts - папка для шрифтов
чтобы подключить новый шрифт просто скопируйте его в папку
шрифт должен быть True Type (ttf)

картинка вызывается так
<img src="secpic.php" alt="защитный код" />

защитный код сохраняется в переменной сессии $_SESSION['secpic'],
т.е. в скрипте обработки формы нужно проверять примерно так
if($_SESSION['secpic']==strtolower($_POST['secpic'])) ...
не забывая в начале скрипта стартовать сессию session_start();


по всем вопросам - на http://easywebscripts.net