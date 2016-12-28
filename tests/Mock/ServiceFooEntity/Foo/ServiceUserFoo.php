<?php

namespace Publisher\Entry\Service\Mode\Foo;

use Publisher\Mode\Foo\AbstractFoo;

class ServiceUserFoo extends AbstractFoo
{
    
    public function generateBody()
    {
        return array('status' => $this->message);
    }

}