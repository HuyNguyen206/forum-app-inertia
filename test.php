<?php

namespace App\DB;

class BaseValidation {

    public array $items = [];
    public string $type;
    public bool $enable;

    public function addItem($item)
    {
        $this->items[] = $item;

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
        unset($this->items[$index]);
        $this->items = array_values($this->items);
//        return $this;
        return new self();
    }
}

$validation = new Validation;
$newItem = $validation->addItem('huy')->addItem('nhung')->addItem('uyen')->removeItemWithIndex(0)->addItem('huy')->addItem('mint');

var_dump($validation->items(), $newItem->items());




