<?php

class SomeCode
{
    public function go(): void
    {
        $item = new Item('hello');
        $item->updateName('world');  // ERROR Item
    }
}
