<p class="footer">

	<?php
	
	if ($this->ion_auth->logged_in())
	{
		echo "<a href='" . site_url('authentication/logout') . "'>Wyloguj</a>";
		echo " | <a href='" . site_url('authentication/edit') . "'>Edytuj dane </a>";
		echo " | <a href='" . site_url('test/clearAuctions') . "'>Wyczyść tablicę wysłanych aukcji (dla testu)</a>";
		echo " | <a href='" . site_url('test/clearCategories') . "'>Wyczyść tablicę kategorii (dla testu)</a>";
		echo " | <a href='" . site_url('test/clearStates') . "'>Wyczyść tablicę regionów (dla testu)</a>";
	}
	
	?>

<span style='float: right;'>Page rendered in <strong>{elapsed_time}</strong> seconds</span>
<div style="clear:both;"></div>
</p>

</div>

</body>

</html>