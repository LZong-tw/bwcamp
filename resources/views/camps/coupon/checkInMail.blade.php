{{-- 敬致　日尋好友，您好：<br>
<br>
          專屬日尋好友的現場優惠來了！於5/15(六)、5/16(日)演唱會現場購買商品，結帳時出示附件QR code，由工作人員做驗證核銷即可享有8折優惠！還未使用的日友們要把握機會喔！<br>
<br>
一、注意事項<br>
1.本優惠不得與其他優惠或折扣活動合併使用。<br>
2.優惠使用次數限乙次，使用達上限即失效，故建議您選購商品時，記得確認您想要的商品種類與數量，以確保您的權益喔！<br>
<br>
二、其他相關連結<br>
<a href="https://linktr.ee/Gcmwithu">https://linktr.ee/Gcmwithu</a>  所有你需要的酷東西，這裡通通都有<br>
<br>
三、問題洽詢<br>
以上如有問題，可透過Line社群或FB粉絲專頁洽詢，謝謝您！<br>
<br>
<br>
       再次感謝您對讚頌以及福智青年的支持，也期待您的蒞臨<br>
       <br>
       敬祝<br>
平安喜樂<br>
<br>
                                                                  福智青年讚頌團     敬上 --}}
<style>
    body {
        margin: 0;
    }

    @page {
        margin: 0;
    }
</style>

<body background="{{ url("img/bg.jpg") }}" style="background-repeat: no-repeat; background-size: 650px;">
<img src="data:image/png;base64,{{ \DNS2D::getBarcodePNG('{"applicant_id":"' . $applicant->id . '"}', 'QRCODE') }}" alt="" width="180px" style="margin-left: 406px; margin-top: 698px;">
<div style="margin-left: 106px; font-size: 64px;margin-top: -250px;">
    {{ $applicant->name }}
</div>
<div style="margin-left: 106px;margin-top: 85px;font-size: 40px;">
    {{ $applicant->group }} 排 {{ $applicant->number }} 號
</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</body>
