<?php

###########################################################
## ===================================================== ##
## |
## |	RGSONE / SITE
## |
## |	@author	rudy marc
## |	@email	rgs@rgsone.com
## |	@web	http://rgsone.com
## |
## ===================================================== ##
###########################################################

namespace Rgsone\Controller;

use Rgsone\Controller\BaseController;

/**
 * Class NotFoundController
 * @package Rgsone\Controller
 */
class NotFoundController extends BaseController
{
	public function show404()
	{
		$this->render( '404' );
	}
} 
