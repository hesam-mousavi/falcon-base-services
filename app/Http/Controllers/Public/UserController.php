<?php

namespace FalconBaseServices\Http\Controllers\Public;

use FalconBaseServices\Helper\Response;
use FalconBaseServices\Services\CurrentUser;

class UserController
{

    public function profile(): void
    {
        Response::json(data: CurrentUser::profile());
    }
}
