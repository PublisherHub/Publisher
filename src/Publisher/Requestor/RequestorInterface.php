<?php

namespace Publisher\Requestor;

use Publisher\Requestor\Request;

interface RequestorInterface
{
    /**
     * Executes a request based on the data given by $request.
     * 
     * @param Request $request
     * 
     * @return string
     */
    public function doRequest(Request $request);
}
