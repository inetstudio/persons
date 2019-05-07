<?php

namespace InetStudio\PersonsPackage\Persons\Contracts\Models;

use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use InetStudio\AdminPanel\Base\Contracts\Models\BaseModelContract;

/**
 * Interface PersonModelContract.
 */
interface PersonModelContract extends BaseModelContract, HasMedia, Auditable
{
}
