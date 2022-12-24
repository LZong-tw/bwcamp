<template>
    <form action="" method="post">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th v-for="(item, key) in columns"
                        :key="key"
                        @click="toggleData(key)"
                        class="alert-primary">
                        {{ item.name }}
                    </th>
                </tr>
            </thead>
            <tr class="border-0">
                <td v-for="(item, key) in columns"
                    :key="key"
                    :id="key"
                    class="border-0">
                </td>
            </tr>
        </table>
        <div class="input-group mt-2">
            <button type="submit" class="btn btn-success ml-3">送出</button>
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
        };
    },
    methods: {
        filterSearch(column) {
            let search = $(`#search${column.id}`).val();
            this.search[column.id] = search;
            this.theData = this.originalData;
            this.theData = this.theData.filter((item) => {
                return item[column.id].toLowerCase().includes(search.toLowerCase());
            });
            if (this.theData.length == 0) {
                this.theData = this.theData = this.originalData;
            }
            this.toggleData(column.id);
            this.toggleData(column.id);
            $(`#search${column.id}`).focus();
        },
        toggleData(id) {
            this.columns[id].show = !this.columns[id].show;
            if (this.columns[id].show) {
                let table = document.createElement("table");
                this.theData.forEach((item, key) => {
                    if (table.innerHTML.includes(item[id])) {
                        return;
                    }
                    if (item[id]) {
                        if (key == 0) {
                            let tr_1 = document.createElement("tr");
                            let td_1 = document.createElement("td");
                            let text = document.createElement("input");
                            text.setAttribute("type", "text");
                            text.setAttribute("placeholder", "搜尋");
                            text.setAttribute("class", "form-control");
                            text.setAttribute("id", "search" + id);
                            text.setAttribute("oninput", "window.vueComponent.filterSearch(" + id + ")");
                            text.setAttribute("value", window.vueComponent.$data.search[id]);
                            td_1.appendChild(text);
                            tr_1.appendChild(td_1);
                            table.appendChild(tr_1);
                        }
                        let tr = document.createElement("tr");
                        tr.setAttribute("id", "tr" + id + "key" + key);
                        let td = document.createElement("td");
                        let checkbox = document.createElement("input");
                        checkbox.type = "checkbox";
                        checkbox.name = "value[" + id +"][" + key + "]";
                        td.appendChild(checkbox);
                        td.innerHTML += item[id];
                        tr.appendChild(td);
                        table.appendChild(tr);
                    }
                });
                $("#" + id).append(`<div id="show-` + id +`">
                                        ` + table.outerHTML + `
                                    </div>`);
            } else {
                $("#show-" + id).remove();
            }
        },
    },
    mounted() {
        window.vueComponent = this;
        for (let key in this.theData[0]) {
            this.search[key] = "";
        }
    },
}
</script>

<style scoped>

</style>
