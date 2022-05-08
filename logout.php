<?
    require_once('_coresetting.php');

    unset($_SESSION);
    session_destroy();
    header('Location: index.php');            

?>