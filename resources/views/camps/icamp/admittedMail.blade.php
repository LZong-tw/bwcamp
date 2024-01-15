<style>
    u{
        color: red;
    }
</style>
<body style="font-size:16px;">
@if(\Str::contains($applicant->batch->name, "全半程"))
<h2 class="center">{{ $applicant->batch->camp->fullName }}录取通知单</h2>
<p class="card-text">{{ $applicant->name }} 大德：</p>
<p class="card-text">随喜您报名「{{ $applicant->batch->camp->fullName }}」，我们诚挚欢迎您参加本活动，共享这场心灵飨宴。为使正行进行顺利，请详阅下列须知。</p>
<p class="card-text">
    <h4>【活动资讯】</h4>
        <div class="ml-4 mb-2">1.正行日期：<br>
        　　全程2024年2月20日(星期二)至2月25日(星期日)中午结束<br>
        　　半程2024年2月23日(星期五)至2月25日(星期日)中午结束</div>
        <div class="ml-4 mb-2">2.报到时间：<br>
        　　全程2024年2月19日(星期一) 下午17:00前完成报到<br>
        　　半程2024年2月22日(星期四) 下午17:00前完成报到</div>
        <div class="ml-4 mb-2">3.报到地点：福智教育园区 （云林县古坑乡麻园村平和21号）</div>
        <div class="ml-4 mb-2">4.正行团费：<br>
        　　全程每人新台币6000元。<br>
        　　半程每人新台币3500元。</div>
        <div class="ml-4 mb-2">5.请于2024年1月15日前缴款给贵区窗口。</div>
    <h4>【前行缘念】</h4>
        <div class="ml-4 mb-2">我们希请 如亨法师開示，于台湾时间1月28日(日)上午9:00~11:00为全体前行缘念，请务必拨冗参加。</div>
    <h4>【应携带物品】</h4>
        <div class="ml-4 mb-2">1.换洗衣物(多套)、盥洗用具、拖鞋、防寒外套、保暖用品。</div>
        <div class="ml-4 mb-2">2.个人常用药物、防疫药物、口罩。</div>
        <div class="ml-4 mb-2">3.随身背包、环保水杯、环保筷、折叠伞。</div>
        <div class="ml-4 mb-2">4.耳塞（睡觉时易受声音干扰者）</div>
    <h4>【其它注意事项】</h4>
        <div class="ml-4 mb-2">1.祈愿请法团乃团体活动，参加者请做好学习的心态，抱着欢喜心并全配合团体行程、随团住宿，正行期间切勿安排个人行程。</div>
        <div class="ml-4 mb-2">2.抵达会场需爬坡，请衡量自身体力是否适合参加。</div>
        <div class="ml-4 mb-2">3.若有行动不便或其他特殊疾病，请务必事先告知。</div>
        <div class="ml-4 mb-2">4.请自备新台币，不提供外币兑换。</div>
        <div class="ml-4 mb-2">5.离台航班请务必预留交通车程及checkin时间。</div>
</p>
@elseif(\Str::contains($applicant->batch->name, "唐卡展"))
<h2 class="center">{{ $applicant->batch->camp->fullName }}录取行前通知单</h2>
<p class="card-text">{{ $applicant->name }} 大德：</p>
<p class="card-text">随喜您报名「{{ $applicant->batch->camp->fullName }}」，我们诚挚欢迎您参加本活动，共享这场心灵飨宴。为使正行进行顺利，请详阅下列须知。</p>
<p class="card-text">
    <h4>【活动资讯】</h4>
        <div class="ml-4 mb-2">1.正行日期：<br>
        　　2024年1/23、1/30、2/17、2/27、3/1日下午16:00结束</div>
        <div class="ml-4 mb-2">2.报到时间：<br>
        　　前一天下午17:00前完成报到<br>
        　　若不住宿，请来信告知，并于当天早上8:00在宗仰楼一楼里仁门口集合。</div>
        <div class="ml-4 mb-2">3.报到地点：福智教育园区 （云林县古坑乡麻园村平和21号）</div>
        <div class="ml-4 mb-2">4.正行团费：<br>
        　　新台币1500元。若当天报到费用为新台币1000元。</div>
        <div class="ml-4 mb-2">5.请于2024年1月17日前汇款至以下帐户，汇款后请在群组回报末五码<br>
        　　银行：011上海商业储蓄银行<br>
        　　户名：施佳妤<br>
        　　帐号：40203000202671</div>
    <h4>【应携带物品】</h4>
        <div class="ml-4 mb-2">1.换洗衣物、盥洗用具、拖鞋、防寒外套、保暖用品。</div>
        <div class="ml-4 mb-2">2.个人常用药物、防疫药物、口罩。</div>
        <div class="ml-4 mb-2">3.随身背包、环保水杯、环保筷、折叠伞。</div>
        <div class="ml-4 mb-2">4.耳塞（睡觉时易受声音干扰者）</div>
    <h4>【其它注意事项】</h4>
        <div class="ml-4 mb-2">请加入各场次的line，以利联系。<br>
        　　1/23场：<a href="https://line.me/ti/g/mf8Ik0lLYW">https://line.me/ti/g/mf8Ik0lLYW</a><br>
        　　1/30场：<a href="https://line.me/ti/g/ROKgf5KBWu">https://line.me/ti/g/ROKgf5KBWu</a><br>
        　　2/17场：<a href="https://line.me/ti/g/92NgNK4zFh">https://line.me/ti/g/92NgNK4zFh</a><br>
        　　2/27场：<a href="https://line.me/ti/g/Jbagi2Du8v">https://line.me/ti/g/Jbagi2Du8v</a><br>
        　　3/1 场：<a href="https://line.me/ti/g/8YcbsFKyGw">https://line.me/ti/g/8YcbsFKyGw</a></div>
</p>
@else
<br>
內容待確認
<br>
@endif
<br>
<br>
<p class="card-text text-right">主办单位：国际事务法会报名服务处 敬上</p>
<p class="card-text text-right">{{ \Carbon\Carbon::now()->year }} 年 {{ \Carbon\Carbon::now()->month }} 月 {{ \Carbon\Carbon::now()->day }} 日</p>
<p class="card-text">{{ $applicant->batch->camp->fullName }}官方网站 <a href="{{ $applicant->batch->camp->site_url }}" target="_blank" rel="noopener noreferrer">{{ $applicant->batch->camp->site_url }}</a></p>
</body>