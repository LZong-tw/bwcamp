/* eslint-disable prettier/prettier */ /* eslint-disable prettier/prettier */
<template>
    <span>        
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
                    v-if="title === null"
                >
                    請選擇職稱，並於文字框做補充
                </div>
                <span>
                    <div>
                        <input type="radio" value="校長" v-model='title' @change="checkValidity()" name="pre_title" required>校長
                        <input type="radio" value="教授" v-model='title' @change="checkValidity()" name="pre_title" required>教授
                        <input type="radio" value="副教授" v-model='title' @change="checkValidity()" name="pre_title" required>副教授
                        <input type="radio" value="助理教授" v-model='title' @change="checkValidity()" name="pre_title" required>助理教授
                        <input type="radio" value="講師" v-model='title' @change="checkValidity()" name="pre_title" required>講師<br>
                        <input type="radio" value="行政人員：" v-model='title' @change="checkValidity()" name="pre_title" required>行政人員(請於下方填寫職稱)
                        <input type="radio" value="" v-model='title' @change="checkValidity()" name="pre_title" required>其他(請於下方填寫)
                        <div class="invalid-feedback crumb">請選擇並填寫職稱</div>
                    </div>
                    <input
                        type="text"
                        required
                        name="title"
                        class="form-control"
                        :value="title"
                        :disabled="title === null"
                        :pattern="
                            title == '行政人員：' ? '.{7,40}' : null
                        "
                        @input="checkValidity($event)"
                    />
                    <div class="invalid-feedback crumb" id="title">請填寫職稱</div>
                </span>
            </div>
        </div>
    </span>
</template>
<script>
export default {
    data() {
        return {
            title: null,
        };
    },
    methods: { 
        checkValidity() {
            document.Camp.title.classList.remove("is-invalid");
        },
    },
    mounted() {
        // console.log("Mounted.");
    },
};
</script>
