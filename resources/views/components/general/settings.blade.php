<div>
    <!-- Live as if you were to die tomorrow. Learn as if you were to live forever. - Mahatma Gandhi -->
    <span class="text-danger font-weight-bold">
        @if($is_vcamp)
            <button type="submit" class="btn btn-success btn-sm" onclick=""> << 返回義工名單</button>
            &nbsp;&nbsp;
            將所選義工設定為{{ ($is_vcamp && $is_care) ? '第' : '' }}
        @else
            <button type="submit" class="btn btn-success btn-sm" onclick=""> << 返回學員名單</button>
            &nbsp;&nbsp;
        @endif

        @if($is_vcamp && !$is_care)
            <select required name='volunteer_group' onChange=''>
                <option value=''>- 請選擇 -</option>
                <option value='秘書'>秘書</option>
                <option value='資訊'>資訊</option>
                <option value='關懷'>關懷</option>
                <option value='教務'>教務</option>
                <option value='行政'>行政</option>
            </select>
            組
            <select required name='volunteer_work' onChange=''>
                <option value=''>- 請選擇 -</option>
                <option value='總護持'>總護持</option>
                <option value='副總護持'>副總護持</option>
                <option value='文書'>文書</option>
                <option value='大組長'>大組長</option>
                <option value='副大組長'>副大組長</option>
            </select>
            職務
        @else
            @if(!$is_vcamp && $is_care)
                將所選學員之關懷員設定為
                <select required name='attendee_care' onChange=''>
                    <option value=''>- 請選擇 -</option>
                    <option value='楊圓滿'>楊圓滿</option>
                    <option value='陳莊嚴'>陳莊嚴</option>
                </select>
            @else
                @if(!$is_vcamp)
                    將所選學員設定為第
                @endif
                <select required name='attendee_group' onChange=''>
                    <option value=''>- 請選擇 -</option>
                    <option value='1'>1</option>
                    <option value='2'>2</option>
                    <option value='3'>3</option>
                    <option value='4'>4</option>
                    <option value='5'>5</option>
                    <option value='6'>6</option>
                    <option value='7'>7</option>
                    <option value='8'>8</option>
                </select>
                組
            @endif

            @if($is_vcamp)
                <select required name='attendee_work' onChange=''>
                    <option value=''>- 請選擇 -</option>
                    <option value='小組長'>小組長</option>
                    <option value='副小組長'>副小組長</option>
                    <option value='組員'>組員</option>
                </select>
                職務
            @endif
        @endif
        &nbsp;&nbsp;
        <button type="submit" class="btn btn-danger btn-sm" onclick="">儲存</button>
    </span>
</div>
