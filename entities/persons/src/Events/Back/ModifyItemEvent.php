<?php

namespace InetStudio\PersonsPackage\Persons\Events\Back;

use Illuminate\Queue\SerializesModels;
use InetStudio\PersonsPackage\Persons\Contracts\Models\PersonModelContract;
use InetStudio\PersonsPackage\Persons\Contracts\Events\Back\ModifyItemEventContract;

/**
 * Class ModifyItemEvent.
 */
class ModifyItemEvent implements ModifyItemEventContract
{
    use SerializesModels;

    /**
     * @var PersonModelContract
     */
    public $item;

    /**
     * ModifyItemEvent constructor.
     *
     * @param  PersonModelContract  $item
     */
    public function __construct(PersonModelContract $item)
    {
        $this->item = $item;
    }
}
