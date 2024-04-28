<?php

namespace Ako\Zarinpal\Php\Helpers;

class Receipt
{
    /**
     * @var object
     */
    private $data;
    public function __construct(?array $data = [])
    {
        $this->data = (object) Utils::array_merge_by_reference($data, [
            'code' => null,
            'message' => null,
            'card_hash' => null,
            'card_pan' => null,
            'ref_id' => null,
            'fee_type' => null,
            'fee' => null,
            'order_id' => null,
        ]);
    }

    function __get($prop)
    {
        $prop = strtolower($prop);
        return  $this->data->$prop;
    }

    function __set($prop, $val)
    {
        $prop = strtolower($prop);
        if (!in_array($prop, [
            'code',
            'message',
            'card_hash',
            'card_pan',
            'ref_id',
            'fee_type',
            'fee',
            'order_id',
        ])) {
            return;
        }
        $this->data->$prop = $val;
    }
}
