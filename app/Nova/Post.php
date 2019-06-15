<?php

namespace App\Nova;

use App\Models\Post as PostModel;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inspheric\NovaDefaultable\HasDefaultableFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Spatie\TagsField\Tags;

class Post extends Resource
{
    use HasDefaultableFields;

    public static $model = PostModel::class;

    public static $title = 'title';

    public static $search = [
        'title',
        'text',
    ];

    public function fields(Request $request)
    {
        return [
            new Panel('Post', [
                Text::make('Title')
                    ->sortable()
                    ->rules('required')
                    ->displayUsing(function (string $title) {
                        return Str::limit($title, 50);
                    }),

                Text::make('', function () {
                    if (! $this->exists) {
                        return '';
                    }

                    return '<a href="' . url($this->url) . '">Show</a>';
                })->asHtml(),

                Markdown::make('Text')
                    ->rules('required'),

                Code::make('Styles')
                    ->language('sass'),

                Code::make('Scripts')
                    ->language('javascript'),

                Images::make('Images', 'post_images')
                    ->conversionOnDetailView('thumb')
                    ->conversionOnIndexView('thumb')
                    ->conversionOnForm('thumb')
                    ->fullSize()
                    ->withResponsiveImages(),

                Tags::make('Tags'),

                DateTime::make('Publish date')
                    ->hideFromIndex()
                    ->sortable(),

                Text::make('Author')
                    ->withMeta([
                        'value' => auth()->user()->name ?? '',
                    ]),
            ]),

            new Panel('Meta', [
                Text::make('External url')
                    ->hideFromIndex(),

                Boolean::make('Published'),

                Boolean::make('Blog content'),
            ]),
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->orderByDesc('publish_date');
    }
}
