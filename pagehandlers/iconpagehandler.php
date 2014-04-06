<?php

namespace PageHandlers;

class IconPageHandler extends PageHandler
{
	public function handle()
	{
		if (isset($_GET['id']))
		{
			$con = \Connection::getConnection();
			$stmt = $con->prepare('SELECT icon FROM types WHERE id=:id');
			if ($stmt->execute(array(':id'=>$_GET['id'])))
			{
				$icon = $stmt->fetchObject()->icon;
				if ($icon == null)
				{
					$this->setReturnCode(404);
					$this->setMessage('<h1>404 Type not found</h1>');
					return $this;				
				}
				
				$this->setHeader('Content-Type', 'image/png');
				$this->setMessage($icon);
				return $this;
			}
			
			$this->setReturnCode(500);
			$this->setMessage('<h1>500 Database Query failed</h1>');
      return $this;
		}
		
		$this->setReturnCode(400);
    $this->setMessage('<h1>400 Parameter id mandatory</h1>');
		return $this;
	}
	
  public function loginRequired() {
    return false;
  }
}
