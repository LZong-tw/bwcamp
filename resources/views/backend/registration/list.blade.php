@extends('backend.master')
@section('content')
@include('..partials.counties_areas_script')
<style>
    .card-link{
        color: #3F86FB!important;
    }
    .card-link:hover{
        color: #33B2FF!important;
    }
</style>
<h2>查詢及下載</h2>
<form method=post action="" class="form-inline">
    <table class="table table-responsive">
        <tr>
            <td align=left valign=middle nowrap>
                <1> 區域：
                <input class="btn btn-primary" type=submit name=region value='全區'>&nbsp;
                <input class="btn btn-primary" type=submit name=region value='台北'>&nbsp;
                <input class="btn btn-primary" type=submit name=region value='桃園'>&nbsp;
                <input class="btn btn-primary" type=submit name=region value='新竹'>&nbsp;
                <input class="btn btn-primary" type=submit name=region value='台中'>&nbsp;
                <input class="btn btn-primary" type=submit name=region value='嘉義'>&nbsp;
                <input class="btn btn-primary" type=submit name=region value='台南'>&nbsp;
                <input class="btn btn-primary" type=submit name=region value='高雄'>&nbsp;
                <input class="btn btn-primary" type=submit name=region value='其他'>&nbsp;
            </td>
        </tr>
        <tr>
            <td align=left valign=middle nowrap>
                <2> 學程：    
                <input class="btn btn-warning" type=submit name=system value='教育部'>&nbsp;
                <input class="btn btn-warning" type=submit name=system value='教育局/處'>&nbsp;
                <input class="btn btn-warning" type=submit name=system value='大專校院'>&nbsp;
                <input class="btn btn-warning" type=submit name=system value='高中職'>&nbsp;
                <input class="btn btn-warning" type=submit name=system value='國中'>&nbsp;
                <input class="btn btn-warning" type=submit name=system value='國小'>&nbsp;
                <input class="btn btn-warning" type=submit name=system value='幼教'>&nbsp;
            </td>
        </tr>
        <tr>
            <td align=left valign=middle nowrap>
                <3> 縣市：    
                <select class="form-control" name=county size=1 onChange='Address(this.options[this.options.selectedIndex].value);'>
                    <option value=''>- 請選縣市 -</option>
                    <option value='臺北市'>臺北市</option>
                    <option value='新北市'>新北市</option>
                    <option value='臺中市'>臺中市</option>
                    <option value='臺南市'>臺南市</option>
                    <option value='高雄市'>高雄市</option>
                    <option value='基隆市'>基隆市</option>
                    <option value='宜蘭縣'>宜蘭縣</option>
                    <option value='桃園市'>桃園市</option>
                    <option value='新竹市'>新竹市</option>
                    <option value='新竹縣'>新竹縣</option>
                    <option value='苗栗縣'>苗栗縣</option>
                    <option value='南投縣'>南投縣</option>
                    <option value='彰化縣'>彰化縣</option>
                    <option value='雲林縣'>雲林縣</option>
                    <option value='嘉義市'>嘉義市</option>
                    <option value='嘉義縣'>嘉義縣</option>
                    <option value='屏東縣'>屏東縣</option>
                    <option value='澎湖縣'>澎湖縣</option>
                    <option value='臺東縣'>臺東縣</option>
                    <option value='花蓮縣'>花蓮縣</option>
                    <option value='金門縣'>金門縣</option>
                    <option value='連江縣'>連江縣</option>
                    <option value='南海諸島'>南海諸島</option>
                    <option value='其他'>其他</option>
                </select>&nbsp;&nbsp;

                <select class="form-control" id=subarea name=subarea size=1>
                    <option value=''>- 再選區鄉鎮 -</option>
                </select>&nbsp;
                <input type="hidden" name="zipcode">
                <input type="hidden" name="address">
                <input class="btn btn-danger" type="reset" name="reset" value="清除">&nbsp;
                <input class="btn btn-success" type="submit" value="查詢">
            </td>
        </tr>
    </table>
</form>
@endsection