<?php
/**
 * Created by PhpStorm.
 * User: sotheavin
 * Date: 10/5/19
 * Time: 11:05 PM
 */

namespace App\Http\Controllers\Admin;


use Yajra\DataTables\DataTables;

class GuestDataTable extends DataTables
{
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->parameters([
                'buttons'   =>   ['excel'],
            ]);

    }
}