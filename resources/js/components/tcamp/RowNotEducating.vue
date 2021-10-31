/* eslint-disable prettier/prettier */ /* eslint-disable prettier/prettier */
<template>
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
                    id="title"
                    placeholder="請填寫職稱"
                    :value="this.title"
                    :disabled="this.inputEnabled === false"
                />
                <div class="invalid-feedback crumb">請填寫職稱</div>
            </span>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            title: null,
        };
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