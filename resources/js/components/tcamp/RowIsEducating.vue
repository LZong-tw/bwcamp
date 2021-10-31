/* eslint-disable prettier/prettier */ /* eslint-disable prettier/prettier */
<template>
    <span id="rowIsEducating">
        <div class="row form-group required">
            <label
                for="inputSchoolOrCourse"
                class="col-md-2 control-label text-md-right"
                >任職機關/任教學程</label
            >
            <div class="col-md-10">
                <label class="radio-inline mr-2">
                    <input
                        type="radio"
                        required
                        name="school_or_course"
                        value="教育部"
                        class="officials"
                        v-model="school_or_course"
                        :disabled="this.inputEnabled === false"
                    />
                    教育部
                    <div class="invalid-feedback crumb">
                        請勾選任職機關/任教學程
                    </div>
                </label>
                <label class="radio-inline mx-2">
                    <input type=radio required name='school_or_course'
                    value=教育局/處 class="officials" v-model="school_or_course"
                    :disabled="this.inputEnabled === false"> 教育局/處
                    <div class="invalid-feedback crumb">&nbsp;</div>
                </label>
                <label class="radio-inline mx-2">
                    <input
                        type="radio"
                        required
                        name="school_or_course"
                        value="高中職"
                        class="compulsories"
                        v-model="school_or_course"
                        :disabled="this.inputEnabled === false"
                    />
                    高中職
                    <div class="invalid-feedback crumb">&nbsp;</div>
                </label>
                <label class="radio-inline mx-2">
                    <input
                        type="radio"
                        required
                        name="school_or_course"
                        value="國中"
                        class="compulsories"
                        v-model="school_or_course"
                        :disabled="this.inputEnabled === false"
                    />
                    國中
                    <div class="invalid-feedback crumb">&nbsp;</div>
                </label>
                <label class="radio-inline mx-2">
                    <input
                        type="radio"
                        required
                        name="school_or_course"
                        value="國小"
                        class="compulsories"
                        v-model="school_or_course"
                        :disabled="this.inputEnabled === false"
                    />
                    國小
                    <div class="invalid-feedback crumb">&nbsp;</div>
                </label>
                <label class="radio-inline ml-2">
                    <input
                        type="radio"
                        required
                        name="school_or_course"
                        value="幼教"
                        class="compulsories"
                        v-model="school_or_course"
                        :disabled="this.inputEnabled === false"
                    />
                    幼教
                    <div class="invalid-feedback crumb">&nbsp;</div>
                </label>
            </div>
        </div>
        <div class="row form-group required">
            <label
                for="inputSubjectTeaches"
                class="col-md-2 control-label text-md-right"
                >職稱</label
            >
            <div class="col-md-10">
                <div
                    id="tip"
                    style="color: red; font-weight: bold"
                    v-if="title === null && school_or_course === null"
                >
                    請先選擇任教機關/任教學程
                </div>
                <div
                    id="tip"
                    style="color: red; font-weight: bold"
                    v-else-if="title === null"
                >
                    再選擇職稱，並於文字框做補充
                </div>
                <span v-if="school_or_course !== null">
                    <component
                        :is="selected_component"
                        :populatedTitle="title"
                        @titleSelected="receivesTitle"
                    ></component>
                    <input
                        type="text"
                        required
                        name="title"
                        class="form-control"
                        id="title"
                        :value="title"
                        :disabled="
                            title === null || this.inputEnabled === false
                        "
                        :pattern="
                            title == '兼課老師，兼職時數：' ? '.{11,30}' : null
                        "
                        @input="checkValidity($event)"
                    />
                    <div
                        class="invalid-feedback crumb"
                        v-if="title == '兼課老師，兼職時數：'"
                    >
                        請填寫兼職時數
                    </div>
                    <div class="invalid-feedback crumb" v-else>請填寫職稱</div>
                </span>
            </div>
        </div>
        <div class="row form-group required">
            <label
                for="inputSubjectTeaches"
                class="col-md-2 control-label text-md-right"
                >任教科目</label
            >
            <div class="col-md-10">
                <input
                    type="text"
                    required
                    name="subject_teaches"
                    :value="subject_teaches"
                    class="form-control"
                    id="inputSubjectTeaches"
                    :disabled="this.inputEnabled === false"
                />
                <div class="invalid-feedback crumb">請填寫任教科目</div>
            </div>
        </div>
    </span>
</template>
<script>
import { watch, ref, onMounted, provide, inject, getCurrentInstance } from "vue";

