@extends('checkIn.master')
@section('content')
<style>
    .text-center {
        text-align: center;
    }

    #CenterDIV {
        position: fixed;
        top: 0;
        left: 0;
        background-color: rgba(255, 255, 255, 0.75);
        width: 100%;
        height: 100%;
        padding-top: 200px;
        display: none;
    }

    .divFloat {
        margin: 0 auto;
        background-color: #FFF;
        color: #000;
        width: 90%;
        height: auto;
        padding: 20px;
        border: solid 1px #999;
        -webkit-border-radius: 3px;
        -webkit-box-orient: vertical;
        -webkit-transition: 200ms -webkit-transform;
        box-shadow: 0 4px 23px 5px rgba(0, 0, 0, 0.2), 0 2px 6px rgba(0, 0, 0, 0.15);
        display: block;
    }

    .footer {
        background-color: rgba(221, 221, 221, 0.80);
    }
</style>
<div class="container">
    <h2 class="mt-4 text-center">福智營隊報到系統</h2>
    <h4 class="mt-2 text-center">選擇欲執行報到營隊</h4>
    @forelse($camps as $camp)
        <div class="row">
            <a href="/checkin?camp_id={{ $camp->id }}&t={{ time() }}" class="mx-auto mt-3">{{ $camp->fullName }}</a>
        </div>
    @empty
        無營隊
    @endforelse
</div>
@endsection
