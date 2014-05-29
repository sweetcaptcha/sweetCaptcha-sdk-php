<?php

// require sweetcaptcha php sdk, don't forget to set up your credentials first
require_once('sweetcaptcha.php');

if (empty($_POST)) {
  // print sweetcaptcha in your form
?>

  <form method="post">
    <p>This is your form you like SweetCapcha to protect</p>
    <p>You can set up it normally as you like <input type="text" name="name" value="" placeholder="Name" /></p>
    <!-- implement sweetcaptcha -->
    <?php echo $sweetcaptcha->get_html() ?>
    <!-- continue with your form -->
    <input type="submit" />
  </form>

<?php

} else { 

  // looks like someone has submitted a form, let's validate it
  if (isset($_POST['sckey']) and isset($_POST['scvalue']) and $sweetcaptcha->check(array('sckey' => $_POST['sckey'], 'scvalue' => $_POST['scvalue'])) == "true") {
    // success! your form was validated
    // do what you like next ...
    echo "Success! carry on if you will";
  }
  else {
    // alas! the validation has failed, the user might be a spam bot or just got the result wrong
    // handle this as you like
    echo "Boohoo! captcha validation failed!";
  }

}

?>
