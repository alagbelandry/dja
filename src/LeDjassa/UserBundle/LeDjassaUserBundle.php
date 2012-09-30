<?php

namespace LeDjassa\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class LeDjassaUserBundle extends Bundle
{
	public function getParent()
    {
        return 'FOSUserBundle';
    }
}
