<?php

namespace App\DB;

class BaseValidation {

    public array $items = [];
    public string $type;
    public bool $enable;

    public function addItem($item)
    {
        echo 'start';

        $this->items[] = $item;
//        throw new \Exception();

        echo 'start 2';

        return $this;
    }


    public function items()
    {

        return $this->items;
    }
}

class Validation extends BaseValidation {
    public function removeItemWithIndex($index)
    {
        echo 'hello';
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        return $this;
//        return new self();
    }
}

function test()
{
    $validation = new Validation;
    try {
        $newItem = $validation->addItem('huy')->removeItemWithIndex(0);
        echo "not reach heare";
    } catch (\Throwable $e) {
    }
    echo 'good bye';
    var_dump($validation->items());
}
 test();
//var_dump($validation->items(), $newItem->items());