export default {
    setup() {
        let school_or_course = ref(null);
        let selected_component = ref(null);
        window.doThing = false;

        watch(school_or_course, (newValue) => {
            if (newValue == "幼教") {
                selected_component.value = "kindergartens";
            } else {
                selected_component.value = "officials_and_compulsories";
            }
            window.doThing = true;
        });
        
        provide("setEnabled", function (ele) {
                if (window.doThing && window.inputEnabled === false && ele) {
                    ele.disabled = true;
                }
            });
        
        provide("allDescendants", function (node, setEnabled, callback) {
                if(node && node.childNodes) {
                    // console.log(node.childNodes);
                    for (var i = 0; i < node.childNodes.length; i++) {
                        var child = node.childNodes[i];
                        if(callback) {
                            callback(child, setEnabled, callback);
                        }
                        if(setEnabled) {
                            setEnabled(child);
                        }
                    }
                }
            });

        return {
            school_or_course,
            selected_component,
        };
    },
    data() {
        return {
            title: null,
            subject_teaches: null,
        };
    },
    components: {
        officials_and_compulsories: {
            emits: ["titleSelected"],
            props: ["populatedTitle"],
            setup(props) {
                const titleEle = ref();
                const title_s = ref(null || props.populatedTitle);
                const setEnabled = inject("setEnabled");
                const allDescendants = inject("allDescendants");
                onMounted(() => {
                    watch(
                        () => props.populatedTitle,
                        (first, second) => {
                            if (first !== null) {
                                title_s.value = first;
                            }
                            console.log("detected:", first, second);
                        }
                    );
                    allDescendants(getCurrentInstance().refs.titleEle, setEnabled, allDescendants);
                });
                return { titleEle, title_s };
            },
            methods: {
                titleSend(e) {
                    this.$emit("titleSelected", e.target.value);
                },
            },
            template: `<div class='titles compulsories' ref="titleEle">
                <div class="form-check form-check-inline">
                    <label>
                        <input type='radio' class='compulsories' value='校長' v-model='this.title_s' @click='titleSend'>校長
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label>
                        <input type='radio' class='compulsories' value='主任' v-model='this.title_s' @click='titleSend'>主任
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label>
                        <input type='radio' class='compulsories' value='教師' v-model='this.title_s' @click='titleSend'>教師
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label>
                        <input type='radio' class='compulsories' value='教師兼行政' v-model='this.title_s' @click='titleSend'>教師兼行政
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label>
                        <input type='radio' class='compulsories' value='代理教師' v-model='this.title_s' @click='titleSend'>代理教師
                    </label>
                </div><br>
                <div class="form-check form-check-inline">
                    <label>
                        <input type='radio' class='compulsories' value='兼課老師，兼職時數：' v-model='this.title_s' @click='titleSend'>兼課老師
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label>
                        <input type='radio' class='compulsories' value='職員' v-model='this.title_s' @click='titleSend'>職員
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label>
                        <input type='radio' class='compulsories' value='' v-model='this.title_s' @click='titleSend'>其他(請於下方填寫)
                    </label>
                </div>
            </div>`,
        },
        kindergartens: {
            emits: ["titleSelected"],
            props: ["populatedTitle"],
            setup(props) {
                const titleEle = ref();
                const title_s = ref(null || props.populatedTitle);
                const setEnabled = inject("setEnabled");
                const allDescendants = inject("allDescendants");
                onMounted(() => {
                    watch(
                        () => props.populatedTitle,
                        (first, second) => {
                            if (first !== null) {
                                title_s.value = first;
                            }
                            console.log("detected:", first, second);
                        }
                    );
                    allDescendants(getCurrentInstance().refs.titleEle, setEnabled, allDescendants);
                });
                return { titleEle, title_s };
            },
            methods: {
                titleSend(e) {
                    this.$emit("titleSelected", this.title_s);
                },
            },
            template: `<div class="titles kindergartens" ref="titleEle">
                <div class="form-check form-check-inline">
                    <label>
                        <input type="radio" class="kindergartens" value="園長" v-model='this.title_s' @click='titleSend'>園長
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label>
                        <input type="radio" class="kindergartens" value="主任" v-model='this.title_s' @click='titleSend'>主任
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label>
                        <input type="radio" class="kindergartens" value="教保組長" v-model='this.title_s' @click='titleSend'>教保組長
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label>
                        <input type="radio" class="kindergartens" value="教師" v-model='this.title_s' @click='titleSend'>教師
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label>
                        <input type="radio" class="kindergartens" value="教保員" v-model='this.title_s' @click='titleSend'>教保員
                    </label>
                </div><br>
                <div class="form-check form-check-inline">
                    <label>
                        <input type="radio" class="kindergartens" value="行政" v-model='this.title_s' @click='titleSend'>行政
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label>
                        <input type="radio" class="kindergartens" value="代理代課教師" v-model='this.title_s' @click='titleSend'>代理代課教師
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label>
                        <input type="radio" class="kindergartens" value="" v-model='this.title_s' @click='titleSend'>其他(請於下方填寫)
                    </label>
                </div>
            </div>`,
        },
    },
    methods: {
        checkValidity() {
            document.Camp.title.classList.remove("is-invalid");
        },
        receivesTitle(e) {
            this.title = e ? e : "";
            if (this.inputEnabled !== false) {
                this.checkValidity();
            }
        },
    },
    beforeMount() {
        window.activeComponents.push(this);
    },
    mounted() {
        if (this.doPopulate) {
            this.getFieldData(this, "title", "tcamp");
            this.getFieldData(this, "school_or_course", "tcamp");
            this.getFieldData(this, "subject_teaches", "tcamp");
        }
    },
};
</script>
