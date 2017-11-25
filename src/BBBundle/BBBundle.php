<?php

namespace BBBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BBBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
