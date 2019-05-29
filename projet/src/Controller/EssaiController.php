
<?php

	namespace App\Controller;
	use Symfony\Component\HttpFoundation\Response;
	class EssaiController{
	publicfunction number()
		{
			$number = random_int(0, 100);
			return new Response('<html><body>Essai number: '.$number.'</body></html>');
		}
	}

?>