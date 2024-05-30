<?php

namespace Rangkotodotcom\Simanang\Services\QrCodes;

use Illuminate\Support\Collection;

interface QrCode
{
    /**
     * @param array $data
     * @return $this
     */
    public function validationQrCode(array $data): self;

    /**
     * @param array $data
     * @return $this
     */
    public function storeQrCode(array $data): self;


    /**
     * @return Collection
     */
    public function getResponse(): Collection;
}
