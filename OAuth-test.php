<!DOCTYPE html>
<html lang="pl-PL">
  <head>
    <meta charset="UTF-8">
    <title>Friendschool OAuth authorize</title>
    <?php
      $client_id = 'YOURS_client_id';
      $redirect_uri = 'YOURS_redirect_uri';
    ?>
    <script src="https://friendschool.ct8.pl/js/api/openapi.js"></script>
    <script>
      var oauth = {
        client_id: '<?=$client_id?>', // klient id aplikacji.
        scope: 'email,login,id', // drive_key - jest poufną informacją.
        redirect_uri: '<?=$redirect_uri?>', // redirect_uri - adres przekierowania gdzie znajduje się callback.
        display: 'page', // page lub popup.
      };
      function loginFs() {
        FS.auth({
          client_id: oauth.client_id,
          scope: oauth.scope,
          redirect_uri: oauth.redirect_uri,
          display: oauth.display,
          response_type: 'code', // ?code=XXX or #token
        });
      }
    </script>
  </head>
  <body>
    <a onclick="loginFs()">Zaloguj się przez Friendschool</a>
    <br>
    <?php
      $url = 'https://oauth.friendschool.ct8.pl'; // url skąd będzie wyciągana informacja.

      if ($_GET['error']=='access_danied') { // jak użytkownik nacisął NIE w dialogie OAuth.
        echo 'ups coś poszło nie tak.';
        exit;
      }

      if (isset($_GET['code'])) {
        $result = false;

        $params = http_build_query(array(
          "client_id" => $client_id,
          "code" => $_GET['code'] // kod który dostaniesz od dialogu OAuth, jak użytkownik nacisął a diaologie Tak.
        ));
        
        $tokenFGC = @file_get_contents($url.'/user/authorize?'.$params);
        $token = json_decode($tokenFGC, true);
        //
        if (strpos($http_response_header[0], "200"))
        {}
        else {
          echo 'Coś poszło nie tak...'; // jak odpowiedź serwera nie równa się 200.
          exit;
        }
        

        if ($token['description']) {
          $userInfo = $token['description'];
          $result = true;
        }
        if ($result) {
          echo "ID: " . $userInfo['id'] . '<br />';
          echo "Imię: " . $userInfo['username'] . '<br />';
          echo "Nazwisko: " . $userInfo['surname'] . '<br />';
          echo "Login: " . $userInfo['login'] . '<br />';
          echo "E-mail: " . $userInfo['email'] . '<br />';
          echo "Klucz dysku: " . $userInfo['drive_key'] . '<br />';
          echo 'Zdjęcia: <img width="150" src="' . $userInfo['img_profile'] . '" />';

        }
      }

    ?>
  </body>
</html>
