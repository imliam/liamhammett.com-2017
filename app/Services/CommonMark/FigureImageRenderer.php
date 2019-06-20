<?php

namespace App\Services\CommonMark;

use League\CommonMark\HtmlElement;
use Illuminate\Support\Facades\Cache;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\Inline\Element\AbstractInline;
use App\Services\CommonMark\Exceptions\NotCloudinaryImageException;
use League\CommonMark\Inline\Renderer\ImageRenderer as BaseImageRenderer;

class FigureImageRenderer extends BaseImageRenderer
{
    public function render(AbstractInline $block, ElementRendererInterface $htmlRenderer)
    {
        $imageElement = parent::render($block, $htmlRenderer);
        $uniqueId = $this->getUniqueId($imageElement->getAttribute('src'));

        $imageElement->setAttribute('class', 'rounded-lg max-w-full');
        $altText = $imageElement->getAttribute('alt');
        $imageElement = $this->makeCloudinaryElement($imageElement);
        $imageElement = $this->makeLightboxElement($imageElement, $uniqueId);

        $figure = new HtmlElement('figure');

        $figure->setContents($imageElement);

        if ($altText) {
            $figcaption = new HtmlElement(
                'figcaption',
                [
                    'class' => 'font-sans text-gray-700 text-xs text-center mt-1',
                    'aria-hidden' => 'true',
                ],
                $altText
            );

            $figure->setContents($figure->getContents() . $figcaption);
        }

        return $figure;
    }

    protected function makeLightboxElement(HtmlElement $imageElement, string $uniqueId): HtmlElement
    {
        $openLink = new HtmlElement('a', ['class' => 'open-lightbox', 'href' => '#' . $uniqueId, 'tabindex' => '-1'], 'Open image in popup');
        $closeLink = new HtmlElement('a', ['class' => 'close-lightbox', 'href' => '#_', 'tabindex' => '-1'], 'Close popup');

        $lightbox = new HtmlElement('div', ['class' => 'lightbox', 'id' => $uniqueId]);
        $lightbox->setContents(
            $openLink .
            $closeLink .
            $imageElement
        );

        return $lightbox;
    }

    private function getUniqueId(string $string): string
    {
        return 'lightbox-' . substr(str_replace(['=', '/', '-'], '', base64_encode($string)), -10);
    }

    private function makeCloudinaryElement(HtmlElement $imageElement): HtmlElement
    {
        $imageUrl = $imageElement->getAttribute('src');

        try {
            $cloudinaryImage = new CloudinaryImage($imageUrl);
        } catch (NotCloudinaryImageException $e) {
            return $imageElement;
        }

        return $this->makeResponsiveCloudinaryImages($cloudinaryImage, $imageElement);
    }

    private function makeResponsiveCloudinaryImages(CloudinaryImage $cloudinaryImage, HtmlElement $originalImageElement): HtmlElement
    {
        $imageUrl = $originalImageElement->getAttribute('src');

        [$originalWidth] = Cache::remember('imagesize_' . $imageUrl, now()->addWeek(), function() use ($imageUrl) {
            return getimagesize($imageUrl);
        });

        $imageTags = [];

        if ($originalWidth >= 1600) {
            $imageTags[] = $this->generateSourceTag($cloudinaryImage, 'webp', 1600, 1280);
            $imageTags[] = $this->generateSourceTag($cloudinaryImage, $cloudinaryImage->getOriginalExtension(), 1600, 1280);
        }

        if ($originalWidth >= 1280) {
            $imageTags[] = $this->generateSourceTag($cloudinaryImage, 'webp', 1280, 1024);
            $imageTags[] = $this->generateSourceTag($cloudinaryImage, $cloudinaryImage->getOriginalExtension(), 1280, 1024);
        }

        if ($originalWidth >= 1024) {
            $imageTags[] = $this->generateSourceTag($cloudinaryImage, 'webp', 1024, 768);
            $imageTags[] = $this->generateSourceTag($cloudinaryImage, $cloudinaryImage->getOriginalExtension(), 1024, 768);
        }

        if ($originalWidth >= 768) {
            $imageTags[] = $this->generateSourceTag($cloudinaryImage, 'webp', 768, 640);
            $imageTags[] = $this->generateSourceTag($cloudinaryImage, $cloudinaryImage->getOriginalExtension(), 768, 640);
        }

        if ($originalWidth >= 640) {
            $imageTags[] = $this->generateSourceTag($cloudinaryImage, 'webp', 640);
            $imageTags[] = $this->generateSourceTag($cloudinaryImage, $cloudinaryImage->getOriginalExtension(), 640);
        }

        $imageTags[] = $this->generateSourceTag($cloudinaryImage, 'webp');
        $imageTags[] = $originalImageElement->__toString();

        return new HtmlElement('picture', [], join('', $imageTags));
    }

    private function generateSourceTag(CloudinaryImage $cloudinaryImage, string $format, int $width = null, int $responsiveBreakpoint = null): HtmlElement
    {
        $sourceTag = new HtmlElement('source', [], '', true);
        $options = ['format' => $format];

        if ($width !== null) {
            $options['width'] = $width;
        }

        $sourceTag->setAttribute('srcset', $cloudinaryImage->generateUrl($options));
        $sourceTag->setAttribute('type', "image/{$format}");

        if ($responsiveBreakpoint !== null) {
            $sourceTag->setAttribute('media', "(min-width:{$responsiveBreakpoint}px)");
        }

        return $sourceTag;
    }
}
