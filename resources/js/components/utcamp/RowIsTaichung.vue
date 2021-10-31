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
                <select
                    name="set_batch_id"
                    id=""
                    class="form-control"
                    required
                    :disabled="this.inputEnabled === false"
                >
                    <option value="" v-if="!batch_id">請選擇</option>
                    <option v-else>{{ batches.batch_id }}</option>
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
            batch_id: null,
        };
    },
    methods: {
        receivesTitle(e) {
            this.title = e ? e : "";
        },
    },
    beforeMount() {
        window.activeComponents.push(this);
    },
    mounted() {
        let batch_id = window.location.pathname.split("/")[2];
        axios.post("/api/getBatch", { id: batch_id }).then((res) => {
            this.batches = res.data;
            if (this.doPopulate) {
                this.getFieldData(this, "batchId");
            }
        });
    },
};
</script>
