<?php

class RequestHandler
{
	public static function handle()
	{
		$pageHandler = RequestHandler::getPageHandlerForAction(isset($_GET['action']) ? $_GET['action'] : null);

		if ($pageHandler != null)
		{
			if ($pageHandler->loginRequired() && !SessionUtility::isLoggedIn())
				$pageHandler = new PageHandlers\LoginPageHandler();

			$pageHandler = $pageHandler->handle();
			$pageHandler->render();
			exit(0);
		}
	}
	
	public static function getPageHandlerForAction($action)
	{
		$pageHandler = null;
		if ($action != null)
		{
			switch ($action)
			{
			case 'login':
				$pageHandler = new PageHandlers\LoginPageHandler();
				break;
			case 'logout':
				$pageHandler = new PageHandlers\LogoutPageHandler();
				break;
			case 'csvimport':
				$pageHandler = new PageHandlers\CsvImportPageHandler();
				break;
			default:
				header('HTTP/1.1 404 Not Found');
				echo '<h1>404 Unkown Action</h1>';
				break;
			}
		}
		else
		{
			$pageHandler = new PageHandlers\StartPageHandler();
		}
		return $pageHandler;
	}
}

?>
