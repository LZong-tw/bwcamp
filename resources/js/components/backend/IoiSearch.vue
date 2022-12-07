<template>
    <form action="" method="post">
        <span class="btn btn-primary mr-2">121231233</span>
        <div class="input-group mt-2">
            <span class="btn btn-primary mr-2">1223</span>
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
        };
    },
    mounted() {
        console.log("IoiSearch mounted");
    },
}
</script>

<style scoped>

</style>
