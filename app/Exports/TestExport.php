<?php

namespace App\Exports;

use App\Models\Applicant;
use Illuminate\Contracts\Container\BindingResolutionException;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class TestExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Applicant::all();
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws BindingResolutionException
     */
    public function view(): View
    {
        $columns = [...config('camps_fields.ecamp')];
        // change key sn to id, key applied_at to created_at
        foreach ($columns as $key => $column) {
            if ($key === 'sn') {
                unset($columns[$key]);
                $columns['id'] = 'id';
            }
            if ($key === 'applied_at') {
                unset($columns[$key]);
                $columns['created_at'] = 'created_at';
            }
            if ($key === 'bName') {
                unset($columns[$key]);
            }
            if ($key === 'group') {
                unset($columns[$key]);
            }
            if ($key === 'region') {
                unset($columns[$key]);
            }
        }
        return view('backend.exports.aggregated_applicants_data', [
            'applicants' => Applicant::whereId(18323)->get(),
            'columns' => $columns
        ]);
    }
}
