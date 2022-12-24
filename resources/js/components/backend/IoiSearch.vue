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
                    @click="toggleData(key)"
                    class="border-0">
                </td>
            </tr>
        </table>
        <div class="input-group mt-2">
            <input type="search" name="search" id="" class="form-control col-md-3 rounded" placeholder="搜尋">
            <button type="submit" class="btn btn-success ml-3">搜尋</button>
        </div>
    </form>
</template>

<script>
export default {
    name: "IoiSearch",
    data() {
        return {
            search: "",
            columns: window.columns,
            theData: window.theData,
        };
    },
    methods: {
        toggleData(id) {
            this.columns[id].show = !this.columns[id].show;
            if (this.columns[id].show) {
                let table = document.createElement("table");
                let tr = document.createElement("tr");
                let td = document.createElement("td");
                this.theData.forEach((item, key) => {
                    td.innerHTML = item[id] ? item[id] : "";
                    tr.appendChild(td);
                    table.appendChild(tr);
                });
                $("#" + id).append(`<div id="show-` + id +`">
                                        ` + table.innerHTML + `
                                    </div>`);
            } else {
                $("#show-" + id).remove();
            }
        },
    },
    mounted() {
        // console.log(this.columns);
    },
}
</script>

<style scoped>

</style>
