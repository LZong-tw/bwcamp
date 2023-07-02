<?php

namespace App\Exports;

use App\Models\Applicant;
use Illuminate\Contracts\Container\BindingResolutionException;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class TestExport implements FromView, WithDrawings
{
    public function __construct($camp_id)
    {
        $this->camp_id = $camp_id;
    }
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
        $columns = [...config('camps_fields.ecamp'), 'avatar' => '大頭', 'files' => '檔案'];
        $this->columns = $columns;
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
        $this->applicants = Applicant::select("applicants.*")
            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
            ->join('camps', 'camps.id', '=', 'batchs.camp_id')
            ->where('camps.id', $this->camp_id)
            ->withTrashed()->get();
        $applicants = $this->applicants;


        $rowPosition = 1;
        $drawings = [];
        foreach ($this->applicants as $a_key => &$applicant) {
            $rowPosition++;
            $colPosition = 0;
            foreach ($this->columns as $key => $v) {
                if ($key == "avatar" && $applicant->avatar != "無" && str_contains($applicant->avatar, '.')) {
                    $colName = $this->getNameFromNumber($colPosition);
                    dd($colName . $rowPosition);
                    continue;
                }
                $files = json_decode($applicant->files);
                if ($key == "files" && $applicant->files != "無" && $files) {
                    foreach ($files as $file) {
                        if (!str_contains($file, '.')) {
                            continue;
                        }
                        $colName = $this->getNameFromNumber($colPosition);
                    }
                }
                $colPosition++;
            }
        }

        return view('backend.exports.aggregated_applicants_data', compact('applicants', 'columns'));
    }

    public function drawings()
    {
        //        $drawings = [];
        //        foreach ($this->applicants as $applicant) {
        //            $drawing = new Drawing();
        //            $drawing->setName('666');
        //            $drawing->setDescription('的照片');
        //            $drawing->setPath(storage_path("avatars/hif3rejxFpXe33sYO15kpcK6xXf0B9nAFc0BmKgF.png"));
        //            $drawing->setHeight(50);
        //            $drawing->setCoordinates("B3");
        //            $drawings[] = $drawing;
        //        }
        //        return $drawings;
        $rowPosition = 1;
        $drawings = [];
        foreach ($this->applicants as $a_key => &$applicant) {
            $rowPosition++;
            $colPosition = 0;
            foreach ($this->columns as $key => $v) {
                if ($key == "avatar") {
                    $drawing = new Drawing();
                    $drawing->setName($applicant->name);
                    $drawing->setDescription($applicant->name . '的照片');
                    $drawing->setPath(storage_path("avatars/hif3rejxFpXe33sYO15kpcK6xXf0B9nAFc0BmKgF.png"));
                    $drawing->setHeight(50);
                    $colName = $this->getNameFromNumber($colPosition);
                    $drawing->setCoordinates($colName . $rowPosition);
                    //                    $drawing->setCoordinates("B3");
                    $drawings[] = $drawing;
                }
                $files = json_decode($applicant->files);
                if ($key == "files") {
                    foreach ($files ?? [] as $file) {
                        if (str_contains($file, '.')) {
                            $drawing = new Drawing();
                            $drawing->setName($applicant->name);
                            $drawing->setDescription($applicant->name . '的照片');
                            $drawing->setPath(storage_path("avatars/hif3rejxFpXe33sYO15kpcK6xXf0B9nAFc0BmKgF.png"));
                            $drawing->setHeight(50);
                            $colName = $this->getNameFromNumber($colPosition);
                            $drawing->setCoordinates($colName . $rowPosition);
                            //                            $drawing->setCoordinates("E3");
                            $drawings[] = $drawing;
                        }
                    }
                }
                $colPosition++;
            }
        }
        return $drawings;
    }

    public function getNameFromNumber($num): string
    {
        $numeric = $num % 26;
        $letter = chr(65 + $numeric);
        $num2 = (int)($num / 26);
        if ($num2 > 0) {
            return self::getNameFromNumber($num2 - 1) . $letter;
        } else {
            return $letter;
        }
    }
}
