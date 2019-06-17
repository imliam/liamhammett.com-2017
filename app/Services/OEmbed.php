<?php

namespace App\Services;

use DOMXPath;
use Exception;
use DOMDocument;
use Embed\Embed;
use Illuminate\Support\Str;

final class OEmbed
{
    public static function parse(string $html): string
    {
        $dom = new DOMDocument();

        @$dom->loadHTML(
            '<!DOCTYPE html>' . mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'),
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        $xpath = new DOMXPath($dom);
        $anchors = $xpath->query('//p/a[@href]');

        foreach ($anchors as $node) {
            if (! $node->hasAttribute('href')) {
                continue;
            }

            if (trim($node->textContent) !== trim($node->getAttribute('href'))) {
                continue;
            }

            if (trim($node->textContent) !== trim($node->parentNode->textContent)) {
                continue;
            }

            try {
                $oembed = Embed::create(
                    trim($node->textContent),
                    [
                        'min_image_width' => 100,
                        'min_image_height' => 100,
                        'choose_bigger_image' => true,
                        'images_blacklist' => 'example.com/*',
                        'url_blacklist' => 'example.com/*',
                        'follow_canonical' => true,
                    ]
                );

                $embedCode = $oembed->code ?? static::getOpenGraphBlock($oembed->getResponse()->getContent());

                $embedDom = new DOMDocument();
                @$embedDom->loadHTML(
                    mb_convert_encoding("<div class='oembed'>{$embedCode}</div>", 'HTML-ENTITIES', 'UTF-8'),
                    LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
                );

                $node->parentNode->replaceChild(
                    $dom->importNode($embedDom->documentElement, true),
                    $node
                );
            } catch (Exception $e) {
                // If the try clause failed for whatever reason, the original
                // node would never have been replaced, so we'll leave the
                // original text anchor in there to be displayed instead.
            }
        }

        return Str::after($dom->saveHTML(), '<!DOCTYPE html>');
    }

    protected static function getOpenGraphBlock(string $html): string
    {
        $openGraphTags = static::getOpenGraphTags($html);

        return view('components.opengraph-link', [
            'url' => $openGraphTags['og:url'] ?? null,
            'imageUrl' => $openGraphTags['og:image'] ?? null,
            'siteName' => $openGraphTags['og:site_name'] ?? null,
            'title' => $openGraphTags['og:title'] ?? null,
            'description' => $openGraphTags['og:description'] ?? null,
            'domainName' => parse_url($openGraphTags['og:url'], PHP_URL_HOST) ?? null,
        ]);
    }

    protected static function getOpenGraphTags(string $html): array
    {
        $doc = new DomDocument();
        @$doc->loadHTML($html);
        $xpath = new DOMXPath($doc);
        $metaTags = $xpath->query('//*/meta[starts-with(@property, \'og:\')]');
        $openGraphTags = [];

        foreach ($metaTags as $metaTag) {
            $openGraphTags[$metaTag->getAttribute('property')] = $metaTag->getAttribute('content');
        }

        return $openGraphTags;
    }
}
