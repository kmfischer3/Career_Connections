<?php

if ( !empty($_SESSION['attributes']) || ( !empty($_SESSION['searchterm']) ) ) {

	echo "<a href=\"#\" style=\"color:red;\" onclick=\"submitForm(0, '');\"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span> clear filters</a>";

}

?>
