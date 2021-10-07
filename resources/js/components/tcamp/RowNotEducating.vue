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
                />
                <div class="invalid-feedback crumb">請填寫職稱</div>
            </span>
        </div>
    </div>
</template>
