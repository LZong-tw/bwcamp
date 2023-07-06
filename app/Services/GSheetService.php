<?php

namespace App\Services;

use Sheets;

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
}