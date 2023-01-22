<?php

declare(strict_types=1);

namespace Blue\Models\Analytics;

final class ClientHints extends \DeviceDetector\ClientHints implements \JsonSerializable
{
    public static function factory(array $headers): self
    {
        $hints =  parent::factory($headers);

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

    public function jsonSerialize(): array
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

    public static function __set_state(array $data)
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
