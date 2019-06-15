<?php

namespace App\Models;

use App\Actions\PublishPostAction;
use App\Http\Controllers\PostController;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Sluggable;
use App\Services\CommonMark\CommonMark;
use App\Services\OEmbed;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Spatie\Tags\HasTags;

class Post extends Model implements Feedable, Sluggable, HasMedia
{
    public const TYPE_LINK = 'link';
    public const TYPE_TWEET = 'tweet';
    public const TYPE_BLOG = 'blogPost';

    use HasSlug,
        HasTags,
        HasMediaTrait;

    public $with = ['tags'];

    public $dates = ['publish_date'];

    public $casts = [
        'published' => 'boolean',
        'blog_content' => 'boolean'
    ];

    public static function boot()
    {
        parent::boot();

        static::saved(function (Post $post) {
            if ($post->published) {
                static::withoutEvents(function () use ($post) {
                    (new PublishPostAction())->execute($post);
                });
            }
        });
    }

    public function scopePublished(Builder $query)
    {
        $query
            ->where('published', true)
            ->orderBy('publish_date', 'desc')
            ->orderBy('id', 'desc');
    }

    public function scopeBlogContent(Builder $query)
    {
        $query->where('blog_content', true);
    }

    public function scopeScheduled(Builder $query)
    {
        $query
            ->where('published', false)
            ->whereNotNull('publish_date');
    }

    public function getFormattedTextAttribute()
    {
        $html = CommonMark::convertToHtml($this->text);

        return OEmbed::parse($html);
    }

    public function updateAttributes(array $attributes)
    {
        $this->title = $attributes['title'];
        $this->text = $attributes['text'];
        $this->publish_date = $attributes['publish_date'];
        $this->published = $attributes['published'] ?? false;
        $this->blog_content = $attributes['blog_content'] ?? false;
        $this->external_url = $attributes['external_url'];

        $this->save();

        $tags = array_map(function (string $tag) {
            return trim(strtolower($tag));
        }, explode(',', $attributes['tags_text']));

        $this->syncTags($tags);

        return $this;
    }

    public static function getFeedItems()
    {
        return static::published()
            ->orderBy('publish_date', 'desc')
            ->limit(100)
            ->get();
    }

    public static function getBlogContentFeedItems()
    {
        return static::published()
            ->where('blog_content', true)
            ->orderBy('publish_date', 'desc')
            ->limit(100)
            ->get();
    }

    public function toFeedItem()
    {
        return FeedItem::create()
            ->id($this->id)
            ->title($this->title)
            ->summary($this->formatted_text)
            ->updated($this->publish_date)
            ->link($this->url)
            ->author('Liam Hammett');
    }

    public function getUrlAttribute(): string
    {
        return action(PostController::class, [$this->idSlug()]);
    }

    public function getPromotionalUrlAttribute(): string
    {
        if (! empty($this->external_url)) {
            return $this->external_url;
        }

        return $this->url;
    }

    public function hasTag(string $tagName): bool
    {
        return $this->refresh()->tags->contains(function (Tag $tag) use ($tagName) {
            return $tag->name === $tagName;
        });
    }

    public function isLink(): bool
    {
        return $this->getType() === static::TYPE_LINK;
    }

    public function isTweet(): bool
    {
        return $this->hasTag(static::TYPE_TWEET);
    }

    public function isBlog(): bool
    {
        return $this->getType() === static::TYPE_BLOG;
    }

    public function getType(): string
    {
        if ($this->hasTag('tweet')) {
            return static::TYPE_TWEET;
        }

        if ($this->blog_content) {
            return static::TYPE_BLOG;
        }

        return static::TYPE_LINK;
    }

    public function getSluggableValue(): string
    {
        return $this->title;
    }

    public static function getTagClassName(): string
    {
        return Tag::class;
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('post_images');
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150);
    }


    public function getExcerptAttribute(): string
    {
        $excerpt = $this->getManualExcerpt() ?? $this->getAutomaticExcerpt();

        $excerpt = str_replace(
            '<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>',
            '<div data-lazy="twitter"></div>',
            $excerpt,
        );

        return trim($excerpt);
    }

    public function getFormattedExcerptAttribute(): string
    {
        return OEmbed::parse($this->excerpt);
    }

    protected function getManualExcerpt(): ?string
    {
        if ($this->isTweet()) {
            return CommonMark::convertToHtml(trim($this->text), true);
        }

        if (!Str::contains($this->text, '<!--more-->')) {
            return null;
        }

        return CommonMark::convertToHtml(trim(Str::before($this->text, '<!--more-->')), true);
    }

    protected function getAutomaticExcerpt(): string
    {
        $excerpt = $this->text;

        if (mb_strlen($excerpt) == 0) {
            return '';
        }

        $endOfThirdParagraph = $this->nthStrPos($excerpt, "\n\n", 3);

        if ($endOfThirdParagraph === false) {
            return CommonMark::convertToHtml($excerpt, true);
        }

        $excerpt = mb_substr($excerpt, 0, $endOfThirdParagraph);

        return CommonMark::convertToHtml($excerpt, true);
    }

    protected function nthStrPos(string $haystack, string $needle, int $number)
    {
        if ($number <= 1) {
            return mb_strpos($haystack, $needle);
        }

        return mb_strpos($haystack, $needle, $this->nthStrPos($haystack, $needle, $number - 1) + mb_strlen($needle));
    }

    public function getTagsTextAttribute(): string
    {
        return $this
            ->tags
            ->pluck('name')
            ->implode(', ');
    }

    public function getEmojiAttribute(): string
    {
        if ($this->isLink()) {
            return '🔗';
        }

        if ($this->isTweet()) {
            return '🐦';
        }

        if ($this->isBlog()) {
            return '🌟';
        }

        return '';
    }

    public function getFormattedTypeAttribute(): string
    {
        if ($this->isBlog()) {
            return 'Blog';
        }

        return ucfirst($this->getType());
    }

    public function getThemeAttribute(): string
    {
        $tagNames = $this->tags->pluck('name');

        if ($tagNames->contains('laravel')) {
            return '#f16563';
        }

        if ($tagNames->contains('php')) {
            return '#7578ab';
        }

        if ($tagNames->contains('javascript')) {
            return '#f7df1e';
        }

        return '#cbd5e0';
    }

    public function getReadingTimeAttribute(): int
    {
        return (int)ceil(str_word_count(strip_tags($this->text)) / 200);
    }

    public function getIsBlogAttribute(): bool
    {
        return $this->type === Post::TYPE_BLOG;
    }

    public function getExternalUrlHostAttribute(): string
    {
        return parse_url($this->external_url)['host'] ?? '';
    }
}
