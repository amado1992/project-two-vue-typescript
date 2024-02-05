<?php

namespace Modules\Common\Services;

use Exception;
use Illuminate\Foundation\Vite;

/**
 * @author Abel David.
 */
class ViteService extends Vite
{
    /**
     * @param string $asset
     * @param string $buildDirectory
     * @return array
     * @throws Exception
     */
    public function assetData(string $asset, string $buildDirectory): array
    {
        $buildDirectory ??= $this->buildDirectory;

        if ($this->isRunningHot()) {

            return [
                'file' => $this->hotAsset($asset),
                'src' => $asset,
                'isEntry' => true
            ];
        }

        $chunk = $this->chunk($this->manifest($buildDirectory), $asset);

        $chunk['file'] = $buildDirectory.'/'.$chunk['file'];

        return $chunk;
    }
}
