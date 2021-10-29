/* eslint-disable prettier/prettier */
/* eslint-disable prettier/prettier */
<template>
    <span>          
        <div class='row form-group required'>
            <label for='inputFuzhi' class='col-md-2 control-label text-md-right'>是否參加過福智的活動</label>
            <div class='col-md-10'>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" name="is_blisswisdom" value='1' v-model="is_blisswisdom" required> 是
                        <div class="invalid-feedback">
                            請選擇項目
                        </div>
                    </label> 
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" name="is_blisswisdom" value='0' v-model="is_blisswisdom" required> 否
                        <div class="invalid-feedback">
                            &nbsp;
                        </div>
                    </label> 
                </div>  
                <div class='row form-group required mt-2' v-if="is_blisswisdom == 1">
                    <label class="control-label col-md-auto text-md-left">活動名稱</label>
                    <div class='col-md'>
                        <input type=text class='form-control' name="blisswisdom_type" value='' minlength="3" required>
                        <div class="invalid-feedback">
                            請輸入參加過的活動
                        </div>
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
            is_blisswisdom: '',
        };
    },
    mounted() {},
}
</script>
