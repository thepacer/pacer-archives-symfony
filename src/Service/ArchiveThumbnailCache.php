<?php

namespace App\Service;

class ArchiveThumbnailCache
{
    protected $cacheBasePath;

    public function __construct(string $kernelProjectDir)
    {
        $this->cacheBasePath = $kernelProjectDir.'/var/cache/archive-thumbnails';
    }

    public function getPath(string $archiveKey): string
    {
        return $this->cacheBasePath.'/'.$this->getCacheFilename($archiveKey);
    }

    public function has(string $archiveKey): bool
    {
        return is_file($this->getPath($archiveKey));
    }

    public function store(string $archiveKey, string $contents): void
    {
        if (!is_dir($this->cacheBasePath)) {
            mkdir($this->cacheBasePath, 0775, true);
        }

        file_put_contents($this->getPath($archiveKey), $contents);
    }

    protected function getCacheFilename(string $archiveKey): string
    {
        return sha1($archiveKey).'.img';
    }
}
