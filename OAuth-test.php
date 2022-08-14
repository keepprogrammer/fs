<!DOCTYPE html>
<html lang="pl-PL">
  <head>
    <meta charset="UTF-8">
    <title>Friendschool OAuth authorize</title>
    <?php
      $client_id = '[client_id]';
      $redirect_uri = '[redirect_uri]';
    ?>
    <script src="https://friendschool.ct8.pl/js/api/openapi.js"></script>
    <script>
      var oauth = {
        client_id: '<?=$client_id?>',
        scope: 'email,login,id,drive_key',
        redirect_uri: '<?=$redirect_uri?>',
        display: 'popup',
      };
      function loginFs() {
        FS.auth({
          client_id: oauth.client_id,
          scope: oauth.scope,
          redirect_uri: oauth.redirect_uri,
          display: oauth.display,
        });
      }
    </script>
  </head>
  <body>
    <a onclick="loginFs()">Zaloguj się przez Friendschool</a>
    <br>
    <?php
      $url = 'https://oauth.friendschool.ct8.pl';

      if ($_GET['error']=='access_danied') {
        echo 'ups coś poszło nie tak.';
        exit;
      }

      if (isset($_GET['code'])) {
        $result = false;

        $params = http_build_query(array(
          "client_id" => $client_id,
          "code" => $_GET['code']
        ));

        $token = json_decode(file_get_contents($url . '/user/authorize?'.$params), true);

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
