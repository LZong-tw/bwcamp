<div class="jumbotron mt-3 p-4">
    <!-- Happiness is not something readymade. It comes from your own actions. - Dalai Lama -->
    <form action="" method="post">
            @foreach ($columns as $key => $column)
                {{-- @if(($loop->iteration) % 3 == 1) <div class="input-group"> @endif --}}
                <span class="btn btn-primary mr-2">{{ $column['name'] }}</span>
                {{-- @if(($loop->iteration) % 3 == 0 || $loop->last) </div> @endif --}}
            @endforeach
        <div class="input-group mt-2">
            <input type="search" name="search" id="" class="form-control col-md-3 rounded" placeholder="搜尋">
            <button type="submit" class="btn btn-success ml-3">搜尋</button>
        </div>
    </form>
</div>
