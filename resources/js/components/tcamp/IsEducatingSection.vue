/* eslint-disable prettier/prettier */ /* eslint-disable prettier/prettier */
<template>
    <span>
        <div class="row form-group required">
            <label
                for="inputIsEducating"
                class="col-md-2 control-label text-md-right"
                >是否現任在學校或教育單位任職</label
            >
            <div class="col-md-10">
                <label class="radio-inline">
                    <input
                        type="radio"
                        required
                        name="is_educating"
                        value="1"
                        id="is_educating_y"
                        v-model="is_educating"
                        :disabled="this.inputEnabled === false"
                    />
                    是（請續填下方任職資料）
                    <div class="invalid-feedback">
                        請勾選是否現任在學校或教育單位任職
                    </div>
                </label>
                <label class="radio-inline">
                    <input
                        type="radio"
                        required
                        name="is_educating"
                        value="0"
                        id="is_educating_n"
                        v-model="is_educating"
                        :disabled="this.inputEnabled === false"
                    />
                    否
                    <div class="invalid-feedback">&nbsp;</div>
                </label>
            </div>
        </div>
        <component v-bind:is="toggleEducatingComponent"></component>
    </span>
</template>
<script>
import rowIsEducating from "./RowIsEducating.vue";
import rowNotEducating from "./RowNotEducating.vue";

export default {
    components: {
        rowIsEducating,
        rowNotEducating,
    },
    data() {
        return {
            is_educating: null,
        };
    },
    computed: {
        toggleEducatingComponent() {
            if (this.is_educating == 1 || this.is_educating == null) {
                return "row-Is-Educating";
            } else {
                return "row-Not-Educating";
            }
        },
    },
    beforeMount() {
        window.activeComponents.push(this);
    },
    mounted() {
        if (this.doPopulate) {
            this.getFieldData(this, "isEducating", "tcamp");
        }
    },
};
</script>
