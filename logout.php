<?php

		session_start();

		session_destroy();	

		echo "<script type='text/javascript'> parent.window.location.href='index.php' </script>";

?>