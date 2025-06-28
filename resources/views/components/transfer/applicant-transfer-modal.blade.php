<!-- 轉換營隊/梯次 Modal 元件 -->
<div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transferModalLabel">轉換營隊/梯次</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <strong>目前申請人：</strong>{{ $applicant->name }}<br>
                    <strong>目前梯次：</strong>{{ $batch->name }} ({{ $camp->fullName }})<br>
                    <strong>目前營隊類型：</strong>{{ $camp->table }}
                </div>
                
                <form id="transferForm">
                    @csrf
                    <input type="hidden" id="applicant_id" value="{{ $applicant->id }}">
                    
                    <div class="form-group">
                        <label for="target_batch_id">選擇目標梯次：</label>
                        <select class="form-control" id="target_batch_id" name="target_batch_id" required>
                            <option value="">請選擇目標梯次...</option>
                        </select>
                    </div>
                    
                    <div id="transfer_type_info" class="alert" style="display: none;">
                        <div id="same_type_info" style="display: none;">
                            <i class="fas fa-info-circle text-info"></i>
                            <strong>同營隊類型轉換</strong><br>
                            將保留所有特殊欄位資料，僅重置錄取、繳費、出席等狀態。
                        </div>
                        <div id="cross_type_info" style="display: none;">
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                            <strong>跨營隊類型轉換</strong><br>
                            將清空特殊欄位資料，保留基礎個人資訊。轉換後需要手動補完特殊欄位資料。
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" id="confirmTransfer" disabled>確認轉換</button>
            </div>
        </div>
    </div>
</div>