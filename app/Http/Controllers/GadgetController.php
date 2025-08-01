<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GadgetController extends Controller
{
    public function fortuneStick(Request $request)
    {
        $action = 'showTossBlocks';
        $result = 100;
        return view('other'.'.fortuneStick',compact('action','result'));
    }
    public function tossBlocks(Request $request)
    {
        $result = rand(0,3);    //0:HH, 1:HT, 2:TH, 3:TT
        if ($result == 1 || $result == 2) {   $action = 'showDrawStick';}
        else {  $action = 'showTossBlocks';}
        return view('other'.'.fortuneStick',compact('action','result'));
    }
    public function drawStick(Request $request)
    {
        $action = 'showStickContent';
        $result = rand(1,51);    //1:HH, 2:TT, 3:HT
        $fn1 = sprintf('/img/fortune/fortunetarot%03d.jpg',$result*2-1);
        $fn2 = sprintf('/img/fortune/fortunetarot%03d.jpg',$result*2);
        $fp1 = url($fn1);
        $fp2 = url($fn2);
        
        return view('other'. '.fortuneStick',compact('action','result','fp1','fp2'));
    }
    public function showStick(Request $request)
    {
        $action = 'showTossBlocks';
        $result = 100;
        return view('other'.'.fortuneStick',compact('action','result'));
    }

}
