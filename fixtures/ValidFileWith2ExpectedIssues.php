<?php

class SomeCode
{
    public function go(): void
    {
        $item = new Item('hello');
        $item->updateName('world');  // ERROR        Item::updateName|SomeCode
    }

    public function anotherMethod(Item $item): void
    {
        $item->updateName('world');  // ERROR Item::anotherMethod|SomeCode
    }
}
