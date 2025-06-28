<!-- 轉換營隊/梯次按鈕元件 -->
@if($currentUser->canAccessResource(new \App\Models\Applicant, 'write', $camp))
    <button class="btn btn-warning mr-2" onclick="openTransferModal()">轉換營隊/梯次</button>
@endif