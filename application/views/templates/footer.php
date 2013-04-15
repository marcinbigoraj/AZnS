<p class="footer">
	<?php
	if ($this -> ion_auth -> logged_in()) {echo "<a href='" . site_url('authentication/logout') . "'>Wyloguj</a>";
	} else {
		echo "<a href='" . site_url('authentication/login') . "'>Zaloguj</a>";
	}
	?>
	<?php
	echo "<a href='" . site_url('a/b') . "'>Wyczyść tablicę wysłanych aukcji (dla testu)</a>";
	?>
	<span style='float: right;'>Page rendered in <strong>{elapsed_time}</strong> seconds</span>
</div>

</body>

</html>