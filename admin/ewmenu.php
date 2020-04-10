<!-- Begin Main Menu -->
<div class="ewMenu">
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(1, $Language->MenuPhrase("1", "MenuText"), "filterlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(2, $Language->MenuPhrase("2", "MenuText"), "flightlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(3, $Language->MenuPhrase("3", "MenuText"), "hotellist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(4, $Language->MenuPhrase("4", "MenuText"), "roomlist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(6, $Language->MenuPhrase("6", "MenuText"), "dialogue_ruleslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(-1, $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
</div>
<!-- End Main Menu -->
