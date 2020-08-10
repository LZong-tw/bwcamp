{{-- 
    參考頁面：https://youth.blisswisdom.org/camp/winter/form/index_addto.php
    --}}
@extends('layouts.ycamp')
@section('content')
    <script LANGUAGE="javascript">
    function SchooList(cityname) {
        var ctr=1;
        document.Camp.sname.options[0]=new Option("請選擇...","");
    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("政治大學", "國立政治大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("臺灣大學", "國立臺灣大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("臺灣師大", "國立臺灣師範大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("陽明大學", "國立陽明大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("臺灣科大", "國立臺灣科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("臺北科大", "國立臺北科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("臺北藝大", "國立臺北藝術大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("國北教大", "國立臺北教育大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("臺北醫學", "臺北醫學大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("臺北市大", "臺北市立大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("臺北護理", "國立臺北護理健康大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("臺北商大", "國立臺北商業大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("臺灣戲曲", "國立臺灣戲曲學院") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("東吳大學", "東吳大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("文化大學", "中國文化大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("世新大學", "世新大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("銘傳大學", "銘傳大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("實踐大學", "實踐大學臺北校區") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("大同大學", "大同大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("中國科大", "中國科技大學臺北校區") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("德明科大", "德明財經科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("中華科大", "中華科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("城市科大", "臺北城市科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("臺北海洋", "臺北海洋科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("馬偕醫專", "馬偕醫護管理專科學校") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("康寧大學", "康寧大學臺北校區") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("國防醫學", "國防醫學院") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("國防管理", "國防大學管理學院") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("國防政戰", "國防大學政治作戰學院") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("臺灣警專", "臺灣警察專科學校") ;
        ctr = ctr + 1;
    }

    //臺北只有推廣教育中心，先移除
    /*if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("稻江學院", "稻江科技暨管理學院") ;
        ctr = ctr + 1;
    }*/

    if (cityname == '臺北市')
    {   document.Camp.sname.options[ctr] = new Option ("--高中--", "高中校名：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '基隆市')
    {   document.Camp.sname.options[ctr] = new Option ("臺灣海大", "國立臺灣海洋大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '基隆市')
    {   document.Camp.sname.options[ctr] = new Option ("經國學院", "經國管理暨健康學院") ;
        ctr = ctr + 1;
    }

    if (cityname == '基隆市')
    {   document.Camp.sname.options[ctr] = new Option ("崇右科大", "崇右影藝科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '基隆市')
    {   document.Camp.sname.options[ctr] = new Option ("--高中--", "高中校名：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '新北市')
    {   document.Camp.sname.options[ctr] = new Option ("臺北大學", "國立臺北大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '新北市')
    {   document.Camp.sname.options[ctr] = new Option ("臺灣藝大", "國立臺灣藝術大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '新北市')
    {   document.Camp.sname.options[ctr] = new Option ("國立空大", "國立空中大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '新北市')
    {   document.Camp.sname.options[ctr] = new Option ("輔仁大學", "輔仁大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '新北市')
    {   document.Camp.sname.options[ctr] = new Option ("淡江大學", "淡江大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '新北市')
    {   document.Camp.sname.options[ctr] = new Option ("華梵大學", "華梵大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '新北市')
    {   document.Camp.sname.options[ctr] = new Option ("真理大學", "真理大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '新北市')
    {   document.Camp.sname.options[ctr] = new Option ("明志科大", "明志科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '新北市')
    {   document.Camp.sname.options[ctr] = new Option ("聖約翰科大", "聖約翰科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '新北市')
    {   document.Camp.sname.options[ctr] = new Option ("景文科大", "景文科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '新北市')
    {   document.Camp.sname.options[ctr] = new Option ("東南科大", "東南科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '新北市')
    {   document.Camp.sname.options[ctr] = new Option ("致理科大", "致理科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '新北市')
    {   document.Camp.sname.options[ctr] = new Option ("醒吾科大", "醒吾科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '新北市')
    {   document.Camp.sname.options[ctr] = new Option ("亞東學院", "亞東技術學院") ;
        ctr = ctr + 1;
    }

    if (cityname == '新北市')
    {   document.Camp.sname.options[ctr] = new Option ("宏國德霖", "宏國德霖科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '新北市')
    {   document.Camp.sname.options[ctr] = new Option ("黎明學院", "黎明技術學院") ;
        ctr = ctr + 1;
    }

    if (cityname == '新北市')
    {   document.Camp.sname.options[ctr] = new Option ("華夏科大", "華夏科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '新北市')
    {   document.Camp.sname.options[ctr] = new Option ("法鼓學院", "法鼓文理學院") ;
        ctr = ctr + 1;
    }

    if (cityname == '新北市')
    {   document.Camp.sname.options[ctr] = new Option ("馬偕醫學院", "馬偕醫學院") ;
        ctr = ctr + 1;
    }

    if (cityname == '新北市')
    {   document.Camp.sname.options[ctr] = new Option ("耕莘健管", "耕莘健康管理專科學校") ;
        ctr = ctr + 1;
    }

    if (cityname == '新北市')
    {   document.Camp.sname.options[ctr] = new Option ("--高中--", "高中校名：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '宜蘭縣')
    {   document.Camp.sname.options[ctr] = new Option ("宜蘭大學", "國立宜蘭大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '宜蘭縣')
    {   document.Camp.sname.options[ctr] = new Option ("佛光大學", "佛光大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '宜蘭縣')
    {   document.Camp.sname.options[ctr] = new Option ("蘭陽學院", "蘭陽技術學院") ;
        ctr = ctr + 1;
    }

    if (cityname == '宜蘭縣')
    {   document.Camp.sname.options[ctr] = new Option ("聖母醫專", "聖母醫護管理專科學校") ;
        ctr = ctr + 1;
    }

    if (cityname == '宜蘭縣')
    {   document.Camp.sname.options[ctr] = new Option ("--高中--", "高中校名：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '桃園市')
    {   document.Camp.sname.options[ctr] = new Option ("中央大學", "國立中央大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '桃園市')
    {   document.Camp.sname.options[ctr] = new Option ("國立體大", "國立體育大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '桃園市')
    {   document.Camp.sname.options[ctr] = new Option ("臺北商大", "國立臺北商業大學桃園校區") ;
        ctr = ctr + 1;
    }

    if (cityname == '桃園市')
    {   document.Camp.sname.options[ctr] = new Option ("中原大學", "中原大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '桃園市')
    {   document.Camp.sname.options[ctr] = new Option ("長庚大學", "長庚大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '桃園市')
    {   document.Camp.sname.options[ctr] = new Option ("元智大學", "元智大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '桃園市')
    {   document.Camp.sname.options[ctr] = new Option ("銘傳大學", "銘傳大學桃園校區") ;
        ctr = ctr + 1;
    }

    if (cityname == '桃園市')
    {   document.Camp.sname.options[ctr] = new Option ("龍華科大", "龍華科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '桃園市')
    {   document.Camp.sname.options[ctr] = new Option ("健行科大", "健行科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '桃園市')
    {   document.Camp.sname.options[ctr] = new Option ("萬能科大", "萬能科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '桃園市')
    {   document.Camp.sname.options[ctr] = new Option ("開南大學", "開南大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '桃園市')
    {   document.Camp.sname.options[ctr] = new Option ("南亞學院", "南亞技術學院") ;
        ctr = ctr + 1;
    }

    if (cityname == '桃園市')
    {   document.Camp.sname.options[ctr] = new Option ("長庚科大", "長庚科技大學林口校區") ;
        ctr = ctr + 1;
    }

    if (cityname == '桃園市')
    {   document.Camp.sname.options[ctr] = new Option ("新生醫專", "新生醫護管理專科學校") ;
        ctr = ctr + 1;
    }

    if (cityname == '桃園市')
    {   document.Camp.sname.options[ctr] = new Option ("國防大學", "國防大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '桃園市')
    {   document.Camp.sname.options[ctr] = new Option ("國防理工", "國防大學理工學院") ;
        ctr = ctr + 1;
    }

    if (cityname == '桃園市')
    {   document.Camp.sname.options[ctr] = new Option ("警察大學", "中央警察大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '桃園市')
    {   document.Camp.sname.options[ctr] = new Option ("陸軍專校", "陸軍專科學校") ;
        ctr = ctr + 1;
    }

    if (cityname == '桃園市')
    {   document.Camp.sname.options[ctr] = new Option ("--高中--", "高中校名：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '新竹縣')
    {   document.Camp.sname.options[ctr] = new Option ("明新科大", "明新科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '新竹縣')
    {   document.Camp.sname.options[ctr] = new Option ("中國科大", "中國科技大學新竹校區") ;
        ctr = ctr + 1;
    }

    if (cityname == '新竹縣')
    {   document.Camp.sname.options[ctr] = new Option ("中華科大", "中華科技大學新竹分部") ;
        ctr = ctr + 1;
    }

    if (cityname == '新竹縣')
    {   document.Camp.sname.options[ctr] = new Option ("大華科大", "大華科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '新竹縣')
    {   document.Camp.sname.options[ctr] = new Option ("--高中--", "高中校名：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '新竹市')
    {   document.Camp.sname.options[ctr] = new Option ("清華大學", "國立清華大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '新竹市')
    {   document.Camp.sname.options[ctr] = new Option ("清華南大", "國立清華大學南大校區") ;
        ctr = ctr + 1;
    }

    if (cityname == '新竹市')
    {   document.Camp.sname.options[ctr] = new Option ("交通大學", "國立交通大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '新竹市')
    {   document.Camp.sname.options[ctr] = new Option ("中華大學", "中華大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '新竹市')
    {   document.Camp.sname.options[ctr] = new Option ("玄奘大學", "玄奘大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '新竹市')
    {   document.Camp.sname.options[ctr] = new Option ("元培科大", "元培科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '新竹市')
    {   document.Camp.sname.options[ctr] = new Option ("--高中--", "高中校名：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '苗栗縣')
    {   document.Camp.sname.options[ctr] = new Option ("聯合大學", "國立聯合大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '苗栗縣')
    {   document.Camp.sname.options[ctr] = new Option ("育達科大", "育達商業科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '苗栗縣')
    {   document.Camp.sname.options[ctr] = new Option ("亞太創意", "亞太創意技術學院") ;
        ctr = ctr + 1;
    }

    if (cityname == '苗栗縣')
    {   document.Camp.sname.options[ctr] = new Option ("仁德醫專", "仁德醫護管理專科學校") ;
        ctr = ctr + 1;
    }

    if (cityname == '苗栗縣')
    {   document.Camp.sname.options[ctr] = new Option ("--高中--", "高中校名：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '臺中市')
    {   document.Camp.sname.options[ctr] = new Option ("中興大學", "國立中興大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺中市')
    {   document.Camp.sname.options[ctr] = new Option ("臺中教大", "國立臺中教育大學") ;
        ctr = ctr + 1;
    }

    //?
    /*if (cityname == '臺中市')
    {   document.Camp.sname.options[ctr] = new Option ("體育大學", "國立臺灣體育大學(臺中)") ;
        ctr = ctr + 1;
    }*/

    if (cityname == '臺中市')
    {   document.Camp.sname.options[ctr] = new Option ("臺灣體大", "國立臺灣體育運動大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺中市')
    {   document.Camp.sname.options[ctr] = new Option ("臺中科大", "國立臺中科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺中市')
    {   document.Camp.sname.options[ctr] = new Option ("中護健康", "國立臺中科技大學中護健康學院") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺中市')
    {   document.Camp.sname.options[ctr] = new Option ("東海大學", "東海大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺中市')
    {   document.Camp.sname.options[ctr] = new Option ("逢甲大學", "逢甲大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺中市')
    {   document.Camp.sname.options[ctr] = new Option ("中山醫大", "中山醫學大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺中市')
    {   document.Camp.sname.options[ctr] = new Option ("中國醫大", "中國醫藥大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺中市')
    {   document.Camp.sname.options[ctr] = new Option ("嶺東科大", "嶺東科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺中市')
    {   document.Camp.sname.options[ctr] = new Option ("中臺科大", "中臺科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺中市')
    {   document.Camp.sname.options[ctr] = new Option ("僑光科大", "僑光科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺中市')
    {   document.Camp.sname.options[ctr] = new Option ("勤益科大", "國立勤益科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺中市')
    {   document.Camp.sname.options[ctr] = new Option ("靜宜大學", "靜宜大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺中市')
    {   document.Camp.sname.options[ctr] = new Option ("朝陽科大", "朝陽科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺中市')
    {   document.Camp.sname.options[ctr] = new Option ("弘光科大", "弘光科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺中市')
    {   document.Camp.sname.options[ctr] = new Option ("亞洲大學", "亞洲大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺中市')
    {   document.Camp.sname.options[ctr] = new Option ("修平科大", "修平科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺中市')
    {   document.Camp.sname.options[ctr] = new Option ("--高中--", "高中校名：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '彰化縣')
    {   document.Camp.sname.options[ctr] = new Option ("彰化師大", "國立彰化師範大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '彰化縣')
    {   document.Camp.sname.options[ctr] = new Option ("大葉大學", "大葉大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '彰化縣')
    {   document.Camp.sname.options[ctr] = new Option ("建國科大", "建國科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '彰化縣')
    {   document.Camp.sname.options[ctr] = new Option ("明道大學", "明道大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '彰化縣')
    {   document.Camp.sname.options[ctr] = new Option ("中州科大", "中州科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '彰化縣')
    {   document.Camp.sname.options[ctr] = new Option ("--高中--", "高中校名：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '南投縣')
    {   document.Camp.sname.options[ctr] = new Option ("暨南大學", "國立暨南國際大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '南投縣')
    {   document.Camp.sname.options[ctr] = new Option ("南開科大", "南開科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '南投縣')
    {   document.Camp.sname.options[ctr] = new Option ("--高中--", "高中校名：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '雲林縣')
    {   document.Camp.sname.options[ctr] = new Option ("雲林科大", "國立雲林科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '雲林縣')
    {   document.Camp.sname.options[ctr] = new Option ("虎尾科大", "國立虎尾科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '雲林縣')
    {   document.Camp.sname.options[ctr] = new Option ("環球科大", "環球科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '雲林縣')
    {   document.Camp.sname.options[ctr] = new Option ("--高中--", "高中校名：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '嘉義縣')
    {   document.Camp.sname.options[ctr] = new Option ("中正大學", "國立中正大學") ;
        ctr = ctr + 1;
    }

    //嘉義縣：民雄校區
    if (cityname == '嘉義縣')
    {   document.Camp.sname.options[ctr] = new Option ("嘉義大學", "國立嘉義大學") ;
        ctr = ctr + 1;
    }

    //2015遷回臺中
    /*if (cityname == '嘉義縣')
    {   document.Camp.sname.options[ctr] = new Option ("臺灣體院", "國立臺灣體育學院嘉義校區") ;
        ctr = ctr + 1;
    }*/

    if (cityname == '嘉義縣')
    {   document.Camp.sname.options[ctr] = new Option ("南華大學", "南華大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '嘉義縣')
    {   document.Camp.sname.options[ctr] = new Option ("吳鳳學院", "吳鳳技術學院") ;
        ctr = ctr + 1;
    }

    if (cityname == '嘉義縣')
    {   document.Camp.sname.options[ctr] = new Option ("稻江學院", "稻江科技暨管理學院") ;
        ctr = ctr + 1;
    }

    if (cityname == '嘉義縣')
    {   document.Camp.sname.options[ctr] = new Option ("長庚科大", "長庚科技大學嘉義校區") ;
        ctr = ctr + 1;
    }

    if (cityname == '嘉義縣')
    {   document.Camp.sname.options[ctr] = new Option ("--高中--", "高中校名：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    //嘉義市：蘭潭校區和新民校區
    if (cityname == '嘉義市')
    {   document.Camp.sname.options[ctr] = new Option ("嘉義大學", "國立嘉義大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '嘉義市')
    {   document.Camp.sname.options[ctr] = new Option ("大同學院", "大同技術學院") ;
        ctr = ctr + 1;
    }

    if (cityname == '嘉義市')
    {   document.Camp.sname.options[ctr] = new Option ("崇仁醫專", "崇仁醫護管理專科學校") ;
        ctr = ctr + 1;
    }

    if (cityname == '嘉義市')
    {   document.Camp.sname.options[ctr] = new Option ("--高中--", "高中校名：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '臺南市')
    {   document.Camp.sname.options[ctr] = new Option ("成功大學", "國立成功大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺南市')
    {   document.Camp.sname.options[ctr] = new Option ("臺南大學", "國立臺南大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺南市')
    {   document.Camp.sname.options[ctr] = new Option ("臺南護專", "國立臺南護理專科學校") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺南市')
    {   document.Camp.sname.options[ctr] = new Option ("康寧大學", "康寧大學臺南校區") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺南市')
    {   document.Camp.sname.options[ctr] = new Option ("中信金融", "中信金融管理學院") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺南市')
    {   document.Camp.sname.options[ctr] = new Option ("臺南藝大", "國立臺南藝術大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺南市')
    {   document.Camp.sname.options[ctr] = new Option ("真理大學", "真理大學臺南校區") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺南市')
    {   document.Camp.sname.options[ctr] = new Option ("南臺科大", "南臺科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺南市')
    {   document.Camp.sname.options[ctr] = new Option ("崑山科大", "崑山科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺南市')
    {   document.Camp.sname.options[ctr] = new Option ("嘉南藥理", "嘉南藥理大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺南市')
    {   document.Camp.sname.options[ctr] = new Option ("長榮大學", "長榮大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺南市')
    {   document.Camp.sname.options[ctr] = new Option ("臺南科大", "臺南應用科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺南市')
    {   document.Camp.sname.options[ctr] = new Option ("遠東科大", "遠東科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺南市')
    {   document.Camp.sname.options[ctr] = new Option ("中華醫大", "中華醫事科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺南市')
    {   document.Camp.sname.options[ctr] = new Option ("臺灣首大", "臺灣首府大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺南市')
    {   document.Camp.sname.options[ctr] = new Option ("南榮科大", "南榮科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺南市')
    {   document.Camp.sname.options[ctr] = new Option ("敏惠醫專", "敏惠醫護管理專科學校") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺南市')
    {   document.Camp.sname.options[ctr] = new Option ("交通大學", "國立交通大學臺南校區") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺南市')
    {   document.Camp.sname.options[ctr] = new Option ("--高中--", "高中校名：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }


    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("中山大學", "國立中山大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("高雄師大", "國立高雄師範大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("高雄大學", "國立高雄大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("第一科大", "國立高雄科技大學(原第一科大)") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("海洋科大", "國立高雄科技大學(原海洋科大)") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("應用科大", "國立高雄科技大學(原應用科大)") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("高雄餐旅", "國立高雄餐旅大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("高雄醫學", "高雄醫學大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("文藻外語", "文藻外語大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("育英醫專", "育英醫護管理專科學校") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("高雄空大", "高雄市立空中大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("海軍官校", "海軍軍官學校") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("義守大學", "義守大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("實踐大學", "實踐大學高雄校區") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("樹德科大", "樹德科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("輔英科大", "輔英科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("正修科大", "正修科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("高苑科大", "高苑科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("和春學院", "和春技術學院") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("東方設大", "東方設計大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("樹人醫專", "樹人醫護管理專科學校") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("高美醫專", "高美醫護管理專科學校") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("陸軍官校", "陸軍軍官學校") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("空軍官校", "空軍軍官學校") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("航技學院", "空軍航空技術學院") ;
        ctr = ctr + 1;
    }

    if (cityname == '高雄市')
    {   document.Camp.sname.options[ctr] = new Option ("--高中--", "高中校名：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '屏東縣')
    {   document.Camp.sname.options[ctr] = new Option ("屏東科大", "國立屏東科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '屏東縣')
    {   document.Camp.sname.options[ctr] = new Option ("屏東大學", "國立屏東大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '屏東縣')
    {   document.Camp.sname.options[ctr] = new Option ("大仁科大", "大仁科技大學") ;
        ctr = ctr + 1;
    }

    //2014年停辦
    /*if (cityname == '屏東縣')
    {   document.Camp.sname.options[ctr] = new Option ("永達學院", "永達技術學院") ;
        ctr = ctr + 1;
    }*/

    if (cityname == '屏東縣')
    {   document.Camp.sname.options[ctr] = new Option ("美和科大", "美和科技大學") ;
        ctr = ctr + 1;
    }

    //2014停辦
    /*if (cityname == '屏東縣')
    {   document.Camp.sname.options[ctr] = new Option ("高鳳數位", "高鳳數位內容學院") ;
        ctr = ctr + 1;
    }*/

    if (cityname == '屏東縣')
    {   document.Camp.sname.options[ctr] = new Option ("慈惠醫專", "慈惠醫護管理專科學校") ;
        ctr = ctr + 1;
    }

    if (cityname == '屏東縣')
    {   document.Camp.sname.options[ctr] = new Option ("--高中--", "高中校名：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '花蓮縣')
    {   document.Camp.sname.options[ctr] = new Option ("東華大學", "國立東華大學") ;
        ctr = ctr + 1;
    }

    //2008年併入東華大學
    /*if (cityname == '花蓮縣')
    {   document.Camp.sname.options[ctr] = new Option ("花蓮教大", "國立花蓮教育大學") ;
        ctr = ctr + 1;
    }*/

    if (cityname == '花蓮縣')
    {   document.Camp.sname.options[ctr] = new Option ("慈濟大學", "慈濟大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '花蓮縣')
    {   document.Camp.sname.options[ctr] = new Option ("大漢學院", "大漢技術學院") ;
        ctr = ctr + 1;
    }

    if (cityname == '花蓮縣')
    {   document.Camp.sname.options[ctr] = new Option ("慈濟科大", "慈濟科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '花蓮縣')
    {   document.Camp.sname.options[ctr] = new Option ("臺灣觀光", "臺灣觀光學院") ;
        ctr = ctr + 1;
    }

    if (cityname == '花蓮縣')
    {   document.Camp.sname.options[ctr] = new Option ("--高中--", "高中校名：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '臺東縣')
    {   document.Camp.sname.options[ctr] = new Option ("臺東大學", "國立臺東大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺東縣')
    {   document.Camp.sname.options[ctr] = new Option ("臺東專校", "國立臺東專科學校") ;
        ctr = ctr + 1;
    }

    if (cityname == '臺東縣')
    {   document.Camp.sname.options[ctr] = new Option ("--高中--", "高中校名：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '澎湖縣')
    {   document.Camp.sname.options[ctr] = new Option ("澎湖科大", "國立澎湖科技大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '澎湖縣')
    {   document.Camp.sname.options[ctr] = new Option ("--高中--", "高中校名：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '金門縣')
    {   document.Camp.sname.options[ctr] = new Option ("金門大學", "國立金門大學") ;
        ctr = ctr + 1;
    }

    if (cityname == '金門縣')
    {   document.Camp.sname.options[ctr] = new Option ("銘傳大學", "銘傳大學金門校區") ;
        ctr = ctr + 1;
    }

    if (cityname == '金門縣')
    {   document.Camp.sname.options[ctr] = new Option ("--高中--", "高中校名：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '海外')
    {   document.Camp.sname.options[ctr] = new Option ("美國", "美國：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '海外')
    {   document.Camp.sname.options[ctr] = new Option ("大陸", "大陸：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '海外')
    {   document.Camp.sname.options[ctr] = new Option ("香港", "香港：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '海外')
    {   document.Camp.sname.options[ctr] = new Option ("日本", "日本：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '海外')
    {   document.Camp.sname.options[ctr] = new Option ("新加坡", "新加坡：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '海外')
    {   document.Camp.sname.options[ctr] = new Option ("澳洲", "澳洲：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '海外')
    {   document.Camp.sname.options[ctr] = new Option ("其他", "海外：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

    if (cityname == '其他')
    {   document.Camp.sname.options[ctr] = new Option ("其他學校", "校名：") ;
        document.Camp.school.readOnly = false ;
        ctr = ctr + 1;
    }

        document.Camp.sname.length= ctr ;
    } 


    </script>

    <script LANGUAGE='javascript'>

    function Address(num) {
        var ctr=1;
        document.Camp.subarea.selectedIndex=0;
        document.Camp.zipcode.value='';  
        document.Camp.address.value='';  
        document.Camp.subarea.options[0]=new Option('再選區鄉鎮..','');

    /*
        document.Camp.Camp.value='';  
    定義二階選單內容
    if (num=='第一階下拉選單的值') {	document.Camp.subarea.options[ctr]=new Option('第二階下拉選單的顯示名稱','第二階下拉選單的值');	ctr=ctr+1;	}
    */	

    /*臺北市*/  

        if (num=='臺北市') { document.Camp.subarea.options[0]=new Option('- 請選區域 -',''); }
        if (num=='臺北市') { document.Camp.subarea.options[ctr]=new Option('中正區','100');	ctr=ctr+1;	}
        if (num=='臺北市') { document.Camp.subarea.options[ctr]=new Option('大同區','103');	ctr=ctr+1;	}
        if (num=='臺北市') { document.Camp.subarea.options[ctr]=new Option('中山區','104');	ctr=ctr+1;	}
        if (num=='臺北市') { document.Camp.subarea.options[ctr]=new Option('松山區','105');	ctr=ctr+1;	}
        if (num=='臺北市') { document.Camp.subarea.options[ctr]=new Option('大安區','106');	ctr=ctr+1;	}
        if (num=='臺北市') { document.Camp.subarea.options[ctr]=new Option('萬華區','108');	ctr=ctr+1;	}
        if (num=='臺北市') { document.Camp.subarea.options[ctr]=new Option('信義區','110');	ctr=ctr+1;	}
        if (num=='臺北市') { document.Camp.subarea.options[ctr]=new Option('士林區','111');	ctr=ctr+1;	}
        if (num=='臺北市') { document.Camp.subarea.options[ctr]=new Option('北投區','112');	ctr=ctr+1;	}
        if (num=='臺北市') { document.Camp.subarea.options[ctr]=new Option('內湖區','114');	ctr=ctr+1;	}
        if (num=='臺北市') { document.Camp.subarea.options[ctr]=new Option('南港區','115');	ctr=ctr+1;	}
        if (num=='臺北市') { document.Camp.subarea.options[ctr]=new Option('文山區','116');	ctr=ctr+1;	}

    /*新北市*/  

        if (num=='新北市') { document.Camp.subarea.options[0]=new Option('- 請選區域 -',''); }
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('萬里區','207');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('金山區','208');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('板橋區','220');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('汐止區','221');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('深坑區','222');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('石碇區','223');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('瑞芳區','224');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('平溪區','226');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('雙溪區','227');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('貢寮區','228');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('新店區','231');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('坪林區','232');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('烏來區','233');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('永和區','234');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('中和區','235');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('土城區','236');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('三峽區','237');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('樹林區','238');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('鶯歌區','239');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('三重區','241');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('新莊區','242');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('泰山區','243');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('林口區','244');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('蘆洲區','247');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('五股區','248');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('八里區','249');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('淡水區','251');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('三芝區','252');	ctr=ctr+1;	} 
        if (num=='新北市') { document.Camp.subarea.options[ctr]=new Option('石門區','253');	ctr=ctr+1;	} 

    /*基隆市*/  

        if (num=='基隆市') { document.Camp.subarea.options[0]=new Option('- 請選區域 -',''); }
        if (num=='基隆市') { document.Camp.subarea.options[ctr]=new Option('仁愛區','200');	ctr=ctr+1;	}
        if (num=='基隆市') { document.Camp.subarea.options[ctr]=new Option('信義區','201');	ctr=ctr+1;	}
        if (num=='基隆市') { document.Camp.subarea.options[ctr]=new Option('中正區','202');	ctr=ctr+1;	}
        if (num=='基隆市') { document.Camp.subarea.options[ctr]=new Option('中山區','203');	ctr=ctr+1;	}
        if (num=='基隆市') { document.Camp.subarea.options[ctr]=new Option('安樂區','204');	ctr=ctr+1;	}
        if (num=='基隆市') { document.Camp.subarea.options[ctr]=new Option('暖暖區','205');	ctr=ctr+1;	}
        if (num=='基隆市') { document.Camp.subarea.options[ctr]=new Option('七堵區','206');	ctr=ctr+1;	}

    /*宜蘭縣*/

        if (num=='宜蘭縣') { document.Camp.subarea.options[0]=new Option('- 請選鄉鎮 -',''); }
        if (num=='宜蘭縣') { document.Camp.subarea.options[ctr]=new Option('宜蘭市','260');	ctr=ctr+1;	} 
        if (num=='宜蘭縣') { document.Camp.subarea.options[ctr]=new Option('頭城鎮','261');	ctr=ctr+1;	} 
        if (num=='宜蘭縣') { document.Camp.subarea.options[ctr]=new Option('礁溪鄉','262');	ctr=ctr+1;	} 
        if (num=='宜蘭縣') { document.Camp.subarea.options[ctr]=new Option('壯圍鄉','263');	ctr=ctr+1;	} 
        if (num=='宜蘭縣') { document.Camp.subarea.options[ctr]=new Option('員山鄉','264');	ctr=ctr+1;	} 
        if (num=='宜蘭縣') { document.Camp.subarea.options[ctr]=new Option('羅東鎮','265');	ctr=ctr+1;	} 
        if (num=='宜蘭縣') { document.Camp.subarea.options[ctr]=new Option('三星鄉','266');	ctr=ctr+1;	} 
        if (num=='宜蘭縣') { document.Camp.subarea.options[ctr]=new Option('大同鄉','267');	ctr=ctr+1;	} 
        if (num=='宜蘭縣') { document.Camp.subarea.options[ctr]=new Option('五結鄉','268');	ctr=ctr+1;	} 
        if (num=='宜蘭縣') { document.Camp.subarea.options[ctr]=new Option('冬山鄉','269');	ctr=ctr+1;	} 
        if (num=='宜蘭縣') { document.Camp.subarea.options[ctr]=new Option('蘇澳鎮','270');	ctr=ctr+1;	} 
        if (num=='宜蘭縣') { document.Camp.subarea.options[ctr]=new Option('南澳鄉','272');	ctr=ctr+1;	} 

    /*釣魚台列嶼*/
        if (num=='宜蘭縣') { document.Camp.subarea.options[ctr]=new Option('釣魚台列嶼','290');	ctr=ctr+1;	}

    /*桃園市*/

        if (num=='桃園市') { document.Camp.subarea.options[0]=new Option('- 請選鄉鎮 -',''); }
        if (num=='桃園市') { document.Camp.subarea.options[ctr]=new Option('中壢區','320');	ctr=ctr+1;	} 
        if (num=='桃園市') { document.Camp.subarea.options[ctr]=new Option('平鎮區','324');	ctr=ctr+1;	} 
        if (num=='桃園市') { document.Camp.subarea.options[ctr]=new Option('龍潭區','325');	ctr=ctr+1;	} 
        if (num=='桃園市') { document.Camp.subarea.options[ctr]=new Option('楊梅區','326');	ctr=ctr+1;	} 
        if (num=='桃園市') { document.Camp.subarea.options[ctr]=new Option('新屋區','327');	ctr=ctr+1;	} 
        if (num=='桃園市') { document.Camp.subarea.options[ctr]=new Option('觀音區','328');	ctr=ctr+1;	} 
        if (num=='桃園市') { document.Camp.subarea.options[ctr]=new Option('桃園區','330');	ctr=ctr+1;	} 
        if (num=='桃園市') { document.Camp.subarea.options[ctr]=new Option('龜山區','333');	ctr=ctr+1;	} 
        if (num=='桃園市') { document.Camp.subarea.options[ctr]=new Option('八德區','334');	ctr=ctr+1;	} 
        if (num=='桃園市') { document.Camp.subarea.options[ctr]=new Option('大溪區','335');	ctr=ctr+1;	} 
        if (num=='桃園市') { document.Camp.subarea.options[ctr]=new Option('復興區','336');	ctr=ctr+1;	} 
        if (num=='桃園市') { document.Camp.subarea.options[ctr]=new Option('大園區','337');	ctr=ctr+1;	} 
        if (num=='桃園市') { document.Camp.subarea.options[ctr]=new Option('蘆竹區','338');	ctr=ctr+1;	} 

    /*新竹縣市*/

        if (num=='新竹市') { document.Camp.subarea.options[ctr]=new Option('新竹市','300');	ctr=ctr+1;	} 

        if (num=='新竹縣') { document.Camp.subarea.options[0]=new Option('- 請選鄉鎮 -',''); }
        if (num=='新竹縣') { document.Camp.subarea.options[ctr]=new Option('竹北市','302');	ctr=ctr+1;	} 
        if (num=='新竹縣') { document.Camp.subarea.options[ctr]=new Option('湖口鄉','303');	ctr=ctr+1;	} 
        if (num=='新竹縣') { document.Camp.subarea.options[ctr]=new Option('新豐鄉','304');	ctr=ctr+1;	} 
        if (num=='新竹縣') { document.Camp.subarea.options[ctr]=new Option('新埔鎮','305');	ctr=ctr+1;	} 
        if (num=='新竹縣') { document.Camp.subarea.options[ctr]=new Option('關西鎮','306');	ctr=ctr+1;	} 
        if (num=='新竹縣') { document.Camp.subarea.options[ctr]=new Option('芎林鄉','307');	ctr=ctr+1;	} 
        if (num=='新竹縣') { document.Camp.subarea.options[ctr]=new Option('寶山鄉','308');	ctr=ctr+1;	} 
        if (num=='新竹縣') { document.Camp.subarea.options[ctr]=new Option('竹東鎮','310');	ctr=ctr+1;	} 
        if (num=='新竹縣') { document.Camp.subarea.options[ctr]=new Option('五峰鄉','311');	ctr=ctr+1;	} 
        if (num=='新竹縣') { document.Camp.subarea.options[ctr]=new Option('橫山鄉','312');	ctr=ctr+1;	} 
        if (num=='新竹縣') { document.Camp.subarea.options[ctr]=new Option('尖石鄉','313');	ctr=ctr+1;	} 
        if (num=='新竹縣') { document.Camp.subarea.options[ctr]=new Option('北埔鄉','314');	ctr=ctr+1;	} 
        if (num=='新竹縣') { document.Camp.subarea.options[ctr]=new Option('峨眉鄉','315');	ctr=ctr+1;	} 

    /*苗栗縣*/

        if (num=='苗栗縣') { document.Camp.subarea.options[0]=new Option('- 請選鄉鎮 -',''); }
        if (num=='苗栗縣') { document.Camp.subarea.options[ctr]=new Option('竹南鎮','350');	ctr=ctr+1;	} 
        if (num=='苗栗縣') { document.Camp.subarea.options[ctr]=new Option('頭份鎮','351');	ctr=ctr+1;	} 
        if (num=='苗栗縣') { document.Camp.subarea.options[ctr]=new Option('三灣鄉','352');	ctr=ctr+1;	} 
        if (num=='苗栗縣') { document.Camp.subarea.options[ctr]=new Option('南庄鄉','353');	ctr=ctr+1;	} 
        if (num=='苗栗縣') { document.Camp.subarea.options[ctr]=new Option('獅潭鄉','354');	ctr=ctr+1;	} 
        if (num=='苗栗縣') { document.Camp.subarea.options[ctr]=new Option('後龍鎮','356');	ctr=ctr+1;	} 
        if (num=='苗栗縣') { document.Camp.subarea.options[ctr]=new Option('通霄鎮','357');	ctr=ctr+1;	} 
        if (num=='苗栗縣') { document.Camp.subarea.options[ctr]=new Option('苑裡鎮','358');	ctr=ctr+1;	} 
        if (num=='苗栗縣') { document.Camp.subarea.options[ctr]=new Option('苗栗市','360');	ctr=ctr+1;	} 
        if (num=='苗栗縣') { document.Camp.subarea.options[ctr]=new Option('造橋鄉','361');	ctr=ctr+1;	} 
        if (num=='苗栗縣') { document.Camp.subarea.options[ctr]=new Option('頭屋鄉','362');	ctr=ctr+1;	} 
        if (num=='苗栗縣') { document.Camp.subarea.options[ctr]=new Option('公館鄉','363');	ctr=ctr+1;	} 
        if (num=='苗栗縣') { document.Camp.subarea.options[ctr]=new Option('大湖鄉','364');	ctr=ctr+1;	} 
        if (num=='苗栗縣') { document.Camp.subarea.options[ctr]=new Option('泰安鄉','365');	ctr=ctr+1;	} 
        if (num=='苗栗縣') { document.Camp.subarea.options[ctr]=new Option('銅鑼鄉','366');	ctr=ctr+1;	} 
        if (num=='苗栗縣') { document.Camp.subarea.options[ctr]=new Option('三義鄉','367');	ctr=ctr+1;	} 
        if (num=='苗栗縣') { document.Camp.subarea.options[ctr]=new Option('西湖鄉','368');	ctr=ctr+1;	} 
        if (num=='苗栗縣') { document.Camp.subarea.options[ctr]=new Option('卓蘭鎮','369');	ctr=ctr+1;	}

    /*臺中市*/ 

        if (num=='臺中市') { document.Camp.subarea.options[0]=new Option('- 請選區域 -',''); }
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('中　區','400');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('東　區','401');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('南　區','402');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('西　區','403');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('北　區','404');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('北屯區','406');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('西屯區','407');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('南屯區','408');	ctr=ctr+1;	} 

    /*原臺中縣*/

        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('太平區','411');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('大里區','412');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('霧峰區','413');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('烏日區','414');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('豐原區','420');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('后里區','421');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('石岡區','422');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('東勢區','423');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('和平區','424');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('新社區','426');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('潭子區','427');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('大雅區','428');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('神岡區','429');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('大肚區','432');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('沙鹿區','433');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('龍井區','434');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('梧棲區','435');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('清水區','436');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('大甲區','437');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('外埔區','438');	ctr=ctr+1;	} 
        if (num=='臺中市') { document.Camp.subarea.options[ctr]=new Option('大安區','439');	ctr=ctr+1;	}

    /*彰化縣*/ 

        if (num=='彰化縣') { document.Camp.subarea.options[0]=new Option('- 請選鄉鎮 -',''); }
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('彰化市','500');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('芬園鄉','502');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('花壇鄉','503');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('秀水鄉','504');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('鹿港鎮','505');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('福興鄉','506');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('線西鄉','507');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('和美鎮','508');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('伸港鄉','509');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('員林鎮','510');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('社頭鄉','511');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('永靖鄉','512');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('埔心鄉','513');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('溪湖鎮','514');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('大村鄉','515');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('埔鹽鄉','516');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('田中鎮','520');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('北斗鎮','521');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('田尾鄉','522');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('埤頭鄉','523');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('溪州鄉','524');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('竹塘鄉','525');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('二林鎮','526');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('大城鄉','527');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('芳苑鄉','528');	ctr=ctr+1;	} 
        if (num=='彰化縣') { document.Camp.subarea.options[ctr]=new Option('二水鄉','530');	ctr=ctr+1;	} 

    /*南投縣*/

        if (num=='南投縣') { document.Camp.subarea.options[0]=new Option('- 請選鄉鎮 -',''); }
        if (num=='南投縣') { document.Camp.subarea.options[ctr]=new Option('南投市','540');	ctr=ctr+1;	} 
        if (num=='南投縣') { document.Camp.subarea.options[ctr]=new Option('中寮鄉','541');	ctr=ctr+1;	} 
        if (num=='南投縣') { document.Camp.subarea.options[ctr]=new Option('草屯鎮','542');	ctr=ctr+1;	} 
        if (num=='南投縣') { document.Camp.subarea.options[ctr]=new Option('國姓鄉','544');	ctr=ctr+1;	} 
        if (num=='南投縣') { document.Camp.subarea.options[ctr]=new Option('埔里鎮','545');	ctr=ctr+1;	} 
        if (num=='南投縣') { document.Camp.subarea.options[ctr]=new Option('仁愛鄉','546');	ctr=ctr+1;	} 
        if (num=='南投縣') { document.Camp.subarea.options[ctr]=new Option('名間鄉','551');	ctr=ctr+1;	} 
        if (num=='南投縣') { document.Camp.subarea.options[ctr]=new Option('集集鎮','552');	ctr=ctr+1;	} 
        if (num=='南投縣') { document.Camp.subarea.options[ctr]=new Option('水里鄉','553');	ctr=ctr+1;	} 
        if (num=='南投縣') { document.Camp.subarea.options[ctr]=new Option('魚池鄉','555');	ctr=ctr+1;	} 
        if (num=='南投縣') { document.Camp.subarea.options[ctr]=new Option('信義鄉','556');	ctr=ctr+1;	} 
        if (num=='南投縣') { document.Camp.subarea.options[ctr]=new Option('竹山鎮','557');	ctr=ctr+1;	} 
        if (num=='南投縣') { document.Camp.subarea.options[ctr]=new Option('鹿谷鄉','558');	ctr=ctr+1;	} 

    /*雲林縣*/

        if (num=='雲林縣') { document.Camp.subarea.options[0]=new Option('- 請選鄉鎮 -',''); }
        if (num=='雲林縣') { document.Camp.subarea.options[ctr]=new Option('斗南鎮','630');	ctr=ctr+1;	} 
        if (num=='雲林縣') { document.Camp.subarea.options[ctr]=new Option('大埤鄉','631');	ctr=ctr+1;	} 
        if (num=='雲林縣') { document.Camp.subarea.options[ctr]=new Option('虎尾鎮','632');	ctr=ctr+1;	} 
        if (num=='雲林縣') { document.Camp.subarea.options[ctr]=new Option('土庫鎮','633');	ctr=ctr+1;	} 
        if (num=='雲林縣') { document.Camp.subarea.options[ctr]=new Option('褒忠鄉','634');	ctr=ctr+1;	} 
        if (num=='雲林縣') { document.Camp.subarea.options[ctr]=new Option('東勢鄉','635');	ctr=ctr+1;	} 
        if (num=='雲林縣') { document.Camp.subarea.options[ctr]=new Option('台西鄉','636');	ctr=ctr+1;	} 
        if (num=='雲林縣') { document.Camp.subarea.options[ctr]=new Option('崙背鄉','637');	ctr=ctr+1;	} 
        if (num=='雲林縣') { document.Camp.subarea.options[ctr]=new Option('麥寮鄉','638');	ctr=ctr+1;	} 
        if (num=='雲林縣') { document.Camp.subarea.options[ctr]=new Option('斗六市','640');	ctr=ctr+1;	} 
        if (num=='雲林縣') { document.Camp.subarea.options[ctr]=new Option('林內鄉','643');	ctr=ctr+1;	} 
        if (num=='雲林縣') { document.Camp.subarea.options[ctr]=new Option('古坑鄉','646');	ctr=ctr+1;	} 
        if (num=='雲林縣') { document.Camp.subarea.options[ctr]=new Option('莿桐鄉','647');	ctr=ctr+1;	} 
        if (num=='雲林縣') { document.Camp.subarea.options[ctr]=new Option('西螺鎮','648');	ctr=ctr+1;	} 
        if (num=='雲林縣') { document.Camp.subarea.options[ctr]=new Option('二崙鄉','649');	ctr=ctr+1;	} 
        if (num=='雲林縣') { document.Camp.subarea.options[ctr]=new Option('北港鎮','651');	ctr=ctr+1;	} 
        if (num=='雲林縣') { document.Camp.subarea.options[ctr]=new Option('水林鄉','652');	ctr=ctr+1;	} 
        if (num=='雲林縣') { document.Camp.subarea.options[ctr]=new Option('口湖鄉','653');	ctr=ctr+1;	} 
        if (num=='雲林縣') { document.Camp.subarea.options[ctr]=new Option('四湖鄉','654');	ctr=ctr+1;	} 
        if (num=='雲林縣') { document.Camp.subarea.options[ctr]=new Option('元長鄉','655');	ctr=ctr+1;	} 

    /*嘉義縣市*/

        if (num=='嘉義市') { document.Camp.subarea.options[ctr]=new Option('嘉義市','600');	ctr=ctr+1;	} 

        if (num=='嘉義縣') { document.Camp.subarea.options[ctr]=new Option('番路鄉','602');	ctr=ctr+1;	} 
        if (num=='嘉義縣') { document.Camp.subarea.options[ctr]=new Option('梅山鄉','603');	ctr=ctr+1;	} 
        if (num=='嘉義縣') { document.Camp.subarea.options[ctr]=new Option('竹崎鄉','604');	ctr=ctr+1;	} 
        if (num=='嘉義縣') { document.Camp.subarea.options[ctr]=new Option('阿里山','605');	ctr=ctr+1;	} 
        if (num=='嘉義縣') { document.Camp.subarea.options[ctr]=new Option('中埔鄉','606');	ctr=ctr+1;	} 
        if (num=='嘉義縣') { document.Camp.subarea.options[ctr]=new Option('大埔鄉','607');	ctr=ctr+1;	} 
        if (num=='嘉義縣') { document.Camp.subarea.options[ctr]=new Option('水上鄉','608');	ctr=ctr+1;	} 
        if (num=='嘉義縣') { document.Camp.subarea.options[ctr]=new Option('鹿草鄉','611');	ctr=ctr+1;	} 
        if (num=='嘉義縣') { document.Camp.subarea.options[ctr]=new Option('太保市','612');	ctr=ctr+1;	} 
        if (num=='嘉義縣') { document.Camp.subarea.options[ctr]=new Option('朴子市','613');	ctr=ctr+1;	} 
        if (num=='嘉義縣') { document.Camp.subarea.options[ctr]=new Option('東石鄉','614');	ctr=ctr+1;	} 
        if (num=='嘉義縣') { document.Camp.subarea.options[ctr]=new Option('六腳鄉','615');	ctr=ctr+1;	} 
        if (num=='嘉義縣') { document.Camp.subarea.options[ctr]=new Option('新港鄉','616');	ctr=ctr+1;	} 
        if (num=='嘉義縣') { document.Camp.subarea.options[ctr]=new Option('民雄鄉','621');	ctr=ctr+1;	} 
        if (num=='嘉義縣') { document.Camp.subarea.options[ctr]=new Option('大林鎮','622');	ctr=ctr+1;	} 
        if (num=='嘉義縣') { document.Camp.subarea.options[ctr]=new Option('溪口鄉','623');	ctr=ctr+1;	} 
        if (num=='嘉義縣') { document.Camp.subarea.options[ctr]=new Option('義竹鄉','624');	ctr=ctr+1;	} 
        if (num=='嘉義縣') { document.Camp.subarea.options[ctr]=new Option('布袋鎮','625');	ctr=ctr+1;	} 

    /*臺南市*/

        if (num=='臺南市') { document.Camp.subarea.options[0]=new Option('- 請選區域 -',''); }
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('中西區','700');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('東　區','701');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('南　區','702');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('北　區','704');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('安平區','708');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('安南區','709');	ctr=ctr+1;	} 

    /*原臺南縣*/

        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('永康區','710');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('歸仁區','711');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('新化區','712');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('左鎮區','713');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('玉井區','714');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('楠西區','715');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('南化區','716');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('仁德區','717');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('關廟區','718');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('龍崎區','719');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('官田區','720');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('麻豆區','721');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('佳里區','722');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('西港區','723');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('七股區','724');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('將軍區','725');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('學甲區','726');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('北門區','727');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('新營區','730');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('後壁區','731');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('白河區','732');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('東山區','733');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('六甲區','734');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('下營區','735');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('柳營區','736');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('鹽水區','737');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('善化區','741');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('新市區','741');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('大內區','742');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('山上區','743');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('新市區','744');	ctr=ctr+1;	} 
        if (num=='臺南市') { document.Camp.subarea.options[ctr]=new Option('安定區','745');	ctr=ctr+1;	}

    /*高雄市*/

        if (num=='高雄市') { document.Camp.subarea.options[0]=new Option('- 請選區域 -',''); }
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('新興區','800');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('前金區','801');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('苓雅區','802');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('鹽埕區','803');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('鼓山區','804');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('旗津區','805');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('前鎮區','806');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('三民區','807');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('楠梓區','811');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('小港區','812');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('左營區','813');	ctr=ctr+1;	} 	

    /*原高雄縣*/

        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('仁武區','814');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('大社區','815');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('岡山區','820');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('路竹區','821');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('阿蓮區','822');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('田寮區','823');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('燕巢區','824');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('橋頭區','825');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('梓官區','826');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('彌陀區','827');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('永安區','828');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('湖內區','829');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('鳳山區','830');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('大寮區','831');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('林園區','832');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('鳥松區','833');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('大樹區','840');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('旗山區','842');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('美濃區','843');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('六龜區','844');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('內門區','845');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('杉林區','846');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('甲仙區','847');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('桃源區','848');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('三民區','849');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('茂林區','851');	ctr=ctr+1;	} 
        if (num=='高雄市') { document.Camp.subarea.options[ctr]=new Option('茄萣區','852');	ctr=ctr+1;	} 

    /*澎湖縣*/

        if (num=='澎湖縣') { document.Camp.subarea.options[0]=new Option('- 請選鄉鎮 -',''); }
        if (num=='澎湖縣') { document.Camp.subarea.options[ctr]=new Option('馬公市','880');	ctr=ctr+1;	} 
        if (num=='澎湖縣') { document.Camp.subarea.options[ctr]=new Option('西嶼鄉','881');	ctr=ctr+1;	} 
        if (num=='澎湖縣') { document.Camp.subarea.options[ctr]=new Option('望安鄉','882');	ctr=ctr+1;	} 
        if (num=='澎湖縣') { document.Camp.subarea.options[ctr]=new Option('七美鄉','883');	ctr=ctr+1;	} 
        if (num=='澎湖縣') { document.Camp.subarea.options[ctr]=new Option('白沙鄉','884');	ctr=ctr+1;	} 
        if (num=='澎湖縣') { document.Camp.subarea.options[ctr]=new Option('湖西鄉','885');	ctr=ctr+1;	} 

    /*屏東縣*/

        if (num=='屏東縣') { document.Camp.subarea.options[0]=new Option('- 請選鄉鎮 -',''); }
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('屏東市','900');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('三地門','901');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('霧台鄉','902');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('瑪家鄉','903');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('九如鄉','904');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('里港鄉','905');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('高樹鄉','906');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('鹽埔鄉','907');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('長治鄉','908');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('麟洛鄉','909');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('竹田鄉','911');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('內埔鄉','912');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('萬丹鄉','913');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('潮州鎮','920');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('泰武鄉','921');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('來義鄉','922');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('萬巒鄉','923');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('崁頂鄉','924');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('新埤鄉','925');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('南州鄉','926');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('林邊鄉','927');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('東港鎮','928');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('琉球鄉','929');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('佳冬鄉','931');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('新園鄉','932');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('枋寮鄉','940');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('枋山鄉','941');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('春日鄉','942');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('獅子鄉','943');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('車城鄉','944');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('牡丹鄉','945');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('恆春鎮','946');	ctr=ctr+1;	} 
        if (num=='屏東縣') { document.Camp.subarea.options[ctr]=new Option('滿州鄉','947');	ctr=ctr+1;	} 

    /*臺東縣*/

        if (num=='臺東縣') { document.Camp.subarea.options[0]=new Option('- 請選鄉鎮 -',''); }
        if (num=='臺東縣') { document.Camp.subarea.options[ctr]=new Option('台東市','950');	ctr=ctr+1;	} 
        if (num=='臺東縣') { document.Camp.subarea.options[ctr]=new Option('綠島鄉','951');	ctr=ctr+1;	} 
        if (num=='臺東縣') { document.Camp.subarea.options[ctr]=new Option('蘭嶼鄉','952');	ctr=ctr+1;	} 
        if (num=='臺東縣') { document.Camp.subarea.options[ctr]=new Option('延平鄉','953');	ctr=ctr+1;	} 
        if (num=='臺東縣') { document.Camp.subarea.options[ctr]=new Option('卑南鄉','954');	ctr=ctr+1;	} 
        if (num=='臺東縣') { document.Camp.subarea.options[ctr]=new Option('鹿野鄉','955');	ctr=ctr+1;	} 
        if (num=='臺東縣') { document.Camp.subarea.options[ctr]=new Option('關山鎮','956');	ctr=ctr+1;	} 
        if (num=='臺東縣') { document.Camp.subarea.options[ctr]=new Option('海端鄉','957');	ctr=ctr+1;	} 
        if (num=='臺東縣') { document.Camp.subarea.options[ctr]=new Option('池上鄉','958');	ctr=ctr+1;	} 
        if (num=='臺東縣') { document.Camp.subarea.options[ctr]=new Option('東河鄉','959');	ctr=ctr+1;	} 
        if (num=='臺東縣') { document.Camp.subarea.options[ctr]=new Option('成功鎮','961');	ctr=ctr+1;	} 
        if (num=='臺東縣') { document.Camp.subarea.options[ctr]=new Option('長濱鄉','962');	ctr=ctr+1;	} 
        if (num=='臺東縣') { document.Camp.subarea.options[ctr]=new Option('太麻里','963');	ctr=ctr+1;	} 
        if (num=='臺東縣') { document.Camp.subarea.options[ctr]=new Option('金峰鄉','964');	ctr=ctr+1;	} 
        if (num=='臺東縣') { document.Camp.subarea.options[ctr]=new Option('大武鄉','965');	ctr=ctr+1;	} 
        if (num=='臺東縣') { document.Camp.subarea.options[ctr]=new Option('達仁鄉','966');	ctr=ctr+1;	} 

    /*花蓮縣*/

        if (num=='花蓮縣') { document.Camp.subarea.options[0]=new Option('- 請選鄉鎮 -',''); }
        if (num=='花蓮縣') { document.Camp.subarea.options[ctr]=new Option('花蓮市','970');	ctr=ctr+1;	} 
        if (num=='花蓮縣') { document.Camp.subarea.options[ctr]=new Option('新城鄉','971');	ctr=ctr+1;	} 
        if (num=='花蓮縣') { document.Camp.subarea.options[ctr]=new Option('秀林鄉','972');	ctr=ctr+1;	} 
        if (num=='花蓮縣') { document.Camp.subarea.options[ctr]=new Option('吉安鄉','973');	ctr=ctr+1;	} 
        if (num=='花蓮縣') { document.Camp.subarea.options[ctr]=new Option('壽豐鄉','974');	ctr=ctr+1;	} 
        if (num=='花蓮縣') { document.Camp.subarea.options[ctr]=new Option('鳳林鎮','975');	ctr=ctr+1;	} 
        if (num=='花蓮縣') { document.Camp.subarea.options[ctr]=new Option('光復鄉','976');	ctr=ctr+1;	} 
        if (num=='花蓮縣') { document.Camp.subarea.options[ctr]=new Option('豐濱鄉','977');	ctr=ctr+1;	} 
        if (num=='花蓮縣') { document.Camp.subarea.options[ctr]=new Option('瑞穗鄉','978');	ctr=ctr+1;	} 
        if (num=='花蓮縣') { document.Camp.subarea.options[ctr]=new Option('萬榮鄉','979');	ctr=ctr+1;	} 
        if (num=='花蓮縣') { document.Camp.subarea.options[ctr]=new Option('玉里鎮','981');	ctr=ctr+1;	} 
        if (num=='花蓮縣') { document.Camp.subarea.options[ctr]=new Option('卓溪鄉','982');	ctr=ctr+1;	} 
        if (num=='花蓮縣') { document.Camp.subarea.options[ctr]=new Option('富里鄉','983');	ctr=ctr+1;	} 

    /*金門縣*/

        if (num=='金門縣') { document.Camp.subarea.options[0]=new Option('- 請選鄉鎮 -',''); }
        if (num=='金門縣') { document.Camp.subarea.options[ctr]=new Option('金沙鎮','890');	ctr=ctr+1;	} 
        if (num=='金門縣') { document.Camp.subarea.options[ctr]=new Option('金湖鎮','891');	ctr=ctr+1;	} 
        if (num=='金門縣') { document.Camp.subarea.options[ctr]=new Option('金寧鄉','892');	ctr=ctr+1;	} 
        if (num=='金門縣') { document.Camp.subarea.options[ctr]=new Option('金城鎮','893');	ctr=ctr+1;	} 
        if (num=='金門縣') { document.Camp.subarea.options[ctr]=new Option('烈嶼鄉','894');	ctr=ctr+1;	} 
        if (num=='金門縣') { document.Camp.subarea.options[ctr]=new Option('烏坵鄉','896');	ctr=ctr+1;	}

    /*連江縣*/

        if (num=='連江縣') { document.Camp.subarea.options[0]=new Option('- 請選鄉鎮 -',''); }
        if (num=='連江縣') { document.Camp.subarea.options[ctr]=new Option('南竿鄉','209'); 	ctr=ctr+1;	} 
        if (num=='連江縣') { document.Camp.subarea.options[ctr]=new Option('北竿鄉','210');	ctr=ctr+1;	} 
        if (num=='連江縣') { document.Camp.subarea.options[ctr]=new Option('莒光鄉','211');	ctr=ctr+1;	} 
        if (num=='連江縣') { document.Camp.subarea.options[ctr]=new Option('東引鄉','212');	ctr=ctr+1;	} 

    /*南海諸島*/

        if (num=='南海諸島') { document.Camp.subarea.options[ctr]=new Option('東沙','817');	ctr=ctr+1;	}
        if (num=='南海諸島') { document.Camp.subarea.options[ctr]=new Option('南沙','819');	ctr=ctr+1;	}

        if (num=='其他') { document.Camp.subarea.options[0]=new Option('請自行輸入',''); }

        document.Camp.subarea.length=ctr;
        document.Camp.subarea.options[0].selected=true;
    } 

    function MyAddress(a,b) {
        if ((a == '新竹市') || (a == '嘉義市'))
        { myaddress = a ; }
        else
        { myaddress = a+b ; }
        
        return (myaddress);
    }

    </script>
    {{-- 
        參考頁面：https://youth.blisswisdom.org/camp/winter/form/index_addto.php
    --}}
    <div class='alert alert-info' role='alert'>
        您在本網站所填寫的個人資料，僅用於此次大專營的報名及活動聯絡之用。
    </div>

    <div class='page-header form-group'>
        <h4>{{ $camp_data->fullName }}線上報名表</h4>
    </div>

    <form method='post' action='{{ route('formSubmit', [$batch_id]) }}' name='Camp' class='form-horizontal needs-validation' role='form'>
    @csrf
    <div class='row form-group'>
        <div class='col-md-2'></div>
        <div class='col-md-10'>
            <span class='text-danger'>＊必填</span>
        </div>
    </div>
    {{-- <div class='row form-group'>
        <label for='inputDate' class='col-md-2 control-label text-md-right'>報名日期</label>
        <div class='col-md-10'>
            {{ \Carbon\Carbon::now()->toDateString() }}
            <input type=hidden name=item[50] value='{{ \Carbon\Carbon::now()->toDateString() }}'>
        </div>
    </div> --}}
    <div class='row form-group'>
        <label for='inputDate' class='col-md-2 control-label text-md-right'>營隊梯次</label>
        <div class='col-md-10'>
            {{ $camp_data->fullName . $camp_data->name . '梯' }} ({{ $camp_data->batch_start }} ~ {{ $camp_data->batch_end }})
            @if(isset($applicant_id))
                <input type='hidden' name='applicant_id' value='{{ $applicant_id }}'>
            @endif
        </div>
    </div>
    <div class='row form-group required'>
        <label for='inputName' class='col-md-2 control-label text-md-right'>姓名</label>
        <div class='col-md-10'>
            <input type='text' name='name' value='' class='form-control' id='inputName' placeholder='請填寫全名' required>
        </div>
    </div>

    <div class="row form-group required">
        <label for='inputGender' class='col-md-2 control-label text-md-right'>生理性別</label>
        <div class='col-md-10'>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" value="M" required>
                <label class="form-check-label" for="M">
                    男
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" value="F" required>
                <label class="form-check-label" for="F">
                    女
                </label>
                <div class="invalid-feedback">
                       未選擇生理性別
                </div>
            </div>
        </div>
    </div>


    <div class='row form-group required'>
        <label for='inputNationName' class='col-md-2 control-label text-md-right'>國籍</label>
        <div class='col-md-2'>
        <select class='form-control' name='nationality' id='inputNationName'>
            <option value='美國' >美國</option>
            <option value='加拿大' >加拿大</option>
            <option value='澳大利亞' >澳大利亞</option>
            <option value='紐西蘭' >紐西蘭</option>
            <option value='中國' >中國</option>
            <option value='香港' >香港</option>
            <option value='澳門' >澳門</option>
            <option value='台灣' selected>台灣</option>
            <option value='韓國' >韓國</option>
            <option value='日本' >日本</option>
            <option value='蒙古' >蒙古</option>
            <option value='新加坡' >新加坡</option>
            <option value='馬來西亞' >馬來西亞</option>
            <option value='菲律賓' >菲律賓</option>
            <option value='印尼' >印尼</option>
            <option value='泰國' >泰國</option>
            <option value='越南' >越南</option>
            <option value='其它' >其它</option>
        </select>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputBirth' class='col-md-2 control-label text-md-right'>生日</label>
        <div class='date col-md-10' id='inputBirth'>
            <div class='row form-group required'>
                <div class="col-md-1">
                    西元
                </div>
                <div class="col-md-3">
                    <input type='number' required class='form-control' name='birthyear' min=1985 max='{{ \Carbon\Carbon::now()->subYears(16)->year }}' value='' placeholder=''>
                </div>
                <div class="col-md-1">
                    年
                </div>
                <div class="col-md-2">
                    <input type='number' required class='form-control' name='birthmonth' min=1 max=12 value='' placeholder=''>
                </div>
                <div class="col-md-1">
                    月
                </div>
                <div class="col-md-3">
                    <input type='number' required class='form-control' name='birthday' min=1 max=31 value='' placeholder=''>
                </div>
                <div class="col-md-1">
                    日
                </div>
            </div>
            <div class='help-block with-errors'></div>
        </div>
    </div>


    <div class='row form-group'>
        <label for='inputInterest' class='col-md-2 control-label text-md-right'>興趣</label>
        <div class='col-md-10'>
        <input type='text' name='habbit' value='' class='form-control' id='inputInterest' placeholder=''>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputEmail' class='col-md-2 control-label text-md-right'>電子郵件</label>
        <div class='col-md-10'>
            <input type='email' required name='email' value='' class='form-control' id='inputEmail' placeholder='請務必填寫正確，方便系統發送確認信'>
            <div class="invalid-feedback">
                郵件不正確
            </div>
        </div>
    </div>

    <script language='javascript'>
        $('#inputEmail').bind("cut copy paste",function(e) {
        e.preventDefault();
        });
    </script>
    
    <div class='row form-group required'>
        <label for='inputEmail' class='col-md-2 control-label text-md-right'>確認電子郵件</label>
        <div class='col-md-10'>
            <input type='email' required  name='emailConfirm' value='' class='form-control' id='inputEmailConfirm'>
            {{-- data-match='#inputEmail' data-match-error='郵件不符合' placeholder='請再次填寫確認郵件填寫正確' --}}
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputProgramType' class='col-md-2 control-label text-md-right'>課程學制</label>
        <div class='col-md-10'>
            <label class=radio-inline>
            <input type=radio required name='system' value=博士 > 博士班
            </label> 
            <label class=radio-inline>
            <input type=radio required name='system' value=碩士 > 碩士班
            </label> 
            <label class=radio-inline>
            <input type=radio required name='system' value=大學 > 大學
            </label> 
            <label class=radio-inline>
            <input type=radio required name='system' value=四技 > 四技
            </label> 
            <label class=radio-inline>
            <input type=radio required name='system' value=二技 > 二技
            </label> 
            <label class=radio-inline>
            <input type=radio required name='system' value=二專 > 二專
            </label> 
            <label class=radio-inline>
            <input type=radio required name='system' value=五專 > 五專
            </label> 
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputStuType' class='col-md-2 control-label text-md-right'>部別</label>
        <div class='col-md-10'>
            <label class=radio-inline>
            <input type=radio required name='day_night' value=日間部 > 日間部
            </label> 
            <label class=radio-inline>
            <input type=radio required name='day_night' value=進修部 > 進修部（夜間部）
            </label> 
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputSchoolName' class='col-md-2 control-label text-md-right'>就讀學校</label>

        <div class='col-md-2'>
            <select required  class='form-control' name='school_location' onChange='SchooList(this.options[this.options.selectedIndex].value);'>
                <option value='' selected>請選擇所在縣市...</option>
                <option value='臺北市' >臺北市</option>
                <option value='新北市' >新北市</option>
                <option value='基隆市' >基隆市</option>
                <option value='宜蘭縣' >宜蘭縣</option>
                <option value='花蓮縣' >花蓮縣</option>
                <option value='桃園市' >桃園市</option>
                <option value='新竹市' >新竹市</option>
                <option value='新竹縣' >新竹縣</option>
                <option value='苗栗縣' >苗栗縣</option>
                <option value='臺中市' >臺中市</option>
                <option value='彰化縣' >彰化縣</option>
                <option value='南投縣' >南投縣</option>
                <option value='雲林縣' >雲林縣</option>
                <option value='嘉義市' >嘉義市</option>
                <option value='嘉義縣' >嘉義縣</option>
                <option value='臺南市' >臺南市</option>
                <option value='高雄市' >高雄市</option>
                <option value='屏東縣' >屏東縣</option>
                <option value='臺東縣' >臺東縣</option>
                <option value='澎湖縣' >澎湖縣</option>
                <option value='金門縣' >金門縣</option>
                <option value='連江縣' >連江縣</option>
                <option value='南海諸島' >南海諸島</option>
                <option value='海外' >海外</option>
            </select>
        </div>
        
        <div class='col-md-2'>
            <select class='form-control' name=sname onChange='document.Camp.school.value=this.options[this.options.selectedIndex].value;'>
                <option value=''>請選擇學校</option>
            </select>
        </div>
        <div class='col-md-4'>
            <input type=text name='school' required  value='' class='form-control' readonly='readonly' id='inputSchoolName' placeholder='左方選擇您的學校。如無，請自行填寫'>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputSchoolGrade' class='col-md-2 control-label text-md-right'>系所年級</label>
        <div class='col-md-6'>
            <input type=text required  name='department' value='' class='form-control' id='inputSchoolDept' placeholder='系所科'>
        </div>
        <div class='col-md-2'>
            <select required  class='form-control' name='grade' >
                <option value='' selected>請選擇年級...</option>
                <option value='一' >一</option>
                <option value='二' >二</option>
                <option value='三' >三</option>
                <option value='四' >四</option>
                <option value='五' >五</option>
                <option value='六' >六</option>
                <option value='延畢' >延畢</option>
                <option value='其它' >其它</option>
            </select>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputCell' class='col-md-2 control-label text-md-right'>行動電話</label>
        <div class='col-md-10'>
            <input type=tel required  name='mobile' value='' class='form-control' id='inputCell' placeholder='格式：0912-345-678'>
        </div>
    </div>

    <div class='row form-group'>
        <label for='inputTelHome' class='col-md-2 control-label text-md-right'>家中電話</label>
        <div class='col-md-10'>
            <input type=tel  name='phone_home' value='' class='form-control' id='inputTelHome' placeholder='格式：02-2545-2546#520'>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputAddress' class='col-md-2 control-label text-md-right'>通訊地址</label>
        <div class='col-md-2'>
            <select  name=county class='form-control' onChange='Address(this.options[this.options.selectedIndex].value);'> 
                <option value=''>請選縣市...</option>
                <option value='臺北市'>臺北市</option>
                <option value='新北市'>新北市</option>
                <option value='基隆市'>基隆市</option>
                <option value='宜蘭縣'>宜蘭縣</option>
                <option value='花蓮縣'>花蓮縣</option>
                <option value='桃園市'>桃園市</option>
                <option value='新竹市'>新竹市</option>
                <option value='新竹縣'>新竹縣</option>
                <option value='苗栗縣'>苗栗縣</option>
                <option value='臺中市'>臺中市</option>
                <option value='彰化縣'>彰化縣</option>
                <option value='南投縣'>南投縣</option>
                <option value='雲林縣'>雲林縣</option>
                <option value='嘉義市'>嘉義市</option>
                <option value='嘉義縣'>嘉義縣</option>
                <option value='臺南市'>臺南市</option>
                <option value='高雄市'>高雄市</option>
                <option value='屏東縣'>屏東縣</option>
                <option value='臺東縣'>臺東縣</option>
                <option value='澎湖縣'>澎湖縣</option>
                <option value='金門縣'>金門縣</option>
                <option value='連江縣'>連江縣</option>
                <option value='南海諸島'>南海諸島</option>
                <option value='海外'>海外</option>
            </select>
        </div>
        <div class='col-md-2'>
            <select name=subarea class='form-control' onChange='document.Camp.zipcode.value=this.options[this.options.selectedIndex].value; document.Camp.address.value=MyAddress(document.Camp.county.value, this.options[this.options.selectedIndex].text);'>
                <option value=''>區鄉鎮...</option>
            </select>
        </div>
        <div class='col-md-1'>
            <input readonly type=text name=zipcode value='' class='form-control'>
        </div>
        <div class='col-md-3'>
            <input type=text required  name='address' value='' maxlength=80 class='form-control' placeholder='海外請自行填寫國家及區域'>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputSource' class='col-md-2 control-label text-md-right'>您如何得知此活動？</label>
        <div class='col-md-10'>
            <p class='form-control-static text-danger'>單選，請選最主要管道。</p>
            <label class=radio-inline>
            <input type=radio required name='way' value=FB > FB
            </label> 
            <label class=radio-inline>
            <input type=radio required name='way' value=IG > IG
            </label> 
            <label class=radio-inline>
            <input type=radio required name='way' value=Line > Line
            </label> 
            <label class=radio-inline>
            <input type=radio required name='way' value=官網 > 官網
            </label> 
            <label class=radio-inline>
            <input type=radio required name='way' value=網路(其它) > 網路(其它)
            </label> 
            <label class=radio-inline>
            <input type=radio required name='way' value=班宣(有同學到班上宣傳) > 班宣(有同學到班上宣傳)
            </label> 
            <label class=radio-inline>
            <input type=radio required name='way' value=同學 > 同學
            </label> 
            <label class=radio-inline>
            <input type=radio required name='way' value=親友師長 > 親友師長
            </label> 
            <label class=radio-inline>
            <input type=radio required name='way' value=活動海報 > 活動海報
            </label> 
            <label class=radio-inline>
            <input type=radio required name='way' value=系所公告 > 系所公告
            </label> 
            <label class=radio-inline>
            <input type=radio required name='way' value=其它 > 其它
            </label> 
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputClub' class='col-md-2 control-label text-md-right'>您曾參與的學校社團活動及擔任職務？</label>
        <div class='col-md-10'>
        <textarea class=form-control rows=2 required  name='club' id=inputClub></textarea>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputGoal' class='col-md-2 control-label text-md-right'>你這一生最想追求或完成的目標是什麼？</label>
        <div class='col-md-10'>
        <textarea class=form-control rows=2 required  name='goal' id=inputGoal></textarea>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputExpect' class='col-md-2 control-label text-md-right'>您對這次活動的期望？</label>
        <div class='col-md-10'>
        <textarea class='form-control' rows=2 required  name='expectation' id=inputExpect></textarea>
        </div>
    </div>

    <!--- 福智活動 -->
    <div class='row form-group'>
        <label for='inputFuzhi' class='col-md-2 control-label text-md-right'>曾參與過福智團體所舉辦之活動與課程（可複選）</label>
        <div class='col-md-10'>
            <label><input type=checkbox name=blisswisdom_type[] value='福智高中' > 就讀福智高中</label> <br/>
            <label><input type=checkbox name=blisswisdom_type[] value='福智中小學' > 就讀福智中小學</label> <br/>
            <label><input type=checkbox name=blisswisdom_type[] value='青少年班' > 參加青少年班</label> <br/>
            <label><input type=checkbox name=blisswisdom_type[] value='青少年營' > 參加青少年營</label> <br/>
            <div class='row form-group'>
                <div class='col-md-2'>
                    <label>其它：</label>
                </div>
                <div class='col-md-10'>
                    <input type=text class='form-control' name=blisswisdom_type[] value=''>
                </div>
            </div>
        </div>
    </div>

    <!--- 錄取通知  -->
    <div class='row form-group'>
        <label for='inputNotice' class='col-md-2 control-label text-md-right'>錄取通知方式</label>
        <div class='col-md-10'>
        <p class='form-control-static text-danger'>
        請於 {{ $camp_data->admission_announcing_date }} ({{ $admission_announcing_date_Weekday }}) 自行上網查詢。<br>
        並於 {{ $camp_data->admission_confirming_end }} ({{ $admission_confirming_end_Weekday }}) 前上網回覆確認參加，倘未回覆，視同放棄。
        </p>
        </div>
    </div>

    <!--- 父母親資料  -->
    <div class='row form-group'>
        <div class='col-md-12' id=parent>
            <label> 父母親為福智學員，請填寫資料 
                <input type=button class='btn btn-info' value='填寫父母親資料' onClick='parent_field(1);'>
            </label>
        </div>
    </div>

    <script language='javascript'>

    function parent_field(show) {
        var show_q= '<label>父母親若不是福智學員，本欄不用填寫 <input type=reset class="btn btn-info" value="清除資料" onClick="parent_field(0);"></label>' ;

        var show_field1 = ' <div class="row form-group"> <label for="father_name" class="col-md-2 control-label">父親姓名</label> <div class="col-md-2"> <input type=text  name="father_name" id="father_name" value="" class=form-control > </div> <label for="father_lamrim" class="col-md-2 control-label">廣論班別</label>   <div class="col-md-2"> <input type=text  name="father_lamrim" id="father_lamrim" value="" placeholder="例：北12宗13班" class=form-control > </div>   <label for="father_phone" class="col-md-2 control-label">聯絡電話</label>  <div class="col-md-2"> <input type=tel  name="father_phone" id="father_phone" value="" class=form-control > </div>  </div>' ;
        var show_field2 = ' <div class="row form-group"> <label for="mother_name" class="col-md-2 control-label">母親姓名</label> <div class="col-md-2"> <input type=text  name="mother_name" id="mother_name" value="" class=form-control > </div>  <label for="mother_lamrim" class="col-md-2 control-label">廣論班別</label>  <div class="col-md-2"> <input type=text  name="mother_lamrim" id="mother_lamrim" value="" placeholder="例：北15增16班" class=form-control > </div>  <label for="mother_phone" class="col-md-2 control-label">聯絡電話</label>  <div class="col-md-2"> <input type=tel  name="mother_phone" id="mother_phone" value="" class=form-control > </div>    </div>' ;
        hidden_field = '<label>父母親為福智學員，請填寫資料<input type=button class="btn btn-info" value="填寫父母親資料" onClick="parent_field(1);"></label>' ;

    if (show == 0) { 
        document.getElementById('parent').innerHTML = hidden_field ; 
    } else { 
        document.getElementById('parent').innerHTML = show_q + show_field1 + show_field2 ; 
    }
    }
    </script>

    <!--- 介紹人資料  -->
    <div class='row form-group'>
        <div class='col-md-12' id=referer>
            <label> 若有介紹人，請填寫資料
                <input type=button class='btn btn-info' value='填寫介紹人資料' onClick='referer_field(1);'>
            </label>
        </div>
    </div>

    <script language='javascript'>

    function referer_field(show) {

        var show_q = ' <label> 若無介紹人，不需填寫資料 <input type=button class="btn btn-info" value="清除資料" onClick="referer_field(0);"> </label> ';

        var show_field1 = ' <div class="row form-group"> <label for="introducer_name" class="col-md-2 control-label">介紹人姓名</label> <div class="col-md-4"> <input type=text  name="introducer_name" id="introducer_name" value="" class=form-control > </div> <label for="introducer_relationship" class="col-md-2 control-label">關係</label> <div class="col-md-4"> <input type=text  name="introducer_relationship" id="introducer_relationship" value="" class=form-control placeholder="與介紹人的關係"> </div> </div>' ;

        var show_field2 = ' <div class="row form-group"> <label for="introducer_participated" class="col-md-2 control-label">福智活動</label> <div class="col-md-4"> <input type=text  name="introducer_participated" id="introducer_participated" value="" class=form-control placeholder="曾參加福智舉辦的活動" > </div> <label for="introducer_phone" class="col-md-2 control-label">介紹人聯絡電話</label> <div class="col-md-4"> <input type=tel  name="introducer_phone" id="introducer_phone" value="" class=form-control > </div> </div>' ;

        hidden_field = ' <label> 若有介紹人，請填寫資料 <input type=button class="btn btn-info" value="填寫介紹人資料" onClick="referer_field(1);"> </label> ';

        if (show == 0) { 
        document.getElementById('referer').innerHTML = hidden_field ; 
        } else { 
        document.getElementById('referer').innerHTML = show_q + show_field1 + show_field2; 
        }
    }
    </script>

    <!--- 填寫表單之人 -->
    <div class='row form-group required'>
        <label class='col-md-2 control-label text-md-right'>填寫人</label>
        <div class='col-md-10'>
            <label class=radio-inline>
                <input type=radio required name="is_inperson" value="1" > 本表係本人填寫 <br/>
            </label> <br/>
            <label class=radio-inline>
                <input type=radio required name="is_inperson" value="0" > 本表由他人代填 
            </label>
            <div class='row form-group'>
                <div class='col-md-2'>
                    代填人姓名： 
                </div>
                <div class='col-md-10'>
                    <input type=text class='form-control' name="agent_name" value=''>
                </div>
            </div>
            <div class='row form-group'>
                <div class='col-md-2'>
                    代填人聯絡電話：
                </div>
                <div class='col-md-10'>
                    <input type=tel class='form-control' name="agent_phone" value=''>
                </div>
            </div>     
        </div>
    </div>

    <div class='row form-group required'>
        <label class='col-md-2 control-label text-md-right'>緊急聯絡人</label>
        <div class='col-md-10'>
            <div class='row form-group'>
                <div class='col-md-2'>
                    姓名：
                </div>
                <div class='col-md-10'>
                    <input type='text'class='form-control' name="emergency_name" value=''>
                </div>
            </div>   
            <div class='row form-group'>
                <div class='col-md-2'>
                    關係：
                </div>
                <div class='col-md-10'>
                    <input type='tel' class='form-control' name="emergency_relationship" value=''>
                </div>
            </div>   
            <div class='row form-group'>
                <div class='col-md-2'>
                    聯絡電話：
                </div>
                <div class='col-md-10'>
                    <input type='tel' class='form-control' name="emergency_mobile" value=''>
                </div>
            </div>   
        </div>
    </div>

    <!--- 同意書 -->
    <div class='row form-group required'>
        <label for='inputTerm' class='col-md-2 control-label text-md-right'>肖像權</label>
        <div class='col-md-10 form-check'>
            <label><p class='form-control-static text-danger'>本次營隊期間，主辦單位將剪輯營隊中學員影像為影片，用於營隊、主辦單位等非營利教育推廣使用，並以網路方式播出，報名並獲錄取之本次營隊的人同意將營隊中之影像用於主辦單位及獲主辦單位授權之非營利機構使用。</p></label> <br/>
            <label>
                <input type='radio' required name="portrait_agree" value='1'> 我同意
            </label>  
            <input type='radio' class='d-none' name="portrait_agree" value='0'>  
            <br/>
        </div>
    </div>

    <div class='row form-group required'>
        <label for='inputTerm' class='col-md-2 control-label text-md-right'>個人資料</label>
        <div class='col-md-10'>
            <label>
                <p class='form-control-static text-danger'>福智文教基金會透由本次營隊取得您的聯繫通訊及個人資料，目的在於營隊期間及後續本基金會舉辦之活動，依所蒐集之資料做為訊息通知、行政處理等非營利目的之使用，不會提供給無關之其它私人單位使用。</p>
            </label> <br/>
            <label>
                <input type='radio' required name='profile_agree' value='1'> 我同意
            </label> 
            <input type='radio' class='d-none' name='profile_agree' value='0' >
            <br/>
        </div>
    </div>

    <div class="row form-group text-danger tips d-none">
        <div class='col-md-2'></div>
        <div class='col-md-10'>
            請檢查是否有未填寫或格式錯誤的欄位。
        </div>
    </div>

    <!--- 確認送出 -->
    <div class='row form-group'>
        <div class='col-md-2'></div>
        <div class='col-md-10'>
            <input type='button' class='btn btn-success' value='確認送出' data-toggle="confirmation">
            <input type='button' class='btn btn-warning' value='回上一頁' onclick=self.history.back()>
            <input type='reset' class='btn btn-danger' value='清除再來'>
        </div>
    </div>

    </form>
    <script>
        $('[data-toggle="confirmation"]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            title: "敬請再次確認資料填寫無誤。",
            btnOkLabel: "正確無誤，送出",
            btnCancelLabel: "再檢查一下",
            popout: true,
            onConfirm: function() {
                        if (document.Camp.checkValidity() === false) {
                            $(".tips").removeClass('d-none');
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        else{
                            $(".tips").addClass('d-none');
                            document.Camp.submit();
                        }
                        document.Camp.classList.add('was-validated');
                    }
        });
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {

                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
    <style>
        .required .control-label::after {
            content: "＊";
            color: red;
        }
    </style>
@stop
{{-- 
    參考頁面：https://youth.blisswisdom.org/camp/winter/form/index_addto.php
    --}}