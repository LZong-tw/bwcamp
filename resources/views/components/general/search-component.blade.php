<div class="jumbotron mt-3 p-4" id="ioi-search">
    <!-- Happiness is not something readymade. It comes from your own actions. - Dalai Lama -->
    @if(!$camp->table == "ceocamp")
        <ioi-search></ioi-search>
    @else
        <h5>套用篩選條件</h5>
        {{--<h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>--}}
        <div>組別：
            <span>
                <input type="checkbox" name="group[]" value="na">未分組
                @foreach($groups as $group)
                    <input type="checkbox" name="group[]" value="{{ $group->id }}">{{ $group->alias }}
                @endforeach
            </span>
        </div>
        <div>性別：
            <span>
                <input type="checkbox" name="gender[]" value="M"> 男
                <input type="checkbox" name="gender[]" value="F"> 女
            </span>
        </div>
        <div>年齡：
            <span>
                <input type="checkbox" name="age[]" value="age <= 30"> <= 30
                <input type="checkbox" name="age[]" value="age >= 31 and age <= 40"> 31 ~ 40
                <input type="checkbox" name="age[]" value="age >= 41 and age <= 50"> 41 ~ 50
                <input type="checkbox" name="age[]" value="age >= 51 and age <= 60"> 51 ~ 60
                <input type="checkbox" name="age[]" value="age >= 61 and age <= 70"> 61 ~ 70
                <input type="checkbox" name="age[]" value="age >= 71"> >= 71
            </span>
        </div>
        <div>產業別：
        </div>
        <div>參加形式：
            <input type="checkbox" name="participation_mode[]" value="實體">實體
            <input type="checkbox" name="participation_mode[]" value="線上">線上
            <input type="checkbox" name="participation_mode[]" value="皆可">皆可
        </div>
        <div>學員姓名：
            <form action="" method="post">
                @csrf
                <div class="d-flex">
                    <input type="text" class="form-control col-4">
                    <input type="submit" value="搜尋" class="ml-3 btn btn-primary">
                </div>
            </form>
        </div>
        <div>推薦人姓名：
            <form action="" method="post">
                @csrf
                <div class="d-flex">
                    <input type="text" class="form-control col-4">
                    <input type="submit" value="搜尋" class="ml-3 btn btn-primary">
                </div>
            </form>
        </div>
        <input type="reset" value="清除篩選條件 - 顥示所有學員" class="btn btn-danger" style="position: relative; float: right; top: -30px;">
    @endif
</div>

