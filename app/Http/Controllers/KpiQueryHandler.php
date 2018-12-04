<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2018/11/27
 * Time: 18:02
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

trait KpiQueryHandler
{
    use FormulaHandler;

    // Query template
    public function query(Request $request)
    {
    }

    // Create query sql
    public function createSql()
    {
    }

    // Create select sql
    public function createSelectSql()
    {
    }

    // Create filter sql
    public function createFilterSql()
    {
    }

    // Create group by sql
    public function createGroupBySql()
    {
    }

    // Create sub query sql for each table
    public function createSubQuerySql()
    {
    }

    // Create subNetwork sql
    public function createSubNetSql()
    {
    }

    // Export query result
    public function download()
    {
    }
}