/* eslint-disable prettier/prettier */ /* eslint-disable prettier/prettier */
<template>
    <span>
        <div class="row form-group required">
            <label
                for="inputSubjectTeaches"
                class="col-md-2 control-label text-md-right"
                >身分別</label
            >
            <div class="col-md-10">
                <span>
                    <div>
                        <div class="form-check form-check-inline">
                            <label>
                                <input
                                    type="radio"
                                    value="教授"
                                    v-model="title"
                                    @change="checkValidity()"
                                    name="position"
                                    required
                                    :disabled="this.inputEnabled === false"
                                />教授
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label>
                                <input
                                    type="radio"
                                    value="副教授"
                                    v-model="title"
                                    @change="checkValidity()"
                                    name="position"
                                    required
                                    :disabled="this.inputEnabled === false"
                                />副教授
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label>
                                <input
                                    type="radio"
                                    value="助理教授"
                                    v-model="title"
                                    @change="checkValidity()"
                                    name="position"
                                    required
                                    :disabled="this.inputEnabled === false"
                                />助理教授
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label>
                                <input
                                    type="radio"
                                    value="講師"
                                    v-model="title"
                                    @change="checkValidity()"
                                    name="position"
                                    required
                                    :disabled="this.inputEnabled === false"
                                />講師
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label>
                                <input
                                    type="radio"
                                    value="行政人員"
                                    v-model="title"
                                    @change="checkValidity()"
                                    name="position"
                                    required
                                    :disabled="this.inputEnabled === false"
                                />行政人員
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label>
                                <input
                                    type="radio"
                                    value=""
                                    v-model="title"
                                    @change="checkValidity()"
                                    name="position"
                                    required
                                    :disabled="this.inputEnabled === false"
                                />其他
                            </label>
                        </div>
                        <div class="invalid-feedback crumb">請選擇身分別</div>
                    </div>
                </span>
            </div>
        </div>
        <div class="row form-group required">
            <label
                for="inputSubjectTeaches"
                class="col-md-2 control-label text-md-right"
                >職稱</label
            >
            <div class="col-md-10">
                <span>
                    <input
                        type="text"
                        required
                        name="title"
                        class="form-control"
                        pattern=".{2,40}"
                        placeholder="若為兼任行政主管，亦請於此填寫職稱"
                        :disabled="this.inputEnabled === false"
                        :value="this.doPopulate === true ? title : null"
                    />
                    <div class="invalid-feedback crumb" id="title">
                        請填寫職稱
                    </div>
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
    beforeMount() {
        window.activeComponents.push(this);
    },
    mounted() {
        if (this.doPopulate) {
            this.getFieldData(this, "title", "tcamp");
        }
    },
};
</script>
