<template>
    <form method="post">
        <input type="hidden" name="_token" :value="csrf_token">
        <input type="reset" value="清除篩選條件 - 顯示所有關懷員" class="btn btn-danger mb-3"  onclick="window.location=window.location.href" v-if="isShowLearners && isShowVolunteers">
        <input type="reset" value="清除篩選條件 - 顯示所有義工" class="btn btn-danger mb-3"  onclick="window.location=window.location.href" v-else-if="isShowVolunteers">
        <input type="reset" value="清除篩選條件 - 顯示所有學員" class="btn btn-danger mb-3"  onclick="window.location=window.location.href" v-else>
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
            <button type="submit" class="btn btn-success" @click="submitForm">送出</button>
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
            theRoles: [],
            theOriginalRoles: [],
            theVolunteersData: window.theVolunteersData ? window.theVolunteersData : null,
            originalData: window.theData,
            csrf_token: window.csrf_token,
            orFields: [],
            andFields: [],
            isShowLearners: window.isShowLearners,
            isShowVolunteers: window.isShowVolunteers
        };
    },
    methods: {
        sleep (ms) {
            return new Promise((resolve)=>setTimeout(resolve,ms));
        },
        filterSearch(column) {
            let search = $(`#search${column}`).val();
            let searchRole = null;
            this.search[column] = search;
            // console.log(column)
            this.theData = this.originalData;
            if (column != "roles") {
                this.theData = this.theData.filter((item) => {
                    if (item[column]) {
                        return item[column].toLowerCase().includes(search.toLowerCase());
                    }
                });
            }
            else {
                // https://stackoverflow.com/questions/38375646/filtering-array-of-objects-with-arrays-based-on-nested-value
                // let filteredArray = arrayOfElements
                //     .filter((element) =>
                //         element.subElements.some((subElement) => subElement.surname === 1))
                //     .map(element => {
                //         let newElt = Object.assign({}, element); // copies element
                //         return newElt.subElements.filter(subElement => subElement.surname === '1');
                //     });
                // 根據職務篩選出義工，在對陣列進行降階後，去除重複職務的義工
                this.theData = this.theData.filter((item) => {
                    if (item["user"] && item["user"]["roles"]) {
                        return item["user"]["roles"].some((role) => role["section"].toLowerCase().includes(search.toLowerCase()));
                    }
                }).map(element => {
                    let newElt = Object.assign({}, element);
                    if (newElt.user) {
                        return newElt.user["roles"].filter(subElement => subElement["section"].toLowerCase().includes(search.toLowerCase()));
                    }
                }).flat();
                this.theData = this.theData.filter((obj, index) => {
                    return index === this.theData.findIndex(o => obj.section === o.section);
                });
                searchRole = 1;
            }
            if (this.theData.length == 0) {
                this.theData = this.originalData;
            }
            this.toggleData(column, searchRole);
            this.toggleData(column, searchRole);
            var strLength = $(`#search${column}`).val().length * 2;
            $(`#search${column}`).focus();
            $(`#search${column}`)[0].setSelectionRange(strLength, strLength);
        },
        toggleData(id, searchRole = null) {
            this.columns[id].show = !this.columns[id].show;
            this.toggleColumns(id);
            if (this.columns[id].show) {
                let table = document.createElement("table");
                let unique = [];
                if (!searchRole && (id == "roles" || id == "position")) {
                    // 義工職務
                    let theType = id == "roles" ? "section" : "position";
                    id = "roles";
                    let noGroupSet = false;
                    this.theVolunteersData.forEach((item, key) => {
                        for (let k = 0; k < item[id].length ; k++) {
                            let theEntity = item[id][k];
                            // console.log(item[id][k])
                            if (item[id][k]["batch"] && unique.includes(item[id][k]["batch"]["name"] + ": " + item[id][k]["section"] + "-" + item[id][k]["position"]) && id != 'name') {
                                continue;
                            }
                            else if (unique.includes(item[id][k]["section"] + "-" + item[id][k]["position"]) && id != 'name') {
                                continue;
                            }
                            if (key == 0 && !noGroupSet) {
                                let tr0 = document.createElement("tr");
                                tr0.setAttribute("id", "tr" + id + "key" + key + "NONE");
                                let td0 = document.createElement("td");
                                let checkbox0 = document.createElement("input");
                                checkbox0.setAttribute("type", "checkbox");
                                checkbox0.setAttribute("name", "group_id[]");
                                checkbox0.setAttribute("value", "NONE");
                                td0.appendChild(checkbox0);
                                td0.innerHTML += "未分組";
                                tr0.appendChild(td0);
                                table.appendChild(tr0);
                                $("#searchField" + id).removeClass("d-none");
                                unique.push("未分組");
                                noGroupSet = true;
                            }
                            if (item[id]) {
                                let tr = document.createElement("tr");
                                tr.setAttribute("id", "tr" + id + "key" + key);
                                let td = document.createElement("td");
                                let checkbox = document.createElement("input");
                                checkbox.setAttribute("type", "checkbox");
                                checkbox.setAttribute("name", "sections[]");
                                checkbox.setAttribute("value", theEntity[theType]);
                                td.appendChild(checkbox);
                                if (theEntity["batch"]) {
                                    td.innerHTML += theEntity["batch"]["name"] + ": " + theEntity["section"];
                                    if(unique.includes(theEntity["batch"]["name"] + ": " + theEntity["section"])) {
                                        return;
                                    }
                                    unique.push(theEntity["batch"]["name"] + ": " + theEntity["section"]);
                                }
                                else {
                                    td.innerHTML += theEntity["section"];
                                    if(unique.includes(theEntity["section"])) {
                                        return;
                                    }
                                    unique.push(theEntity["section"]);
                                }
                                tr.appendChild(td);
                                table.appendChild(tr);
                                $("#searchField" + id).removeClass("d-none");
                            }
                        }
                    });
                }
                else {
                    this.theData.forEach((item, key) => {
                        if (unique.includes(item[id]) && id != 'name') {
                            return;
                        }
                        if (key == 0 && (id == "group" || id == "roles")) {
                            let tr0 = document.createElement("tr");
                            tr0.setAttribute("id", "tr" + id + "key" + key + "NONE");
                            let td0 = document.createElement("td");
                            let checkbox0 = document.createElement("input");
                            checkbox0.setAttribute("type", "checkbox");
                            checkbox0.setAttribute("name", "group_id[]");
                            checkbox0.setAttribute("value", "NONE");
                            td0.appendChild(checkbox0);
                            td0.innerHTML += "未分組";
                            tr0.appendChild(td0);
                            table.appendChild(tr0);
                            $("#searchField" + id).removeClass("d-none");
                        }
                        if (id == "roles" && item["section"]) {
                            // 職務的顯示要特別處理：只顯示職務組別，不顯示職務名稱
                            let tr = document.createElement("tr");
                            tr.setAttribute("id", "tr" + "sections" + "key" + key);
                            let td = document.createElement("td");
                            let checkbox = document.createElement("input");
                            checkbox.setAttribute("type", "checkbox");
                            checkbox.setAttribute("name", "sections[]");
                            checkbox.setAttribute("value", item["section"]);
                            td.appendChild(checkbox);
                            td.innerHTML += item["section"];
                            tr.appendChild(td);
                            table.appendChild(tr);
                            $("#searchField" + id).removeClass("d-none");
                            unique.push(item["section"]);
                        }
                        else if (item[id]) {
                            let tr = document.createElement("tr");
                            tr.setAttribute("id", "tr" + id + "key" + key);
                            let td = document.createElement("td");
                            let checkbox = document.createElement("input");
                            checkbox.setAttribute("type", "checkbox");
                            if (id == "group") {
                                checkbox.setAttribute("name", "group_id[]");
                                checkbox.setAttribute("value", item["group_id"]);
                            } else {
                                checkbox.setAttribute("name", id + "[]");
                                checkbox.setAttribute("value", item[id]);
                            }
                            td.appendChild(checkbox);
                            td.innerHTML += item[id];
                            tr.appendChild(td);
                            table.appendChild(tr);
                            $("#searchField" + id).removeClass("d-none");
                            unique.push(item[id]);
                        }
                    });
                }
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
            form.action = window.isShowVolunteers ? '/volunteer' : '/learner';
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
        // console.table(this.theVolunteersData);
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
