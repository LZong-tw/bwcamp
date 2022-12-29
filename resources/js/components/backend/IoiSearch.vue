<template>
    <form method="post">
        <input type="hidden" name="_token" :value="csrf_token">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th v-for="(item, key) in columns"
                        :key="key"
                        @click="toggleData(key)"
                        v-show="key != 'contactlog' && item.for_query"
                        class="alert-primary">
                        {{ item.name }}
                    </th>
                </tr>
            </thead>
            <tr class="border-0">
                <td v-for="(item, key) in columns"
                    v-show="key != 'contactlog' && item.for_query"
                    :key="key"
                    :id="key"
                    class="border-0">
                </td>
            </tr>
            <tr class="border-0">
                <td v-for="(item, key) in columns"
                    class="border-0 d-none" :id="'searchField' + key">
                    <input type="text" name="" :placeholder="'搜尋' + item.name" class="form-control"
                           :id="'search' + key" :value="search[key]" @keyup="filterSearch(key)">
                </td>
            </tr>
        </table>
        <div class="input-group mt-2">
            <button type="submit" class="btn btn-success ml-3" @click="submitForm">送出</button>
        </div>
    </form>
</template>

<script>
export default {
    name: "IoiSearch",
    data() {
        return {
            search: [],
            columns: window.columns,
            theData: window.theData,
            originalData: window.theData,
            csrf_token: window.csrf_token,
            orFields: [],
            andFields: [],
        };
    },
    methods: {
        filterSearch(column) {
            let search = $(`#search${column}`).val();
            console.log(search);
            this.search[column] = search;
            this.theData = this.originalData;
            this.theData = this.theData.filter((item) => {
                return item[column].toLowerCase().includes(search.toLowerCase());
            });
            if (this.theData.length == 0) {
                this.theData = this.originalData;
            }
            this.toggleData(column);
            this.toggleData(column);
            var strLength = $(`#search${column}`).val().length * 2;
            $(`#search${column}`).focus();
            $(`#search${column}`)[0].setSelectionRange(strLength, strLength);
        },
        toggleData(id) {
            this.columns[id].show = !this.columns[id].show;
            this.toggleColumns(id);
            if (this.columns[id].show) {
                let table = document.createElement("table");
                this.theData.forEach((item, key) => {
                    if (table.innerHTML.includes(item[id])) {
                        return;
                    }
                    if (key == 0 && key == "group") {
                        let tr0 = document.createElement("tr");
                        tr0.setAttribute("id", "tr" + id + "key" + key + "NONE");
                        let td0 = document.createElement("td");
                        let checkbox0 = document.createElement("input");
                        checkbox0.setAttribute("onclick", 'window.vueComponent.toggleCheckbox(this)');
                        checkbox0.setAttribute("type", "checkbox");
                        checkbox0.setAttribute("name", id + "[]");
                        checkbox0.setAttribute("value", "NONE");
                        td0.appendChild(checkbox0);
                        td0.innerHTML += "未分組";
                        tr0.appendChild(td0);
                        table.appendChild(tr0);
                        $("#searchField" + id).removeClass("d-none");
                    }
                    if (item[id]) {
                        let tr = document.createElement("tr");
                        tr.setAttribute("id", "tr" + id + "key" + key);
                        let td = document.createElement("td");
                        let checkbox = document.createElement("input");
                        checkbox.setAttribute("onclick", 'window.vueComponent.toggleCheckbox(this)');
                        checkbox.setAttribute("type", "checkbox");
                        checkbox.setAttribute("name", id + "[]");
                        checkbox.setAttribute("value", item[id]);
                        td.appendChild(checkbox);
                        td.innerHTML += item[id];
                        tr.appendChild(td);
                        table.appendChild(tr);
                        $("#searchField" + id).removeClass("d-none");
                    }
                });
                $("#searchField" + id).append(`<div id="show-` + id +`">
                                        ` + table.outerHTML + `
                                    </div>`);
            } else {
                $("#show-" + id).remove();
                $("#searchField" + id).addClass("d-none");
            }
        },
        toggleCheckbox(ele) {
            // let nameSplit = ele.name.split(".");
            // nameSplit.pop();
            // if (ele.checked) {
            //     let val = [];
            //     val[nameSplit] = ele.value;
            //     this.orFields.push(val);
            // } else {
            //     this.orFields = this.orFields.filter((item, key) => {
            //         return item[nameSplit][key] != ele.value;
            //     });
            // }
            // console.table(this.orFields);
        },
        toggleColumns(ele) {
            // let nameSplit = ele.name.split(".");
            // nameSplit.pop();
            // if (ele.checked) {
            //     let val = [];
            //     val[nameSplit] = ele.value;
            //     this.andFields.push(val);
            // } else {
            //     this.andFields = this.andFields.filter((item, key) => {
            //         return item[nameSplit][key] != ele.value;
            //     });
            // }
            // console.table(this.andFields);
        },
        submitForm() {
            let data = {
                orFields: this.orFields,
                // andFields: this.andFields,
            };
            const form = document.createElement('form');
            form.method = "POST";
            form.action = '/learner';
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = "_token";
            csrf.value = window.csrf_token;
            form.appendChild(csrf);
            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = "orFields";
            hiddenField.value = this.orFields;
            form.appendChild(hiddenField);
            document.body.appendChild(form);
            form.submit();
        }
    },
    mounted() {
        // console.table(this.theData);
        // console.table(this.columns);
        window.vueComponent = this;
        for (let key in this.theData[0]) {
            this.search[key] = "";
        }
    },
}
</script>

<style scoped>

</style>
