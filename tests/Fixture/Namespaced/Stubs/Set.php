<?php

declare(strict_types=1);

namespace Stubs;

class Set
{
    private $items = [];

    public function isEmpty()
    {
        return $this->size() === 0;
    }

    public function add($item)
    {
        if (!$this->contains($item)) {
            $this->items[] = $item;
        }
    }

    public function size()
    {
        return count($this->items);
    }

    public function contains($item)
    {
        return $this->indexOf($item) !== false;
    }

    public function remove($item)
    {
        $index = $this->indexOf($item);

        if ($index !== false) {
            unset($this->items[$index]);
        }
    }

    private function indexOf($item)
    {
        return array_search($item, $this->items, true);
    }
}
