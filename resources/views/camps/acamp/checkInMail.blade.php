<style>
    u{
        color: red;
    }
    .right{
        float: right;
        margin-bottom: 5px;
        clear: both;
    }
</style>
<body style="font-size:16px;">
<h2 class="center">{{ $applicant->batch->camp->fullName }}</h2>
<h2 class="center">報到通知單(二天)</h2>
<table width="100%" style="table-layout:fixed; border: 0;">
    <tr>
        <td>姓名：{{ $applicant->name }}</td>
        <td>組別：{{ $applicant->group }}</td>
        <td>錄取編號：{{ $applicant->group }}{{ $applicant->number }}</td>
    </tr>
</table>
<p>
　　歡迎您參加「2021卓越青年生命探索營」，我們誠摯歡迎您來共享這場心靈饗宴，希望您能獲得豐盛的收穫。
為使二天之研習進行順利，請詳閱下列須知。
</p>
<table width="100%" style="table-layout:fixed; border: 0; word-wrap: break-word;">
<tr><td>        
    <ol>
        <li>研習日期：2021年9月4日(星期六)至9月5日(星期日)
            <p style="font-weight: bolder;">
                2021年9月4日(星期六) <br>
                報到時間(提前上線時間)：13:45 <br>
                正式開始時間：14:00 <br>
                預計結束時間：17:30 <br>
                <br>    
                2021年9月5日(星期日) <br>
                報到時間(提前上線時間)：08:45 <br>
                正式開始時間：09:00 <br>
                預計結束時間：12:00 <br>
                <a style="color: red; font-weight: bolder;">敬請準時報到，若有特殊情形請即時告知關懷員。</a>
            </p>
        </li>            
        <li>線上連結：
            <p>
                ZOOM會議ID：946 3186 5789 <br>
                ZOOM 會議室密碼： 264731 <br>
                ZOOM 加入姓名(顯示名稱)：
                <ol type="A" style="margin-top: 5px">
                    <li>在最前面加個A，後面接組別&姓名</li> 
                    <li>若有分小組，請在後方加-01 、-02，沒有分小組就不加</li> 
                </ol>
                例如： <br>
                【沒有分小組的顯示名稱】<br>
                A02&nbsp;&lt;姓名&gt; <br>
                【有分小組的顯示名稱】 <br>
                A01-01&nbsp;&lt;姓名&gt; <br>
                A01-02&nbsp;&lt;姓名&gt; <br>
                A03-01&nbsp;&lt;姓名&gt; <br>
                <a style="color: red; font-weight: bolder;">關懷員會通知及提醒顯示名稱的方式，若有疑問請告知關懷員。</a> 
            </p>
        </li>
    </ol>
</td></tr>
</table>
<p>
    &#10070;注意事項：
    <ul>
        <li>若您無法全程參加，請告知關懷員，感恩您的善行。</li> 
        <li>若有任何問題，歡迎與關懷員聯絡，或來電本會。</li> 
    </ul>
</p>
<a class="right">財團法人福智文教基金會　敬啟</a><br> 
<a class="right">{{ \Carbon\Carbon::now()->year }}  年　{{ \Carbon\Carbon::now()->month }}  月 　 {{ \Carbon\Carbon::now()->day }}  日</a>
<p>
    聯絡電話：02-7751-6788分機:610408、613091、610301 <br>
    洽詢時間：週一∼週五 10:00 ~ 20:00、週六 10:00～16:00 <br>
    Facebook卓越青年 <a href="https://www.facebook.com/YoungOneCamp" target="_blank" rel="noopener noreferrer">https://www.facebook.com/YoungOneCamp</a> <br>
    2021卓越青年生命探索營官方網站 <a href="http://www.youngone.org.tw/camp/" target="_blank" rel="noopener noreferrer">http://www.youngone.org.tw/camp/</a> 
</p>
</body>