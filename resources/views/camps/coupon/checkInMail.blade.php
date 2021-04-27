<style>
    u{
        color: red;
    }
</style>
<body style="font-size:16px;">
<h2 class="center">{{ $applicant->batch->camp->fullName }} 優惠碼通知單</h2>
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>優惠碼：{{ $applicant->name }}</td>
    </tr>
</table>
</body>