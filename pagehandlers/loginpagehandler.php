<?php

namespace PageHandlers;

class LoginPageHandler extends PageHandler
{
  public function handle()
  {
  	if (isset($_SESSION['username']) && $_SESSION['username'] != '')
  	{
  		$handler = new OverviewPageHandler();
  		$handler->handle();
  		return $handler;
  	}
  	
  	$to = '';
  	if (isset($_GET['to']))
  	{
  		$to = $_SERVER['REQUST_URI'].explode('to=');
  		$to = $to[count($to)-1];
  	}
  	
    if (isset($_POST['username']) && isset($_POST['password']))
    {
      $user = \Classes\User::first(array('username'=>$_POST['username']));
      if ($user != null)
      {
        if ($user->comparePassword($_POST['password']))
        {
          $_SESSION['username'] = $user->getUsername();
          $_SESSION['userid'] = $user->getUserId();
          
/*          // redirect internally
          $overviewPageHandler = new OverviewPageHandler();
          $overviewPageHandler->handle();
          return $overviewPageHandler;*/
          
          if ($to == '')
          	$to = '?action=overview';
          
          // redirect with HTTP Headers
          $this->setReturnCode(302);
          $this->setHeader('Location', $to);
          $this->setMessage('redirecting...');
        }
      }
      
      // only reached in case of unsuccessful login
      $this->showLoginValidationError();
    }
    
    $this->setPhpTemplate('login');
    $this->setPageData('to', '&to='.$to);
    return $this;
  }
  
  
  public function showLoginValidationError()
  {
    $this->setPageData('loginError', true);
  }
  
	public function loginRequired()
  {
    return false;
  }
}

?>
