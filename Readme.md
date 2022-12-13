#Installation:

composer require milkhan/fiver-metric-tool

#Register it in NovaServiceProvider in tools section

 public function tools()
    {
        return [
            (new \Milkhan\FiverMetricTool\FiverMetricTool())
        ];
    }




