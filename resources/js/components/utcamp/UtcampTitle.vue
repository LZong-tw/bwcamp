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
                <span >
                    <div class="titles universities">
                        <input type="radio" class="universities" value="校長" v-model='title'>校長
                        <input type="radio" class="universities" value="教授" v-model='title'>教授
                        <input type="radio" class="universities" value="副教授" v-model='title'>副教授
                        <input type="radio" class="universities" value="助理教授" v-model='title'>助理教授
                        <input type="radio" class="universities" value="講師" v-model='title'>講師<br>
                        <input type="radio" class="universities" value="職員" v-model='title'>職員
                        <input type="radio" class="universities" value="" v-model='title'>其他(請於下方填寫)
                    </div>
                    <input
                        type="text"
                        required
                        name="title"
                        class="form-control"
                        id="title"
                        :value="title"
                        v-bind:disabled="title === null"
                    />
                    <div class="invalid-feedback crumb">請填寫職稱</div>
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
    methods: { },
    mounted() {
        // console.log("Mounted.");
    },
};
</script>
