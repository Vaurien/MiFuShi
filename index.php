<?php
require 'facebook.php';

$facebook = new Facebook(array(
  'appId'  => '147994818628846',
  'secret' => '660e0a573695098486b6216e8e4a86df',
));

$user = $facebook->getUser();



if ($user) {
  try {
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

if (!$user) {
  $loginUrl = $facebook->getLoginUrl();
}


?>
<!doctype html>
<html>
  <head>
    <title>Index MiFuShi</title>

  </head>
  <body>
   

    <?php if (!$user): ?>
      <div>
        <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
      </div>
    <?php endif ?>

    <?php if ($user): ?>
      <h3>You</h3>
      <img src="https://graph.facebook.com/<?php echo $user; ?>/picture">

      <h3>Your User Object (/me)</h3>
      <pre><?php print_r($user_profile); ?></pre>
    <?php endif ?>



	

<?php
require 'openid.php';
try {
    $openid = new LightOpenID('mifushi.olympe-network.com');
    if(!$openid->mode) {
        if(isset($_POST['openid_identifier'])) {
            $openid->identity = $_POST['openid_identifier'];
			// http://code.google.com/p/lightopenid/wiki/GettingMoreInformation
			$openid->required = array('namePerson/friendly', 'contact/email', 'namePerson'); 
            header('Location: ' . $openid->authUrl());
        }
?>
<form action="" method="post">
    OpenID: <input type="text" name="openid_identifier" /> <button>Submit</button>
</form>
<?php
    } elseif($openid->mode == 'cancel') {
        echo 'User has canceled authentication!';
    } else {
        //echo 'User ' . ($openid->validate() ? $openid->identity . ' has ' : 'has not ') . 'logged in. <br />';
		if ($openid->validate()) {
		$data = $openid->getAttributes();
        $email = $data['contact/email'];
        $nickname = $data['namePerson/friendly'];
		$fullname = $data['namePerson'];
		 echo "Identity : $openid->identity <br>";
        echo "Email : $email <br>";
        echo "First name : $nickname <br>";
		echo "Fullname : $fullname";
} else { echo "Failed";}
    }
} catch(ErrorException $e) {
    echo $e->getMessage();
}
?>
</body>