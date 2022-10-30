/* eslint-disable prettier/prettier */ /* eslint-disable prettier/prettier */
<template>
    <span>
        <div class="row form-group required">
            <label for="inputFuzhi" class="col-md-2 control-label text-md-right"
                >是否參加過福智的活動</label
            >
            <div class="col-md-10">
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input
                            type="radio"
                            name="is_blisswisdom"
                            value="1"
                            v-model="is_blisswisdom"
                            required
                            :disabled="this.inputEnabled === false"
                        />
                        是
                        <div class="invalid-feedback">請選擇項目</div>
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input
                            type="radio"
                            name="is_blisswisdom"
                            value="0"
                            v-model="is_blisswisdom"
                            required
                            :disabled="this.inputEnabled === false"
                        />
                        否
                        <div class="invalid-feedback">&nbsp;</div>
                    </label>
                </div>
                <div
                    class="row form-group required mt-2"
                    v-if="is_blisswisdom == 1"
                >
                    <label class="control-label col-md-auto text-md-left"
                        >活動名稱</label
                    >
                    <div class="col-md">
                        <input
                            type="text"
                            class="form-control"
                            name="blisswisdom_type"
                            :value="blisswisdom_type"
                            minlength="3"
                            required
                            :disabled="this.inputEnabled === false"
                        />
                        <div class="invalid-feedback">請輸入參加過的活動</div>
                    </div>
                </div>
            </div>
        </div>
    </span>
</template>
<script>
export default {
    data() {
        return {
            is_blisswisdom: "",
            blisswisdom_type: null,
        };
    },
    beforeMount() {
        window.activeComponents.push(this);
    },
    mounted() {
        if (this.doPopulate) {
            this.getFieldData(this, "isBlisswisdom", "utcamp").then(
                this.getFieldData(this, "blisswisdomType", "utcamp")
            );
        }
    },
};
</script>
