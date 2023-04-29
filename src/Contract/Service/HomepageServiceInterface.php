<?php

namespace App\Contract\Service;

use App\Dto\ViewModel\WhoIsOnlineViewModel;

interface HomepageServiceInterface
{
    public function getWhoIsOnlineVm(): WhoIsOnlineViewModel;
}