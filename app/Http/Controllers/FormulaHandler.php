<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2018/11/28
 * Time: 10:53
 */

namespace App\Http\Controllers;


trait FormulaHandler
{
    // Parse formula.
    // The array format is [{'counter' => 'counter1', 'table' => 'tableName', 'agg' => 'max'} ....]
    public function parserKpi($formulas)
    {
        foreach ($formulas as $formula) {
            // do parse kpi
        }
        return array();
    }

    // Parse counter with '_'
    private function parseInternalCounter($counter)
    {

    }

}