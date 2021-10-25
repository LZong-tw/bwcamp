/* eslint-disable prettier/prettier */ /* eslint-disable prettier/prettier */
<template>
    <span>    
        <div class="row form-group required">
            <label
                for="inputSubjectTeaches"
                class="col-md-2 control-label text-md-right"
                >報名場次</label
            >
            <div class="col-md-10">
                <select name="set_batch_id" id="" class="form-control" required>
                    <option value="">請選擇</option>
                    <option
                        v-for="(item, key) in batches"
                        :value="item.id"
                        :key="key"
                    >
                        {{ item.name }}
                    </option>
                </select>
                <div class="invalid-feedback">請選擇報名場次</div>
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
            batches: null,
        };
    },
    components: {
        officials_and_compulsories: {
            emits: ["titleSelected"],
            data() {
                return {
                    title_s: null,
                };
            },
            methods: {
                titleSend(e) {
                    this.$emit("titleSelected", e.target.value);
                },
            },
            template: `<div class='titles compulsories'>
                <input type='radio' class='compulsories' value='校長' v-model='this.title_s' @click='titleSend'>校長
                <input type='radio' class='compulsories' value='主任' v-model='this.title_s' @click='titleSend'>主任
                <input type='radio' class='compulsories' value='教師' v-model='this.title_s' @click='titleSend'>教師
                <input type='radio' class='compulsories' value='教師兼行政' v-model='this.title_s' @click='titleSend'>教師兼行政
                <input type='radio' class='compulsories' value='代理教師' v-model='this.title_s' @click='titleSend'>代理教師<br>
                <input type='radio' class='compulsories' value='兼課老師' v-model='this.title_s' @click='titleSend'>兼課老師
                <input type='radio' class='compulsories' value='職員' v-model='this.title_s' @click='titleSend'>職員
                <input type='radio' class='compulsories' value='' v-model='this.title_s' @click='titleSend'>其他(請於下方填寫)
            </div>`,
        },
        universities: {
            emits: ["titleSelected"],
            data() {
                return {
                    title_s: null,
                };
            },
            methods: {
                titleSend(e) {
                    this.$emit("titleSelected", this.title_s);
                },
            },
            template: `<div class="titles universities">
                <input type="radio" class="universities" value="校長" v-model='this.title_s' @click='titleSend'>校長
                <input type="radio" class="universities" value="教授" v-model='this.title_s' @click='titleSend'>教授
                <input type="radio" class="universities" value="副教授" v-model='this.title_s' @click='titleSend'>副教授
                <input type="radio" class="universities" value="助理教授" v-model='this.title_s' @click='titleSend'>助理教授
                <input type="radio" class="universities" value="講師" v-model='this.title_s' @click='titleSend'>講師<br>
                <input type="radio" class="universities" value="職員" v-model='this.title_s' @click='titleSend'>職員
                <input type="radio" class="universities" value="" v-model='this.title_s' @click='titleSend'>其他(請於下方填寫)
            </div>`,
        },
        kindergartens: {
            emits: ["titleSelected"],
            data() {
                return {
                    title_s: null,
                };
            },
            methods: {
                titleSend(e) {
                    this.$emit("titleSelected", this.title_s);
                },
            },
            template: `<div class="titles kindergartens">
                <input type="radio" class="kindergartens" value="園長" v-model='this.title_s' @click='titleSend'>園長
                <input type="radio" class="kindergartens" value="主任" v-model='this.title_s' @click='titleSend'>主任
                <input type="radio" class="kindergartens" value="教保組長" v-model='this.title_s' @click='titleSend'>教保組長
                <input type="radio" class="kindergartens" value="教師" v-model='this.title_s' @click='titleSend'>教師
                <input type="radio" class="kindergartens" value="教保員" v-model='this.title_s' @click='titleSend'>教保員<br>
                <input type="radio" class="kindergartens" value="行政" v-model='this.title_s' @click='titleSend'>行政
                <input type="radio" class="kindergartens" value="代理代課教師" v-model='this.title_s' @click='titleSend'>代理代課教師
                <input type="radio" class="kindergartens" value="" v-model='this.title_s' @click='titleSend'>其他(請於下方填寫)
            </div>`,
        },
    },
    computed: {
        toggleTitleComponent() {
            if (this.school_or_course == "大專院校") {
                return "universities";
            } else if (this.school_or_course == "幼教") {
                return "kindergartens";
            } else {
                return "officials_and_compulsories";
            }
        },
    },
    methods: {
        receivesTitle(e) {
            this.title = e ? e : "";
        },
    },
    mounted() {
        let batch_id = window.location.pathname.split('/')[2];
        console.log(batch_id);
        axios.post("/api/getBatch", {id: batch_id}).then((res) => {
            this.batches = res.data;
            console.log(res.data);
        });
    },
};
</script>
