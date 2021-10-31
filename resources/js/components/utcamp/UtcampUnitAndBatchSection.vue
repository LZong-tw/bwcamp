/* eslint-disable prettier/prettier */
/* eslint-disable prettier/prettier */
/* eslint-disable prettier/prettier */
<template>
    <span>          
        <div class="row form-group required">
            <label for="inputName" class="col-md-2 control-label text-md-right">服務單位</label>
            <div class="col-md-10">
                <select
                    class="form-control"
                    v-model="unit_area"
                    name="unit_county"
                    :disabled="this.inputEnabled === false"
                >
                    <option value="">- 請選擇行政區 -</option>
                    <option value="基隆市">基隆市</option>
                    <option value="臺北市">臺北市</option>
                    <option value="新北市">新北市</option>
                    <option value="桃園市">桃園市</option>
                    <option value="新竹市">新竹市</option>
                    <option value="新竹縣">新竹縣</option>
                    <option value="苗栗縣">苗栗縣</option>
                    <option value="臺中市">臺中市</option>
                    <option value="彰化縣">彰化縣</option>
                    <option value="南投縣">南投縣</option>
                    <option value="雲林縣">雲林縣</option>
                    <option value="嘉義市">嘉義市</option>
                    <option value="嘉義縣">嘉義縣</option>
                    <option value="臺南市">臺南市</option>
                    <option value="高雄市">高雄市</option>
                    <option value="屏東縣">屏東縣</option>
                    <option value="宜蘭縣">宜蘭縣</option>
                    <option value="花蓮縣">花蓮縣</option>
                    <option value="臺東縣">臺東縣</option>
                    <option value="金門縣">金門縣</option>
                    <option value="澎湖縣">澎湖縣</option>
                    <option value="其他">其他</option>
                </select>
                <select
                    v-if="unit_area != '其他'"
                    id="unit"
                    class="form-control"
                    v-model="selected"
                    :disabled="this.inputEnabled === false"
                    required
                >
                    <option :value="''" v-if="this.inputEnabled !== false">- 請選擇 -</option>
                    <option v-else>{{ selected }}</option>
                    <option
                        v-for="(item, key) in filtered_schools"
                        :value="item"
                        :key="key"
                    >
                        {{ item.full_name }}
                    </option>
                </select>
                <input
                    type="text"
                    v-if="unit_area == '其他'"
                    class="form-control"
                    name="unit"
                    placeholder="請自行填寫"
                    :disabled="this.inputEnabled === false"
                    :value="
                        selected.abbreviation ? selected.abbreviation : unit
                    "
                    required
                />
                <input
                    v-else
                    type="hidden"
                    name="unit"
                    :disabled="this.inputEnabled === false"
                    :value="
                        selected.abbreviation ? selected.abbreviation : unit
                    "
                />
                <div class="invalid-feedback">請選擇服務單位</div>
            </div>
        </div>
        <component v-bind:is="toggleTaichungComponent"></component>        
    </span>
