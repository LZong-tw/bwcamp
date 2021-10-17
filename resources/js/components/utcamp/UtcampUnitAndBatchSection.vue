/* eslint-disable prettier/prettier */
/* eslint-disable prettier/prettier */
<template>
    <span>          
        <div class="row form-group required">
            <label for="inputName" class="col-md-2 control-label text-md-right">服務單位</label>
            <div class="col-md-10">
                <select name="unit" id="" required class="form-control" v-model="selected">
                    <option :value="{ is_taichung: null }">- 請選擇 -</option>
                    <option :value="{ is_taichung: 1, name: '123' }">台中</option>
                    <option :value="{ is_taichung: 0, name: '123' }">非台中</option>
                </select>
            </div>
            <div class="invalid-feedback">請選擇服務單位</div>
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
            selected: { is_taichung: null },
        };
    },
    computed: {
        toggleTaichungComponent() {
            if (this.selected.is_taichung == 1) {
                return "row-Is-Taichung";
            } else {
                return null;
            }
        },
    },
    mounted() {
        // console.log("Mounted.");
    },
}
</script>
