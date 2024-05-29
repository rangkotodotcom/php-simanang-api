<?php

namespace Rangkotodotcom\Simanang\Services\Schools;

use Illuminate\Support\Collection;

interface School
{
    /**
     * @return Collection
     */
    public function getResponse(): Collection;
}
