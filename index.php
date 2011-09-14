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
