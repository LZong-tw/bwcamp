/* eslint-disable prettier/prettier */
/* eslint-disable prettier/prettier */
<template>
    <span id="rowIsEducating">
        <div class='row form-group required'>
            <label for='inputSchoolOrCourse' class='col-md-2 control-label text-md-right'>任職機關/任教學程</label>
            <div class='col-md-10'>
                <label class=radio-inline>
                    <input type=radio required name='school_or_course' value=教育部 class="officials" v-model="school_or_course"> 教育部
                    <div class="invalid-feedback crumb">
                        請勾選任職機關/任教學程
                    </div>
                </label> 
                <label class=radio-inline>
                    <input type=radio required name='school_or_course' value=教育局/處 class="officials" v-model="school_or_course"> 教育局/處
                    <div class="invalid-feedback crumb">
                        &nbsp;
                    </div>
                </label> 
                <label class=radio-inline>
                    <input type=radio required name='school_or_course' value=大專院校 class="universities" v-model="school_or_course"> 大專院校
                    <div class="invalid-feedback crumb">
                        &nbsp;
                    </div>
                </label> <label class=radio-inline>
                    <input type=radio required name='school_or_course' value=高中職 class="compulsories" v-model="school_or_course"> 高中職
                    <div class="invalid-feedback crumb">
                        &nbsp;
                    </div>
                </label> <label class=radio-inline>
                    <input type=radio required name='school_or_course' value=國中 class="compulsories" v-model="school_or_course"> 國中
                    <div class="invalid-feedback crumb">
                        &nbsp;
                    </div>
                </label> <label class=radio-inline>
                    <input type=radio required name='school_or_course' value=國小 class="compulsories" v-model="school_or_course"> 國小
                    <div class="invalid-feedback crumb">
                        &nbsp;
                    </div>
                </label> <label class=radio-inline>
                    <input type=radio required name='school_or_course' value=幼教 class="compulsories" v-model="school_or_course"> 幼教
                    <div class="invalid-feedback crumb">
                        &nbsp;
                    </div>
                </label> 
            </div>
        </div>
        <div class='row form-group required'> 
            <label for='inputSubjectTeaches' class='col-md-2 control-label text-md-right'>職稱</label>
            <div class='col-md-10'>
                <div id='tip' style='color: red; font-weight: bold;'>請先選擇任教機關/任教學程</div>
                <component v-bind:is="toggleTitleComponent" :parent-title="title"></component>
                <input type=text required name='title' class='form-control' id='title' v-model="title">
                <div class="invalid-feedback crumb">
                    請填寫職稱
                </div>
            </div>
        </div>
        <div class='row form-group required'> 
        <label for='inputSubjectTeaches' class='col-md-2 control-label text-md-right'>任教科系/任教科目</label>
            <div class='col-md-10'>
                <input type=text required  name='subject_teaches' value='' class='form-control' id='inputSubjectTeaches'>
                <div class="invalid-feedback crumb">
                    請填寫任教科系/任教科目
                </div>
            </div>
        </div>
    </span>
</template>
<script>
export default {
    data() {
        return {
            school_or_course: null,
            title: null,
        };
    },
    components: {
        officials_and_compulsories: {
            props: ["parentTitle"],
            template: `<div class='titles compulsories'>
                <input type='radio' name='data[12]' class='compulsories' value='校長' :parent-title>校長
                <input type='radio' name='data[12]' class='compulsories' value='主任' :parent-title>主任
                <input type='radio' name='data[12]' class='compulsories' value='教師' :parent-title>教師
                <input type='radio' name='data[12]' class='compulsories' value='教師兼行政' :parent-title>教師兼行政
                <input type='radio' name='data[12]' class='compulsories' value='代理教師' :parent-title>代理教師<br>
                <input type='radio' name='data[12]' class='compulsories' value='兼課老師' :parent-title>兼課老師
                <input type='radio' name='data[12]' class='compulsories' value='職員' :parent-title>職員
                <input type='radio' name='data[12]' class='compulsories' value='其他' :parent-title>其他
            </div>`,
        },
        universities: {
            props: ["parentTitle"],
            template: `<div class="titles universities">
                <input type="radio" name="data[12]" class="universities" value="校長" :parent-title>校長
                <input type="radio" name="data[12]" class="universities" value="教授" :parent-title>教授
                <input type="radio" name="data[12]" class="universities" value="副教授" :parent-title>副教授
                <input type="radio" name="data[12]" class="universities" value="助理教授" :parent-title>助理教授
                <input type="radio" name="data[12]" class="universities" value="講師" :parent-title>講師<br>
                <input type="radio" name="data[12]" class="universities" value="職員" :parent-title>職員
                <input type="radio" name="data[12]" class="universities" value="其他" :parent-title>其他
            </div>`,
        },
    },
    computed: {
        toggleTitleComponent() {
            if (this.school_or_course == "大專院校") {
                return "universities";
            } else {
                return "officials_and_compulsories";
            }
        },
    },
    methods: {
        showLog(e) {
            console.log(this.is_educating)
        }
    },
    mounted() {
        console.log("Mounted.");
    },
}
</script>
