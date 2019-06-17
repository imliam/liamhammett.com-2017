<?php

namespace App\Services\CommonMark;

use Cloudinary;
use Illuminate\Support\Str;
use App\Services\CommonMark\Exceptions\NotCloudinaryImageException;

final class CloudinaryImage
{
    /** @var string */
    protected $cloudName;

    /** @var string */
    protected $type;

    /** @var string */
    protected $originalOptions;

    /** @var string */
    protected $filePath;

    /** @var string */
    protected $originalExtension;

    /** @var string */
    protected $filePathWithoutExtension;

    /**
     * @throws \App\Services\CommonMark\Exceptions\NotCloudinaryImageException
     */
    public function __construct(string $url)
    {
        $urlParts = parse_url($url);

        if (!isset($urlParts['host'], $urlParts['path']) || $urlParts['host'] !== 'res.cloudinary.com') {
            throw new NotCloudinaryImageException('Image URL does not follow Cloudinary format.');
        }

        $urlPathParts = explode('/', ltrim($urlParts['path'], '/'));

        [$cloudName, /* 'image' */, $type, $originalOptions] = $urlPathParts;
        $this->cloudName = $cloudName;
        $this->type = $type;
        $this->originalOptions = $originalOptions;

        // Everything after the predefined format - this will be the path to the
        // image in Cloudinary, including any buckets and directories it's in.
        $filePath = join('/', array_slice($urlPathParts, 4));

        if (!Str::startsWith($urlParts['path'], "/{$cloudName}/image/upload/")) {
            throw new NotCloudinaryImageException('Image URL does not follow Cloudinary format.');
        }

        $this->originalExtension = ltrim(substr($filePath, strrpos($filePath, '.')), '.');
        $this->filePathWithoutExtension = substr($filePath, 0, strrpos($filePath, '.'));
    }

    public function generateUrl(array $options): string
    {
        $options = $options + $this->getDefaultOptions();

        return Cloudinary::cloudinary_url(
            $this->filePathWithoutExtension,
            $options
        );
    }

    protected function getDefaultOptions(): array
    {
        return [
            'secure' => true,
            'cloud_name' => $this->cloudName,
            'format' => $this->originalExtension ?? 'jpg',
            'type' => $this->type ?? 'upload',
        ];
    }

    public function getOriginalExtension(): string
    {
        return $this->originalExtension;
    }
}