<?php

namespace App\Services;

use App\Models\Applicant;
use App\Models\Batch;
use App\Models\Camp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class ApplicantTransferService
{
    public function transferApplicant(int $applicantId, int $targetBatchId): array
    {
        return DB::transaction(function () use ($applicantId, $targetBatchId) {
            $applicant = $this->validateApplicant($applicantId);
            $targetBatch = $this->validateTargetBatch($targetBatchId);
            
            if ($applicant->batch_id === $targetBatch->id) {
                throw new Exception('不能轉換到相同的梯次');
            }

            $sourceBatch = $applicant->batch;
            $sourceCamp = $sourceBatch->camp;
            $targetCamp = $targetBatch->camp;

            $isSameCampType = $this->isSameCampType($sourceCamp, $targetCamp);

            $changes = $this->performTransfer($applicant, $targetBatch, $targetCamp, $isSameCampType);

            $this->logTransfer($applicant, $sourceBatch, $targetBatch, $changes, $isSameCampType);

            return [
                'success' => true,
                'message' => $isSameCampType ? '同營隊類型轉換完成' : '跨營隊類型轉換完成，請補完特殊欄位資料',
                'is_same_camp_type' => $isSameCampType,
                'changes' => $changes
            ];
        });
    }

    private function validateApplicant(int $applicantId): Applicant
    {
        $applicant = Applicant::find($applicantId);
        
        if (!$applicant) {
            throw new Exception('申請人不存在');
        }

        return $applicant;
    }

    private function validateTargetBatch(int $targetBatchId): Batch
    {
        $targetBatch = Batch::with('camp')->find($targetBatchId);
        
        if (!$targetBatch) {
            throw new Exception('目標梯次不存在');
        }

        // 檢查梯次開始日期是否在今天以後
        if ($targetBatch->batch_start <= now()->toDateString()) {
            throw new Exception('無法轉換到已經開始或結束的梯次');
        }

        return $targetBatch;
    }

    private function isSameCampType(Camp $sourceCamp, Camp $targetCamp): bool
    {
        return $sourceCamp->table === $targetCamp->table;
    }

    private function performTransfer(Applicant $applicant, Batch $targetBatch, Camp $targetCamp, bool $isSameCampType): array
    {
        $changes = [];
        $originalData = $applicant->toArray();
        $originalCarersCount = $applicant->carers()->count();

        $applicant->update([
            'batch_id' => $targetBatch->id,
            'is_admitted' => false,
            'admitted_at' => null,
            'is_paid' => false,
            'is_attend' => false,
            'group_id' => null,
            'number_id' => null,
            'fee' => $targetCamp->getSetFeeAttribute()
        ]);

        $this->clearCarerRelations($applicant);

        $changes['basic_fields'] = [
            'batch_id' => ['from' => $originalData['batch_id'], 'to' => $targetBatch->id],
            'is_admitted' => ['from' => $originalData['is_admitted'], 'to' => false],
            'is_paid' => ['from' => $originalData['is_paid'], 'to' => false],
            'is_attend' => ['from' => $originalData['is_attend'], 'to' => false],
            'group_id' => ['from' => $originalData['group_id'], 'to' => null],
            'number_id' => ['from' => $originalData['number_id'], 'to' => null],
            'fee' => ['from' => $originalData['fee'], 'to' => $targetCamp->getSetFeeAttribute()],
            'carers_cleared' => ['from' => $originalCarersCount, 'to' => 0]
        ];

        if (!$isSameCampType) {
            $changes['special_fields'] = $this->handleCrossTypeTransfer($applicant);
            
            $this->addTransferNote($applicant, $targetBatch);
        } else {
            $changes['special_fields'] = $this->handleSameTypeTransfer($applicant, $targetBatch);
        }

        return $changes;
    }

    private function handleSameTypeTransfer(Applicant $applicant, Batch $targetBatch): array
    {
        return ['action' => 'preserved', 'note' => '同營隊類型，特殊欄位資料已保留'];
    }

    private function handleCrossTypeTransfer(Applicant $applicant): array
    {
        $deletedTables = [];
        $sourceCampType = $applicant->batch->camp->table;

        $specialTables = [
            'ycamp', 'tcamp', 'ecamp', 'hcamp', 'icamp', 'scamp', 
            'ceocamp', 'acamp', 'lrcamp', 'wcamp', 'utcamp', 'actcamp'
        ];

        foreach ($specialTables as $table) {
            if ($applicant->{$table}) {
                $applicant->{$table}()->delete();
                $deletedTables[] = $table;
            }
        }

        return [
            'action' => 'cleared',
            'deleted_tables' => $deletedTables,
            'note' => '跨營隊類型轉換，已清空特殊欄位資料'
        ];
    }

    private function addTransferNote(Applicant $applicant, Batch $targetBatch): void
    {
        $sourceCampName = $applicant->batch->camp->fullName ?? $applicant->batch->camp->abbreviation;
        $targetCampName = $targetBatch->camp->fullName ?? $targetBatch->camp->abbreviation;
        
        $note = "從「{$sourceCampName}」轉入「{$targetCampName}」，需補完特殊欄位資料";
        
        if ($applicant->expectation) {
            $applicant->expectation = $note . "\n\n" . $applicant->expectation;
        } else {
            $applicant->expectation = $note;
        }
        
        $applicant->save();
    }

    private function logTransfer(Applicant $applicant, Batch $sourceBatch, Batch $targetBatch, array $changes, bool $isSameCampType): void
    {
        $logData = [
            'applicant_id' => $applicant->id,
            'applicant_name' => $applicant->name,
            'source_batch_id' => $sourceBatch->id,
            'source_camp' => $sourceBatch->camp->fullName ?? $sourceBatch->camp->abbreviation,
            'target_batch_id' => $targetBatch->id,
            'target_camp' => $targetBatch->camp->fullName ?? $targetBatch->camp->abbreviation,
            'is_same_camp_type' => $isSameCampType,
            'changes' => $changes,
            'timestamp' => now()
        ];

        Log::info('申請人轉換營隊/梯次', $logData);
    }

    private function clearCarerRelations(Applicant $applicant): void
    {
        $applicant->carers()->detach();
    }
}