<?php

namespace App\Exports;

use App\Models\Applicant;
use App\Models\Batch;
use App\Models\Camp;
use App\Models\CampOrg;
use App\Models\CarerApplicantXref;
use App\Models\CheckIn;
use App\Models\ContactLog;
use App\Models\SignInSignOut;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ApplicantsExport implements WithHeadings, WithMapping, FromCollection, WithDrawings
{
    protected $columns;
    protected $applicants;
    protected $user;
    public function __construct(protected Camp $camp,)
    {
        $this->user = \App\Models\User::find(auth()->id());
        if($this->camp->applicants()) {
            // 參加者報到日期
            $checkInDates = CheckIn::select('check_in_date')->whereIn('applicant_id', $this->camp->applicants()->pluck('applicants.id'))->groupBy('check_in_date')->get();
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
                $rawCheckInData = CheckIn::select('applicant_id')->where('check_in_date', $checkInDate)->whereIn('applicant_id', $this->camp->applicants()->pluck('applicants.id'))->get();
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

        $campTable = $this->camp->table;
        $applicants = Applicant::select("applicants.*", $campTable . ".*", "batchs.name as bName", "applicants.id as sn", "applicants.created_at as applied_at")
            ->join('batchs', 'batchs.id', '=', 'applicants.batch_id')
            ->join('camps', 'camps.id', '=', 'batchs.camp_id')
            ->join($campTable, 'applicants.id', '=', $campTable . '.applicant_id')
            ->where('camps.id', $this->camp->id)
            ->withTrashed()->get();
        foreach($applicants as $applicant) {
            $applicant->id = $applicant->sn;
            if($applicant->fee > 0) {
                if($applicant->fee - $applicant->deposit <= 0) {
                    $applicant->is_paid = "是";
                } else {
                    $applicant->is_paid = "否";
                }
            } else {
                $applicant->is_paid = "無費用";
            }
            if($applicant->trashed()) {
                $applicant->is_cancelled = "是";
            } else {
                $applicant->is_cancelled = "否";
            }
        }
        foreach ($applicants as $a_key => &$applicant) {
            if (!$this->user->canAccessResource(
                $applicant,
                'read',
                $this->camp,
                context: str_contains($this->camp->table, "vcamp") ? "vcampExport" : null,
                target: $applicant)
            ) {
                $applicants->forget($a_key);
                continue;
            }
            foreach($this->columns as $key => $v) {
                if ($key == "avatar") {
                    if ($applicant->avatar == null) {
                        $applicant->$key = "無";
                        continue;
                    }
                    if (!file_exists(storage_path($applicant->avatar))) {
                        $applicant->$key = "無";
                        continue;
                    }
                    $applicant->offsetUnset($key);
                    continue;
                }
                if ($key == "files") {
                    if ($applicant->files == null) {
                        $applicant->$key = "無";
                        continue;
                    }
                    $files = json_decode($applicant->files);
                    $applicant->offsetUnset($key);
                    foreach ($files as $file) {
                        if (!file_exists(storage_path($file))) {
                            $applicant->$key = "無";
                        }
                    }
                    continue;
                }
                if ($key == "group" && str_contains($this->camp->table, "vcamp") && $applicant->user?->roles) {
                    $applicant->$key = $applicant->user->roles->pluck('applicant_group.alias')->implode('、');
                    continue;
                }
                if ($v == "關懷員") {
                    if ($this->user->canAccessResource(
                        new CarerApplicantXref,
                        'read',
                        $this->camp,
                        target: $applicant
                    )) {
                        if ($applicant->carers) {
                            $applicant->$key = $applicant->carers->flatten()->pluck('name')->implode('、');
                        }
                        else {
                            $applicant->$key = "無";
                        }
                    } else {
                        unset($this->columns[$key]);
                        continue;
                    }
                    continue;
                }
                if ($key == "care_log") {
                    if ($this->user->canAccessResource(new ContactLog, 'read', $this->camp, target: $applicant)) {
                        if ($applicant->contactlogs) {
                            $applicant->$key = "";
                            foreach ($applicant->contactlogs as $count => $contactlog) {
                                $applicant->$key .= $contactlog->takenby->name . " @ " . $contactlog->created_at . ": " . $contactlog->notes;
                                if ($count != count($applicant->contactlogs) - 1) {
                                    $applicant->$key .= PHP_EOL;
                                }
                            }
                        }
                        else {
                            $applicant->$key = "無";
                        }
                    } else {
                        unset($this->columns[$key]);
                        continue;
                    }
                }
                // 使用正規表示式抓出日期欄
                if(preg_match('/\d\d\d\d-\d\d-\d\d/', $key)) {
                    if ($this->user->canAccessResource(new CheckIn, 'read', $this->camp, target: $applicant)) {
                        // 填充報到資料
                        if(in_array($applicant->id, $checkInData[$key])) {
                            $applicant->$key = "⭕";
                        } else {
                            $applicant->$key = "➖";
                        }
                    } else {
                        unset($this->columns[$key]);
                        continue;
                    }
                } elseif(str_contains($key, "SIGN_")) {
                    if ($this->user->canAccessResource(new SignInSignOut, 'read', $this->camp, target: $applicant)) {
                        // 填充簽到資料
                        if($signData[substr($key, 5)]['applicants']->contains($applicant->id)) {
                            $applicant->$key = "✔️";
                        } else {
                            $applicant->$key = "❌";
                        }
                    } else {
                        unset($this->columns[$key]);
                        continue;
                    }
                } elseif($key == "role_section") {
                    if ($this->user->canAccessResource(new CampOrg, 'read', $this->camp, target: $applicant)) {
                        $roles = "";
                        $aRoles = $applicant->user?->roles()->where('camp_id', $applicant->vcamp->mainCamp->id)->get() ?? [];
                        foreach ($aRoles as $k => $role) {
                            $roles .= $role->section;
                            if ($k != count($aRoles) - 1) {
                                $roles .= "\n";
                            }
                        }
                        $applicant->$key = $roles;
                    } else {
                        unset($this->columns[$key]);
                        continue;
                    }
                } elseif($key == "role_position") {
                    if ($this->user->canAccessResource(new CampOrg, 'read', $this->camp, target: $applicant)) {
                        $roles = "";
                        $aRoles = $applicant->user?->roles()->where('camp_id', $applicant->vcamp->mainCamp->id)->get() ?? [];
                        foreach ($aRoles as $k => $role) {
                            $roles .= $role->position;
                            if ($k != count($aRoles) - 1) {
                                $roles .= "\n";
                            }
                        }
                        $applicant->$key = $roles;
                    } else {
                        unset($this->columns[$key]);
                        continue;
                    }
                }
            }
        }
        $this->applicants = $applicants;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->applicants;
    }

    public function view(): View
    {
        return view('backend.exports.aggregated_applicants_data', [
            'applicants' => $this->applicants,
            'columns' => $this->columns,
        ]);
    }

    public function map($applicant) : array
    {
        $result = [];
        foreach ($this->columns as $key => $value) {
            $result[] = $applicant->$key;
        }
        return $result;
    }

    public function headings() : array
    {
        $result = [];
        foreach ($this->columns as $key => $value) {
            $result[] = $value;
        }
        return $result;
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

    public function drawings()
    {
        $rowPosition = 1;
        $drawings = [];
        foreach ($this->applicants as $a_key => &$applicant) {
            $rowPosition++;
            $colPosition = 0;
            foreach ($this->columns as $key => $v) {
                if ($key == "avatar" && $applicant->avatar != "無" && str_contains($applicant->avatar, '.')) {
                    $drawing = new Drawing();
                    $drawing->setName($applicant->name);
                    $drawing->setDescription($applicant->name . '的照片');
                    $drawing->setPath(storage_path($applicant->avatar));
                    $drawing->setHeight(50);
                    $colName = $this->getNameFromNumber($colPosition);
                    $drawing->setCoordinates($colName . $rowPosition);
                    $drawings[] = $drawing;
                    continue;
                }
                $files = json_decode($applicant->files);
                if ($key == "files" && $applicant->files != "無" && $files) {
                    foreach ($files as $file) {
                        if (!str_contains($file, '.')) {
                            continue;
                        }
                        $drawing = new Drawing();
                        $drawing->setName($applicant->name);
                        $drawing->setDescription($applicant->name . '的照片');
                        $drawing->setPath(storage_path($file));
                        $drawing->setHeight(50);
                        $colName = $this->getNameFromNumber($colPosition);
                        $drawing->setCoordinates($colName . $rowPosition);
                        $drawings[] = $drawing;
                    }
                }
                $colPosition++;
            }
        }
        return $drawings;
    }
}
