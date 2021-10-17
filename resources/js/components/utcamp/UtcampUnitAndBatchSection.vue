/* eslint-disable prettier/prettier */
/* eslint-disable prettier/prettier */
<template>
    <span>          
        <div class="row form-group required">
            <label for="inputName" class="col-md-2 control-label text-md-right">服務單位</label>
            <div class="col-md-10">
                <select
                    name="unit"
                    id="unit"
                    class="form-control"
                    v-model="selected"
                    required
                >
                    <option :value="''">- 請選擇 -</option>
                    <option :value="{ is_taichung: 1, name: '123' }">台中</option>
                    <option :value="{ is_taichung: 0, name: '123' }">非台中</option>
                </select>
                <div class="invalid-feedback">請選擇服務單位</div>
            </div>
        </div>
        <component v-bind:is="toggleTaichungComponent"></component>        
    </span>
</template>
<script>
import rowIsTaichung from "./RowIsTaichung.vue";
export default {
    components: {
        rowIsTaichung,
    },
    data() {
        return {
            selected: '',
        };
    },
    computed: {
        toggleTaichungComponent() {
            if (this.selected && this.selected.is_taichung == 1) {
                return "row-Is-Taichung";
            }
            return null;
        },
    },
    mounted() {
        // console.log("Mounted.");
    },
}
</script>
