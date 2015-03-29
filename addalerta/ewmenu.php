<!-- Begin Main Menu -->
<div class="phpmaker">
<?php $RootMenu = new cMenu("RootMenu"); ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(1, $Language->MenuPhrase("1", "MenuText"), "alertaslist.php", -1, "", TRUE, FALSE);
$RootMenu->Render();
?>
</div>
<!-- End Main Menu -->
