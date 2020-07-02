<?php

namespace Theear\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class Autorefresh extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'autorefresh';

    /**
     * @var array
     */
    public $options = [
        // 60  => '1 minute',
        // 120 => '2 minutes',
        null => 'Disabled',
        300 => '5 minutes',
        600 => '10 minutes',
        900 => '15 minutes'
    ];

    /**
     * @var int
     */
    public $default = 30;

    /**
     * Autorefresh constructor.
     * @param string $name
     * @param array $options
     * @param int $default
     */
    public function __construct(string $name = 'Refresh Rate', $options = [], $default = 300)
    {
        $this->name = $name;
        $this->options = empty($options) ? $this->options : $options;
        $this->default = $default;
    }

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query;
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return $this->options;
    }

    /**
     * @return int
     */
    public function default()
    {
        return $this->default;
    }
}
