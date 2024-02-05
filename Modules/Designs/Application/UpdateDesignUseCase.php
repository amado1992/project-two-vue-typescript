<?php

namespace Modules\Designs\Application;

use Illuminate\Support\Facades\Log;
use Modules\Designs\Entities\Design;
use Modules\Designs\Events\DesignUpdated;
use Modules\Designs\Events\UpdatingDesign;
use Modules\Quotes\Application\UpdateQuoteUseCase;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Throwable;

/**
 * @author Abel David.
 */
class UpdateDesignUseCase
{
    private const FILES_KEY = 'files';
    private const FILES_TO_REMOVE_KEY = 'files_to_remove';

    /**
     * @param UpdateQuoteUseCase $updateQuoteUseCase
     */
    public function __construct(
        private readonly UpdateQuoteUseCase $updateQuoteUseCase
    )
    {
        //
    }

    /**
     * @param array $data
     * @param Design $design
     * @return bool
     */
    public function __invoke(array $data, Design $design): bool
    {
        $oldDesign = clone $design;

        if ($this->fileIsDirty($data)) {

            UpdatingDesign::dispatch($design);
        }

        $wasChanged = ($this->updateQuoteUseCase)($data, $design->quote);
        $wasChanged = $this->syncFiles($data, $design) || $wasChanged;

        if ($wasChanged) {

            $design->fill([
                'updated_by' => auth()->user()->getAuthIdentifier()
            ])->save();

            DesignUpdated::dispatch($design, $oldDesign);
        }

        return $wasChanged;
    }

    /**
     * @param array $data
     * @param Design $design
     * @return bool
     */
    private function syncFiles(array $data, Design $design): bool
    {
        $wasChanged = false;

        if (isset($data[self::FILES_TO_REMOVE_KEY])) {

            $wasChanged = true;

            foreach ($data[self::FILES_TO_REMOVE_KEY] as $file) {

                $design->media()->delete($file);
            }
        }

        if (isset($data[self::FILES_KEY])) {

            foreach ($data[self::FILES_KEY] as $file) {

                $wasChanged = true;

                try {

                    $design->addMedia($file)
                        ->toMediaCollection();

                } catch (Throwable $e) {

                    Log::error($e->getMessage());
                }
            }
        }

        return $wasChanged;
    }

    /**
     * @param array $data
     * @return bool
     */
    private function fileIsDirty(array $data): bool
    {
        if (isset($data[self::FILES_KEY]) && count($data[self::FILES_KEY])) {

            return true;
        }

        if (isset($data[self::FILES_TO_REMOVE_KEY]) && count($data[self::FILES_TO_REMOVE_KEY])) {

            return true;
        }

        return false;
    }
}
