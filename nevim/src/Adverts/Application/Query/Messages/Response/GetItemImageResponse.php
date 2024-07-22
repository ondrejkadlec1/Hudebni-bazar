<?php

namespace Ondra\App\Adverts\Application\Query\Messages;

use Nette\Application\Responses\FileResponse;
use Ondra\App\Shared\Application\Query\Query;

class GetItemImageResponse implements Query
{
    private string $mimeType;
    private FileResponse $fileResponse;

    /**
     * @param string $mimeType
     * @param FileResponse $fileResponse
     */
    public function __construct(string $mimeType, FileResponse $fileResponse)
    {
        $this->mimeType = $mimeType;
        $this->fileResponse = $fileResponse;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @return FileResponse
     */
    public function getFileResponse(): FileResponse
    {
        return $this->fileResponse;
    }
}