</template>
<script>
import rowIsTaichung from "./RowIsTaichung.vue";
export default {
    components: {
        rowIsTaichung,
    },
    data() {
        return {
            selected: '',
            unit_county: null,
            unit_area: '',
            unit: null,
            schools: [
                {area: '宜蘭縣', abbreviation: '佛光大學', full_name: '佛光大學', is_taichung: 0}, 
                {area: '宜蘭縣', abbreviation: '宜蘭大學', full_name: '國立宜蘭大學', is_taichung: 0}, 
                {area: '宜蘭縣', abbreviation: '聖母醫專', full_name: '聖母醫護管理專科學校', is_taichung: 0}, 
                {area: '宜蘭縣', abbreviation: '蘭陽學院', full_name: '蘭陽技術學院', is_taichung: 0}, 
                {area: '花蓮縣', abbreviation: '大漢學院', full_name: '大漢技術學院', is_taichung: 0}, 
                {area: '花蓮縣', abbreviation: '東華大學', full_name: '國立東華大學', is_taichung: 0}, 
                {area: '花蓮縣', abbreviation: '慈濟大學', full_name: '慈濟大學', is_taichung: 0}, 
                {area: '花蓮縣', abbreviation: '慈濟科大', full_name: '慈濟科技大學', is_taichung: 0}, 
                {area: '花蓮縣', abbreviation: '臺灣觀光', full_name: '臺灣觀光學院', is_taichung: 0}, 
                {area: '金門縣', abbreviation: '金門大學', full_name: '國立金門大學', is_taichung: 0}, 
                {area: '南投縣', abbreviation: '崇德學院', full_name: '一貫道崇德學院', is_taichung: 1}, 
                {area: '南投縣', abbreviation: '南開科大', full_name: '南開科技大學', is_taichung: 1}, 
                {area: '南投縣', abbreviation: '暨南大學', full_name: '國立暨南國際大學', is_taichung: 1}, 
                {area: '屏東縣', abbreviation: '大仁科大', full_name: '大仁科技大學', is_taichung: 0}, 
                {area: '屏東縣', abbreviation: '美和科大', full_name: '美和學校財團法人美和科技大學', is_taichung: 0}, 
                {area: '屏東縣', abbreviation: '屏東大學', full_name: '國立屏東大學', is_taichung: 0}, 
                {area: '屏東縣', abbreviation: '屏東科大', full_name: '國立屏東科技大學', is_taichung: 0}, 
                {area: '屏東縣', abbreviation: '慈惠醫專', full_name: '慈惠醫護管理專科學校', is_taichung: 0}, 
                {area: '苗栗縣', abbreviation: '仁德醫專', full_name: '仁德醫護管理專科學校', is_taichung: 1}, 
                {area: '苗栗縣', abbreviation: '聯合大學', full_name: '國立聯合大學', is_taichung: 1}, 
                {area: '苗栗縣', abbreviation: '育達科大', full_name: '育達科技大學', is_taichung: 1}, 
                {area: '桃園市', abbreviation: '警察大學', full_name: '中央警察大學', is_taichung: 0}, 
                {area: '桃園市', abbreviation: '中原大學', full_name: '中原大學', is_taichung: 0}, 
                {area: '桃園市', abbreviation: '元智大學', full_name: '元智大學', is_taichung: 0}, 
                {area: '桃園市', abbreviation: '長庚大學', full_name: '長庚大學', is_taichung: 0}, 
                {area: '桃園市', abbreviation: '長庚科大', full_name: '長庚科技大學林口校區', is_taichung: 0}, 
                {area: '桃園市', abbreviation: '南亞學院', full_name: '南亞技術學院', is_taichung: 0}, 
                {area: '桃園市', abbreviation: '健行科大', full_name: '健行科技大學', is_taichung: 0}, 
                {area: '桃園市', abbreviation: '中央大學', full_name: '國立中央大學', is_taichung: 0}, 
                {area: '桃園市', abbreviation: '國立體大', full_name: '國立體育大學', is_taichung: 0}, 
                {area: '桃園市', abbreviation: '國防大學', full_name: '國防大學', is_taichung: 0}, 
                {area: '桃園市', abbreviation: '陸軍專校', full_name: '陸軍專科學校', is_taichung: 0}, 
                {area: '桃園市', abbreviation: '開南大學', full_name: '開南大學', is_taichung: 0}, 
                {area: '桃園市', abbreviation: '新生醫專', full_name: '新生醫護管理專科學校', is_taichung: 0}, 
                {area: '桃園市', abbreviation: '萬能科大', full_name: '萬能科技大學', is_taichung: 0}, 
                {area: '桃園市', abbreviation: '龍華科大', full_name: '龍華科技大學', is_taichung: 0}, 
                {area: '高雄市', abbreviation: '空軍官校', full_name: '中華民國空軍軍官學校', is_taichung: 0}, 
                {area: '高雄市', abbreviation: '海軍官校', full_name: '中華民國海軍軍官學校', is_taichung: 0}, 
                {area: '高雄市', abbreviation: '陸軍官校', full_name: '中華民國陸軍軍官學校', is_taichung: 0}, 
                {area: '高雄市', abbreviation: '文藻外語', full_name: '文藻外語大學', is_taichung: 0}, 
                {area: '高雄市', abbreviation: '正修科大', full_name: '正修科技大學', is_taichung: 0}, 
                {area: '高雄市', abbreviation: '育英醫專', full_name: '育英醫護管理專科學校', is_taichung: 0}, 
                {area: '高雄市', abbreviation: '和春學院', full_name: '和春技術學院', is_taichung: 0}, 
                {area: '高雄市', abbreviation: '東方設大', full_name: '東方設計大學', is_taichung: 0}, 
                {area: '高雄市', abbreviation: '航技學院', full_name: '空軍航空技術學院', is_taichung: 0}, 
                {area: '高雄市', abbreviation: '天皇學院', full_name: '一貫道天皇學院', is_taichung: 0}, 
                {area: '高雄市', abbreviation: '高苑科大', full_name: '高苑科技大學', is_taichung: 0}, 
                {area: '高雄市', abbreviation: '高雄空大', full_name: '高雄市立空中大學', is_taichung: 0}, 
                {area: '高雄市', abbreviation: '高雄醫學', full_name: '高雄醫學大學', is_taichung: 0}, 
                {area: '高雄市', abbreviation: '中山大學', full_name: '國立中山大學', is_taichung: 0}, 
                {area: '高雄市', abbreviation: '高雄大學', full_name: '國立高雄大學', is_taichung: 0}, 
                {area: '高雄市', abbreviation: '高科建工', full_name: '國立高雄科技大學', is_taichung: 0}, 
                {area: '高雄市', abbreviation: '高雄師大', full_name: '國立高雄師範大學', is_taichung: 0}, 
                {area: '高雄市', abbreviation: '高雄餐旅', full_name: '國立高雄餐旅大學', is_taichung: 0}, 
                {area: '高雄市', abbreviation: '義守大學', full_name: '義守大學', is_taichung: 0}, 
                {area: '高雄市', abbreviation: '實踐大學', full_name: '實踐大學高雄校區', is_taichung: 0}, 
                {area: '高雄市', abbreviation: '輔英科大', full_name: '輔英科技大學', is_taichung: 0}, 
                {area: '高雄市', abbreviation: '樹人醫專', full_name: '樹人醫護管理專科學校', is_taichung: 0}, 
                {area: '高雄市', abbreviation: '樹德科大', full_name: '樹德科技大學', is_taichung: 0}, 
                {area: '基隆市', abbreviation: '臺灣海大', full_name: '國立臺灣海洋大學', is_taichung: 0}, 
                {area: '基隆市', abbreviation: '崇右科大', full_name: '崇右影藝科技大學', is_taichung: 0}, 
                {area: '基隆市', abbreviation: '經國學院', full_name: '經國管理暨健康學院', is_taichung: 0}, 
                {area: '雲林縣', abbreviation: '虎尾科大', full_name: '國立虎尾科技大學', is_taichung: 0}, 
                {area: '雲林縣', abbreviation: '雲林科大', full_name: '國立雲林科技大學', is_taichung: 0}, 
                {area: '雲林縣', abbreviation: '環球科大', full_name: '環球科技大學', is_taichung: 0}, 
                {area: '新北市', abbreviation: '宏國德霖', full_name: '宏國德霖科技大學', is_taichung: 0}, 
                {area: '新北市', abbreviation: '亞東學院', full_name: '亞東技術學院', is_taichung: 0}, 
                {area: '新北市', abbreviation: '明志科大', full_name: '明志科技大學', is_taichung: 0}, 
                {area: '新北市', abbreviation: '東南科大', full_name: '東南科技大學', is_taichung: 0}, 
                {area: '新北市', abbreviation: '法鼓學院', full_name: '法鼓文理學院', is_taichung: 0}, 
                {area: '新北市', abbreviation: '致理科大', full_name: '致理學校財團法人致理科技大學', is_taichung: 0}, 
                {area: '新北市', abbreviation: '真理大學', full_name: '真理大學', is_taichung: 0}, 
                {area: '新北市', abbreviation: '耕莘健管', full_name: '耕莘健康管理專科學校', is_taichung: 0}, 
                {area: '新北市', abbreviation: '馬偕醫學院', full_name: '馬偕醫學院', is_taichung: 0}, 
                {area: '新北市', abbreviation: '國立空大', full_name: '國立空中大學', is_taichung: 0}, 
                {area: '新北市', abbreviation: '臺北大學', full_name: '國立臺北大學', is_taichung: 0}, 
                {area: '新北市', abbreviation: '臺灣藝大', full_name: '國立臺灣藝術大學', is_taichung: 0}, 
                {area: '新北市', abbreviation: '淡江大學', full_name: '淡江大學', is_taichung: 0}, 
                {area: '新北市', abbreviation: '景文科大', full_name: '景文科技大學', is_taichung: 0}, 
                {area: '新北市', abbreviation: '華夏科大', full_name: '華夏科技大學', is_taichung: 0}, 
                {area: '新北市', abbreviation: '華梵大學', full_name: '華梵大學', is_taichung: 0}, 
                {area: '新北市', abbreviation: '聖約翰科大', full_name: '聖約翰科技大學', is_taichung: 0}, 
                {area: '新北市', abbreviation: '輔仁大學', full_name: '輔仁大學', is_taichung: 0}, 
                {area: '新北市', abbreviation: '黎明學院', full_name: '黎明技術學院', is_taichung: 0}, 
                {area: '新北市', abbreviation: '醒吾科大', full_name: '醒吾科技大學', is_taichung: 0}, 
                {area: '新竹市', abbreviation: '中華大學', full_name: '中華大學', is_taichung: 0}, 
                {area: '新竹市', abbreviation: '玄奘大學', full_name: '玄奘大學', is_taichung: 0}, 
                {area: '新竹市', abbreviation: '元培科大', full_name: '元培醫事科技大學', is_taichung: 0}, 
                {area: '新竹市', abbreviation: '清華大學', full_name: '國立清華大學', is_taichung: 0}, 
                {area: '新竹市', abbreviation: '陽明交大', full_name: '國立陽明交通大學交大校區', is_taichung: 0}, 
                {area: '新竹縣', abbreviation: '大華科大', full_name: '敏實科技大學', is_taichung: 0}, 
                {area: '新竹縣', abbreviation: '中國科大', full_name: '中國科技大學', is_taichung: 0}, 
                {area: '新竹縣', abbreviation: '中華科大', full_name: '中華科技大學新竹分部', is_taichung: 0}, 
                {area: '新竹縣', abbreviation: '明新科大', full_name: '明新科技大學', is_taichung: 0}, 
                {area: '嘉義市', abbreviation: '大同學院', full_name: '大同技術學院', is_taichung: 0}, 
                {area: '嘉義市', abbreviation: '崇仁醫專', full_name: '崇仁醫護管理專科學校', is_taichung: 0}, 
                {area: '嘉義縣', abbreviation: '吳鳳學院', full_name: '吳鳳科技大學', is_taichung: 0}, 
                {area: '嘉義縣', abbreviation: '長庚科大', full_name: '長庚科技大學嘉義校區', is_taichung: 0}, 
                {area: '嘉義縣', abbreviation: '南華大學', full_name: '南華大學', is_taichung: 0}, 
                {area: '嘉義縣', abbreviation: '中正大學', full_name: '國立中正大學', is_taichung: 0}, 
                {area: '嘉義縣', abbreviation: '嘉義大學', full_name: '國立嘉義大學', is_taichung: 0}, 
                {area: '彰化縣', abbreviation: '大葉大學', full_name: '大葉大學', is_taichung: 1}, 
                {area: '彰化縣', abbreviation: '中州科大', full_name: '中州科技大學', is_taichung: 1}, 
                {area: '彰化縣', abbreviation: '明道大學', full_name: '明道大學', is_taichung: 1}, 
                {area: '彰化縣', abbreviation: '建國科大', full_name: '建國科技大學', is_taichung: 1}, 
                {area: '彰化縣', abbreviation: '彰化師大', full_name: '國立彰化師範大學', is_taichung: 1}, 
                {area: '臺中市', abbreviation: '中山醫大', full_name: '中山醫學大學', is_taichung: 1}, 
                {area: '臺中市', abbreviation: '中國醫大', full_name: '中國醫藥大學', is_taichung: 1}, 
                {area: '臺中市', abbreviation: '中臺科大', full_name: '中臺科技大學', is_taichung: 1}, 
                {area: '臺中市', abbreviation: '弘光科大', full_name: '弘光科技大學', is_taichung: 1}, 
                {area: '臺中市', abbreviation: '亞洲大學', full_name: '亞洲大學', is_taichung: 1}, 
                {area: '臺中市', abbreviation: '東海大學', full_name: '東海大學', is_taichung: 1}, 
                {area: '臺中市', abbreviation: '修平科大', full_name: '修平學校財團法人修平科技大學', is_taichung: 1}, 
                {area: '臺中市', abbreviation: '中興大學', full_name: '國立中興大學', is_taichung: 1}, 
                {area: '臺中市', abbreviation: '勤益科大', full_name: '國立勤益科技大學', is_taichung: 1}, 
                {area: '臺中市', abbreviation: '臺中科大', full_name: '國立臺中科技大學', is_taichung: 1}, 
                {area: '臺中市', abbreviation: '臺中教大', full_name: '國立臺中教育大學', is_taichung: 1}, 
                {area: '臺中市', abbreviation: '臺灣體大', full_name: '國立臺灣體育運動大學', is_taichung: 1}, 
                {area: '臺中市', abbreviation: '逢甲大學', full_name: '逢甲大學', is_taichung: 1}, 
                {area: '臺中市', abbreviation: '朝陽科大', full_name: '朝陽科技大學', is_taichung: 1}, 
                {area: '臺中市', abbreviation: '僑光科大', full_name: '僑光科技大學', is_taichung: 1}, 
                {area: '臺中市', abbreviation: '靜宜大學', full_name: '靜宜大學', is_taichung: 1}, 
                {area: '臺中市', abbreviation: '嶺東科大', full_name: '嶺東科技大學', is_taichung: 1}, 
                {area: '臺北市', abbreviation: '大同大學', full_name: '大同大學', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '文化大學', full_name: '中國文化大學', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '中國科大', full_name: '中國科技大學臺北校區', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '中華科大', full_name: '中華科技大學', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '世新大學', full_name: '世新大學', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '臺北海洋', full_name: '台北海洋科技大學', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '台灣神學研究學院', full_name: '台灣神學研究學院', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '東吳大學', full_name: '東吳大學', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '城市科大', full_name: '城市學校財團法人臺北城市科技大學', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '馬偕醫專', full_name: '馬偕醫護管理專科學校', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '政治大學', full_name: '國立政治大學', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '陽明交大', full_name: '國立陽明交通大學陽明校區', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '臺北科大', full_name: '國立臺北科技大學', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '臺北商大', full_name: '國立臺北商業大學', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '國北教大', full_name: '國立臺北教育大學', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '臺北藝大', full_name: '國立臺北藝術大學', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '臺北護理', full_name: '國立臺北護理健康大學', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '臺灣大學', full_name: '國立臺灣大學', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '臺灣科大', full_name: '國立臺灣科技大學', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '臺灣師大', full_name: '國立臺灣師範大學', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '臺灣戲曲', full_name: '國立臺灣戲曲學院', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '國防醫學', full_name: '國防醫學院', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '康寧大學', full_name: '康寧大學', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '實踐大學', full_name: '實踐大學臺北校區', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '臺北市大', full_name: '臺北市立大學', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '臺北基督學院', full_name: '臺北基督學院', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '臺北醫學', full_name: '臺北醫學大學', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '臺灣警專', full_name: '臺灣警察專科學校', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '銘傳大學', full_name: '銘傳大學', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '德明科大', full_name: '德明財經科技大學', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '稻江學院', full_name: '稻江科技暨管理學院', is_taichung: 0}, 
                {area: '臺北市', abbreviation: '基督教台灣浸會神學院', full_name: '基督教台灣浸會神學院', is_taichung: 0}, 
                {area: '臺東縣', abbreviation: '臺東大學', full_name: '國立臺東大學', is_taichung: 0}, 
                {area: '臺東縣', abbreviation: '臺東專校', full_name: '國立臺東專科學校', is_taichung: 0}, 
                {area: '臺南市', abbreviation: '中信金融', full_name: '中信金融管理學院', is_taichung: 0}, 
                {area: '臺南市', abbreviation: '中華醫大', full_name: '中華醫事科技大學', is_taichung: 0}, 
                {area: '臺南市', abbreviation: '臺南科大', full_name: '台南應用科技大學', is_taichung: 0}, 
                {area: '臺南市', abbreviation: '臺灣首大', full_name: '台灣首府大學', is_taichung: 0}, 
                {area: '臺南市', abbreviation: '台灣基督長老教會南神神學院', full_name: '台灣基督長老教會南神神學院', is_taichung: 0}, 
                {area: '臺南市', abbreviation: '長榮大學', full_name: '長榮大學', is_taichung: 0}, 
                {area: '臺南市', abbreviation: '南臺科大', full_name: '南臺學校財團法人南臺科技大學', is_taichung: 0}, 
                {area: '臺南市', abbreviation: '成功大學', full_name: '國立成功大學', is_taichung: 0}, 
                {area: '臺南市', abbreviation: '臺南大學', full_name: '國立臺南大學', is_taichung: 0}, 
                {area: '臺南市', abbreviation: '臺南藝大', full_name: '國立臺南藝術大學', is_taichung: 0}, 
                {area: '臺南市', abbreviation: '臺南護專', full_name: '國立臺南護理專科學校', is_taichung: 0}, 
                {area: '臺南市', abbreviation: '崑山科大', full_name: '崑山科技大學', is_taichung: 0}, 
                {area: '臺南市', abbreviation: '敏惠醫專', full_name: '敏惠醫護管理專科學校', is_taichung: 0}, 
                {area: '臺南市', abbreviation: '嘉南藥理', full_name: '嘉南藥理大學', is_taichung: 0}, 
                {area: '臺南市', abbreviation: '遠東科大', full_name: '遠東科技大學', is_taichung: 0}, 
                {area: '澎湖縣', abbreviation: '澎湖科大', full_name: '國立澎湖科技大學', is_taichung: 0},
            ],
        };
    },
    computed: {
        toggleTaichungComponent() {
            if (this.selected && this.selected.is_taichung == 1) {
                return "row-Is-Taichung";
            }
            return null;
        },
        filtered_schools() {
            this.clear();
            return (
                this.schools &&
                this.schools.filter((item) => item.area == this.unit_area)
            );
        },
    },
    methods: {
        clear() {
            this.selected = '';
        },
    },
    beforeMount() {
        window.activeComponents.push(this);
    },
    mounted() {
        if (this.doPopulate) {
            let self = this;
            this.getFieldData(this, "unitCounty", "tcamp").then(() => {
                self.unit_area = self.unit_county;
                self.getFieldData(this, "unit", "tcamp").then(() => {
                    self.schools.forEach((item) => {
                        if (item && item.abbreviation == self.unit) {
                            self.selected = item;
                        }
                    });
                });
            });
        }
    },
}
</script>
