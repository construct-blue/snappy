<?php

declare(strict_types=1);

namespace Blue\Models\Analytics\Tracker;

use Blue\Core\Database\Storable;

final class ClientHints extends \DeviceDetector\ClientHints implements Storable
{
    public static function factory(array $headers): self
    {
        $hints = parent::factory($headers);

        return new self(
            $hints->model,
            $hints->platform,
            $hints->platformVersion,
            $hints->uaFullVersion,
            $hints->fullVersionList,
            $hints->mobile,
            $hints->architecture,
            $hints->bitness,
            $hints->app
        );
    }

    public function toStorage(): array
    {
        return [
            'model' => $this->model,
            'platform' => $this->platform,
            'platformVersion' => $this->platformVersion,
            'uaFullVersion' => $this->uaFullVersion,
            'fullVersionList' => $this->fullVersionList,
            'mobile' => $this->mobile,
            'architecture' => $this->architecture,
            'bitness' => $this->bitness,
            'app' => $this->app,
        ];
    }

    public static function fromStorage(array $data): static
    {
        return new ClientHints(
            $data['model'],
            $data['platform'],
            $data['platformVersion'],
            $data['uaFullVersion'],
            $data['fullVersionList'],
            $data['mobile'],
            $data['architecture'],
            $data['bitness'],
            $data['app'],
        );
    }
}
