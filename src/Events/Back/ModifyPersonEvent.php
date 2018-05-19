<?php

namespace InetStudio\Persons\Events\Back;

use Illuminate\Queue\SerializesModels;
use InetStudio\Persons\Contracts\Events\Back\ModifyPersonEventContract;

/**
 * Class ModifyPersonEvent.
 */
class ModifyPersonEvent implements ModifyPersonEventContract
{
    use SerializesModels;

    public $object;

    /**
     * Create a new event instance.
     *
     * ModifyPersonEvent constructor.
     *
     * @param $object
     */
    public function __construct($object)
    {
        $this->object = $object;
    }
}
