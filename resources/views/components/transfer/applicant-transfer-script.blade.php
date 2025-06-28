<!-- 轉換營隊/梯次 JavaScript 元件 -->
<script>
    let availableBatches = [];
    const currentCampTable = '{{ $camp->table }}';
    const currentBatchId = {{ $batch->id }};

    function openTransferModal() {
        // 載入可用梯次
        loadAvailableBatches();
        $('#transferModal').modal('show');
    }

    function loadAvailableBatches() {
        fetch('/api/batches/available', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                availableBatches = data.batches;
                populateBatchSelect();
            } else {
                alert('載入梯次失敗：' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('載入梯次時發生錯誤');
        });
    }

    function populateBatchSelect() {
        const select = document.getElementById('target_batch_id');
        select.innerHTML = '<option value="">請選擇目標梯次...</option>';
        
        availableBatches.forEach(batch => {
            // 排除目前的梯次
            if (batch.id !== currentBatchId) {
                const option = document.createElement('option');
                option.value = batch.id;
                option.textContent = batch.display_name;
                option.dataset.campTable = batch.camp_table;
                select.appendChild(option);
            }
        });
    }

    // 當選擇梯次時顯示轉換類型資訊
    document.getElementById('target_batch_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const confirmBtn = document.getElementById('confirmTransfer');
        const typeInfo = document.getElementById('transfer_type_info');
        const sameTypeInfo = document.getElementById('same_type_info');
        const crossTypeInfo = document.getElementById('cross_type_info');
        
        if (this.value) {
            const targetCampTable = selectedOption.dataset.campTable;
            const isSameType = currentCampTable === targetCampTable;
            
            // 顯示轉換類型資訊
            typeInfo.style.display = 'block';
            if (isSameType) {
                typeInfo.className = 'alert alert-info';
                sameTypeInfo.style.display = 'block';
                crossTypeInfo.style.display = 'none';
            } else {
                typeInfo.className = 'alert alert-warning';
                sameTypeInfo.style.display = 'none';
                crossTypeInfo.style.display = 'block';
            }
            
            confirmBtn.disabled = false;
        } else {
            typeInfo.style.display = 'none';
            confirmBtn.disabled = true;
        }
    });

    // 確認轉換
    document.getElementById('confirmTransfer').addEventListener('click', function() {
        const applicantId = document.getElementById('applicant_id').value;
        const targetBatchId = document.getElementById('target_batch_id').value;
        const selectedOption = document.getElementById('target_batch_id').options[document.getElementById('target_batch_id').selectedIndex];
        const targetCampTable = selectedOption.dataset.campTable;
        const isSameType = currentCampTable === targetCampTable;
        
        if (!targetBatchId) {
            alert('請選擇目標梯次');
            return;
        }
        
        // 顯示確認對話框
        const confirmMessage = isSameType 
            ? `確定要將申請人轉換到「${selectedOption.textContent}」嗎？\n\n同營隊類型轉換將保留所有特殊欄位資料。`
            : `確定要將申請人轉換到「${selectedOption.textContent}」嗎？\n\n⚠️ 跨營隊類型轉換將清空特殊欄位資料，需要手動補完。`;
            
        if (!confirm(confirmMessage)) {
            return;
        }
        
        // 執行轉換
        this.disabled = true;
        this.textContent = '轉換中...';
        
        fetch('/api/applicant/transfer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                applicant_id: applicantId,
                target_batch_id: targetBatchId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('轉換成功！\n\n' + data.message);
                // 重新載入頁面
                window.location.reload();
            } else {
                alert('轉換失敗：' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('轉換時發生錯誤');
        })
        .finally(() => {
            this.disabled = false;
            this.textContent = '確認轉換';
        });
    });
</script>