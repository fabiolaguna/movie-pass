<?php 

try{
    $accessToken = $helper->getAccessToken();
}catch (\Facebook\Exceptions\FacebookResponseException $e){
    echo "Response Exception: " . $e->getMessage();
}catch (\Facebook\Exceptions\FacebookSDKException $e)
{
    echo "SDK Exception: " . $e->getMessage();
}

if(!$accessToken){
    include_once(VIEWS.'/navLogin.php');
    include_once(VIEWS.'/login.php');
}

$oAuth2Client = $FB->getOAuth2Client();
if(!$accessToken->isLongLived())
{
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
}
$response = $FB->get("/me?fields=id,name,email", $accessToken);
$userData = $response->getGraphNode()->asArray();
include_once(VIEWS . '/navClient.php');
include_once(VIEWS . '/homeClient.php');
?>