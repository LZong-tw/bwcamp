<?php

namespace App\Exports;

use App\Models\Applicant;
use App\Models\Batch;
use App\Models\Camp;
use App\Models\CheckIn;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ApplicantsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $columns;
    public function __construct(protected Camp $camp,)
    {
        if($this->camp->applicants()) {
            // 參加者報到日期
                $checkInDates = CheckIn::select('check_in_date')->whereIn('applicant_id', $this->camp->applicants()->pluck('sn'))->groupBy('check_in_date')->get();
            if ($checkInDates) {
                $checkInDates = $checkInDates->toArray();
            } else {
                $checkInDates = array();
            }
            $checkInDates = \Arr::flatten($checkInDates);
            foreach ($checkInDates as $key => $checkInDate) {
                unset($checkInDates[$key]);
                $checkInDates[(string)$checkInDate] = $checkInDate;
            }
            // 各梯次報到日期填充
            $batches = Batch::whereIn('id', $this->camp->applicants()->pluck('batch_id'))->get();
            foreach ($batches as $batch) {
                $date = Carbon::createFromFormat('Y-m-d', $batch->batch_start);
                $endDate = Carbon::createFromFormat('Y-m-d', $batch->batch_end);
                while (1) {
                    if ($date > $endDate) {
                        break;
                    }
                    $str = $date->format('Y-m-d');
                    if (!in_array($str, $checkInDates)) {
                        $checkInDates = array_merge($checkInDates, [$str => $str]);
                    }
                    $date->addDay();
                }
            }
            // 按陣列鍵值升冪排列
            ksort($checkInDates);
            $checkInData = array();
            // 將每人每日的報到資料按報到日期組合成一個陣列
            foreach ($checkInDates as $checkInDate => $v) {
                $checkInData[(string)$checkInDate] = array();
                $rawCheckInData = CheckIn::select('applicant_id')->where('check_in_date', $checkInDate)->whereIn('applicant_id', $this->camp->applicants()->pluck('sn'))->get();
                if ($rawCheckInData) {
                    $checkInData[(string)$checkInDate] = $rawCheckInData->pluck('applicant_id')->toArray();
                }
            }

            // 簽到退時間
            $signAvailabilities = $this->camp->allSignAvailabilities;
            $signData = [];
            $signDateTimesCols = [];

            if ($signAvailabilities) {
                foreach ($signAvailabilities as $signAvailability) {
                    $signData[$signAvailability->id] = [
                        'type' => $signAvailability->type,
                        'date' => substr($signAvailability->start, 5, 5),
                        'start' => substr($signAvailability->start, 11, 5),
                        'end' => substr($signAvailability->end, 11, 5),
                        'applicants' => $signAvailability->applicants->pluck('id')
                    ];
                    $str = $signAvailability->type == "in" ? "簽到時間：" : "簽退時間：";
                    $signDateTimesCols["SIGN_" . $signAvailability->id] = $str . substr($signAvailability->start, 5, 5) . " " . substr($signAvailability->start, 11, 5) . " ~ " . substr($signAvailability->end, 11, 5);
                }
            } else {
                $signData = array();
            }
        }
        $this->columns = ["deleted_at" => "取消時間"];
        if (str_contains($this->camp->table, 'vcamp')) {
            $this->columns = array_merge($this->columns, ["role_section" => '職務組別', "role_position" => '職務']);
        }
        if((!isset($signData) || count($signData) == 0)) {
            if(!isset($checkInDates)) {
                $this->columns = array_merge($this->columns, config('camps_fields.general'), config('camps_fields.' . $this->camp->table) ?? []);
            } else {
                $this->columns = array_merge($this->columns, config('camps_fields.general'), config('camps_fields.' . $this->camp->table) ?? [], $checkInDates);
            }
        } else {
            if(!isset($checkInDates)) {
                $this->columns = array_merge($this->columns, config('camps_fields.general'), config('camps_fields.' . $this->camp->table) ?? [], $signDateTimesCols);
            } else {
                $this->columns = array_merge($this->columns, config('camps_fields.general'), config('camps_fields.' . $this->camp->table) ?? [], $checkInDates, $signDateTimesCols);
            }
        }
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $applicants = $this->camp->applicants()->get();
        return $applicants;
    }

    public function map($applicant): array
    {
        $result = [];
        foreach ($this->columns as $key => $value) {
            $result[] = $applicant->$key;
        }
        return $result;
    }

    public function headings(): array
    {
        $result = [];
        foreach ($this->columns as $key => $value) {
            $result[] = $value;
        }
        return $result;
    }
}
