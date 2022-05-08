<?
    //print_html($_SESSION);
    //$_basename = str_replace(".php", "", basename($_SERVER['PHP_SELF']));
    $_sideMenuArr = json_decode(file_get_contents("./_json/sidemenu.json"), true);
?>
<aside class="left-sidebar" data-sidebarbg="skin5">
    <div class="scroll-sidebar">
        <nav class="sidebar-nav">
            <ul id="sidebarnav" class="p-t-30">
                <? 
                    for ($_m=0; $_m < sizeof($_sideMenuArr['menuItems']); $_m++) { 
                        $_mItems = $_sideMenuArr['menuItems'][$_m];
                        $_variable = "";

                        if ($_mItems['permission'].'' == $_SESSION['user_type']){
                            if (isset($_SESSION['user_type_id']) && $_SESSION['user_type_id'].'' != ''){
                                $_variable = "?studentID=".$_SESSION['user_type_id'];
                            }
                ?>
                           <li class="sidebar-item">
                                <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?=$_mItems['url']?>.php<?=$_variable?>" aria-expanded="false">
                                    <i class="<?=$_mItems['icon']?>"></i>
                                    <span class="hide-menu"><?=$_mItems['display']?></span>
                                </a>
                           </li>
                <?
                        }
                    }
                ?>
            </ul>
        </nav>
    </div>
</aside>