<?php

namespace App\Services\CommonMark;

use Illuminate\Support\Str;
use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Renderer\HeadingRenderer as BaseHeadingRenderer;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;

class HeadingRenderer extends BaseHeadingRenderer
{
    /** @var bool */
    private $isRenderingExcerpt = false;

    public function __construct(bool $isRenderingExcerpt = false)
    {
        $this->isRenderingExcerpt = $isRenderingExcerpt;
    }

    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, $inTightList = false)
    {
        $element = parent::render($block, $htmlRenderer, $inTightList);

        if ($this->isRenderingExcerpt) {
            return new HtmlElement('strong', ['class' => 'text-xl'], $element->getContents());
        }

        $id = Str::slug($element->getContents());
        $headingLevel = max(1, (int) substr($element->getTagName(), 1));

        $element->setAttribute('id', $id);
        $element->setAttribute('class', 'sm:relative');
        $element->setContents(
            new HtmlElement(
                'a',
                [
                    'href' => "#{$id}",
                    'class' => 'permalink sm:absolute ' . $this->getHashMarginClass($headingLevel)
                ],
                str_repeat('#', $headingLevel)
            )
            . ' '
            . $element->getContents()
        );

        return $element;
    }

    /**
     * Get the CSS class to be used to offset the hash from the main heading.
     *
     * @param int $hashCount
     * @return string
     */
    private function getHashMarginClass($hashCount)
    {
        return [
            1 => 'sm:-ml-6',
            2 => 'sm:-ml-8',
            3 => 'sm:-ml-10',
        ][$hashCount] ?? 'sm:-ml-16';
    }
}
