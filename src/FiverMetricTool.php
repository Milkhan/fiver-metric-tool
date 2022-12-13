<?php

namespace Milkhan\FiverMetricTool;

use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;
use Laravel\Nova\Metrics\Metric;


class FiverMetricTool extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('fiver-metric-tool', __DIR__.'/../dist/js/tool.js');
        Nova::style('fiver-metric-tool', __DIR__.'/../dist/css/tool.css');
    }



    public function cards()
{
    return [
        (new Metric)
            ->withTotals()
            ->withTrends()
            ->format('0,0')
            ->setName('Users')
            ->setValue($this->users)
            ->withMeta([
                'select' => [
                    'options' => [
                        'All Users' => 'all',
                        'Active Users' => 'active',
                        'Inactive Users' => 'inactive',
                    ],
                ],
            ]),
    ];
}
    /**
     * Build the menu that renders the navigation links for the tool.
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function menu(Request $request)
    {
        return MenuSection::make('Fiver Metric Tool')
            ->path('/fiver-metric-tool')
            ->icon('server');
    }


}
