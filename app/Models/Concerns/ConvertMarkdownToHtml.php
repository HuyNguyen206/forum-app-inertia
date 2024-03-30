<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;

trait ConvertMarkdownToHtml
{
    protected static function booted()
    {
        static::saving(function (Model $model) {
            collect(static::getMarkdownToHtmlMap())
                ->each(function ($toHtmlFieldName, $fromMarkDownFieldName) use ($model) {
                    $model->fill([
                        $toHtmlFieldName => \str($model->$fromMarkDownFieldName)
                            ->markdown(
                                [
                                    'html_input' => 'strip',
                                    'allow_unsafe_links' => false,
                                    'max_nesting_level' => 5
                                ])]);
                });
        });
    }

    protected static function getMarkdownToHtmlMap()
    {
        return [
            'body' => 'body_html',
        ];
    }
}
