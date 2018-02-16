<?php
require_once __DIR__.'/vendor/autoload.php';
 
session_start();
 
/**
 * setClientId(jika domain di ganti silahkan daftar ulang di console.developers.google.com)
 * setClientSecret(jika domain di ganti silahkan daftar ulang di console.developers.google.com)
 * untuk detail cara pendaftaran di console.developers.google.com silahkan googling sendiri
 */
$client = new Google_Client();
$client->setClientId('963518883182-5ob4usi9kv3ltljlmeo7ip8j0vsjv17a.apps.googleusercontent.com');
$client->setClientSecret("QLGXlVVr2lG1gcJM_cpFR753");
$client->setRedirectUri("http://localhost/sso_google/auth.php");
$client->setScopes(array(
    "https://www.googleapis.com/auth/userinfo.email",
    "https://www.googleapis.com/auth/userinfo.profile",
    "https://www.googleapis.com/auth/plus.me"
));
 
if (!isset($_GET['code'])) {
  $auth_url = $client->createAuthUrl();
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {

  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
   
  try {
      // profile
      $plus = new Google_Service_Plus($client);
      $_SESSION['access_profile'] = $plus->people->get("me");
  } catch (\Exception $e) {
      echo $e->__toString();

      $_SESSION['access_token'] = "";
      die;
  }
 
  header('Location:index.php');
}