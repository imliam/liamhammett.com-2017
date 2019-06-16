<?php

namespace App\Services\CommonMark;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Renderer\ImageRenderer as BaseImageRenderer;

class FigureImageRenderer extends BaseImageRenderer
{
    /** @var string */
    private $uniqueId = '';

    public function render(AbstractInline $block, ElementRendererInterface $htmlRenderer)
    {
        $element = parent::render($block, $htmlRenderer);
        $uniqueId = $this->getUniqueId($element->getAttribute('src'));

        $element->setAttribute('class', 'rounded-lg max-w-full');
        $element->setAttribute('id', $this->uniqueId);
        $altText = $element->getAttribute('alt');
        $element = $this->makeLightboxElement($element, $uniqueId);

        $figure = new HtmlElement('figure');

        $figure->setContents($element);

        if ($altText) {
            $figcaption = new HtmlElement(
                'figcaption',
                [
                    'class' => 'font-sans text-gray-600 text-xs text-center mt-2',
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

    private function getUniqueId($string)
    {
        return 'image-' . substr(str_replace(['=', '/', '-'], '', base64_encode($string)), -10);
    }
}
