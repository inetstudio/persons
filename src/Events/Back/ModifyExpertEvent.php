<?php

namespace InetStudio\Experts\Events\Back;

use Illuminate\Queue\SerializesModels;
use InetStudio\Experts\Contracts\Events\Back\ModifyExpertEventContract;

/**
 * Class ModifyExpertEvent.
 */
class ModifyExpertEvent implements ModifyExpertEventContract
{
    use SerializesModels;

    public $object;

    /**
     * Create a new event instance.
     *
     * ModifyExpertEvent constructor.
     *
     * @param $object
     */
    public function __construct($object)
    {
        $this->object = $object;
    }
}
