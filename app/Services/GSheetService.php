<?php

namespace App\Services;

use Sheets;
use App\Models\DynamicStat;

class GSheetService
{
    /**
     * Sheet
     */
    public function Sheet($sheet_id, $sheet_name)
    {
        return Sheets::spreadsheet($sheet_id)->sheet($sheet_name);
    }

    /**
     * Get sheet's data
     */
    public function Get($sheet_id, $sheet_name)
    {
        return $this->Sheet($sheet_id, $sheet_name)->get();
    }

    /**
     * Get sheet's first()?
     */
    public function First($sheet_id, $sheet_name)
    {
        return $this->Sheet($sheet_id, $sheet_name)->first();
    }

    /**
     * Get sheet's data range
     */
    public function Range($sheet_id, $sheet_name, $cell_range)
    {
        return $this->Sheet($sheet_id, $sheet_name)->range($cell_range)->get();
    }

    /**
     * Append data to sheet
     */
    public function Append($sheet_id, $sheet_name, $data = [])
    {
        return $this->Sheet($sheet_id, $sheet_name)->append([$data]);
    }

    /**
     * Update sheet's all data
     */
    public function Update($sheet_id, $sheet_name, $data = [])
    {
        return $this->Sheet($sheet_id, $sheet_name)->update([$data]);
    }

    /**
     * Clear sheet's all data
     */
    public function Clear($sheet_id, $sheet_name)
    {
        return $this->Sheet($sheet_id, $sheet_name)->clear();
    }

    public function importAccomodation($camp_id, $sheet_name, $group_name)
    {
        //ds_id = 385/386 (男/女)
        $ds = DynamicStat::select('dynamic_stats.*')
        ->where('urltable_id', $camp_id)
        ->where('urltable_type', 'App\Models\Camp')
        ->where('purpose', 'Accomodation')
        ->where('sheet_name', $sheet_name)
        ->first();

        $sheet_id = $ds->spreadsheet_id;
        //$sheet_name = $ds->sheet_name;
        $cells = $this->Sheet($sheet_id, $sheet_name)->get();

        $titles = $cells[0];
        $num_cols = count($titles);
        $num_rows = count($cells);

        $title_tg1 = "組別";
        $title_tg2 = "棟別_戶別";
        $title_tg3 = "房號";
        $title_tg4 = "提供床位數";
        $colidx1 = 0;
        $colidx2 = 0;
        $colidx3 = 0;
        $colidx4 = 0;

        //find title
        for ($i = 1; $i < $num_cols; $i++) {
            if (str_contains($titles[$i], $title_tg1)) {
                $colidx1 = $i;
            } elseif (str_contains($titles[$i], $title_tg2)) {
                $colidx2 = $i;
            } elseif (str_contains($titles[$i], $title_tg3)) {
                $colidx3 = $i;
            } elseif (str_contains($titles[$i], $title_tg4)) {
                $colidx4 = $i;
            }
        }

        $accomodations = array();
        for ($j = 1; $j < $num_rows; $j++) {
            $data = $cells[$j];
            if ($data[$colidx1] == $group_name) {
                $accomodation['apartment'] = $data[$colidx2];
                $accomodation['room'] = $data[$colidx3];
                $accomodation['beds'] = $data[$colidx4];
                array_push($accomodations, $accomodation);
            }
        }
        return $accomodations;
    }
}
