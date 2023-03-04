<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="shortcut icon" href="/vendor/laratrust/img/logo.png">
  <title>職務及權限管理界面 - @yield('title')</title>
  <link href="{{ mix('laratrust.css', 'vendor/laratrust') }}" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
</head>
<body>
<div>
  <nav class="bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-center h-16">
        <div class="flex items-center">
          <div class="hidden md:block">
            <div class="flex items-baseline">
{{--              <a href="{{config('laratrust.panel.go_back_route')}}" class="nav-button">← Go Back</a>--}}
              <a
                href="{{ route('laratrustCustom.roles-assignment.index', compact("camp_id")) }}"
                class="ml-4 {{ request()->is('*roles-assigment*') ? 'nav-button-active' : 'nav-button' }}"
              >
                職務及權限總覽
              </a>
              <a
                href="{{route('laratrustCustom.roles.index', compact("camp_id"))}}"
                class="ml-4 {{ request()->is('*roles') ? 'nav-button-active' : 'nav-button' }}"
              >
                職務清單
              </a>
              <a
                href="{{ route('laratrustCustom.permissions.index', compact("camp_id")) }}"
                class="ml-4 {{ request()->is('*permissions*') ? 'nav-button-active' : 'nav-button' }}"
              >
                權限清單
              </a>
{{--              <img src="{{ asset('vendor/laratrust/img/logo.png') }}" alt="" style="w-25">--}}
              <span class="pl-3 ml-3 text-white">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;職務及權限管理界面</span>
            </div>
          </div>
        </div>
        <div class="-mr-2 flex md:hidden">
          <!-- Mobile menu button -->
          <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white">
            <!-- Menu open: "hidden", Menu closed: "block" -->
            <svg class="block h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <!-- Menu open: "block", Menu closed: "hidden" -->
            <svg class="hidden h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!--
      Mobile menu, toggle classes based on menu state.

      Open: "block", closed: "hidden"
    -->
    <div class="hidden md:hidden">
      <div class="px-2 pt-2 pb-3 sm:px-3">
        <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-white bg-gray-900 focus:outline-none focus:text-white focus:bg-gray-700">Dashboard</a>
        <a href="#" class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700">Team</a>
        <a href="#" class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700">Projects</a>
        <a href="#" class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700">Calendar</a>
        <a href="#" class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700">Reports</a>
      </div>
    </div>
  </nav>

  <header class="bg-white shadow">
    <div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold leading-tight text-gray-900">
        @yield('title')
      </h1>
    </div>
  </header>
  <main>
    <div class="@if(!isset($type) || isset($type) && $type != 'role') max-w-6xl mx-auto py-6 sm:px-6 lg:px-8 @else mx-5 @endif">
      @foreach (['error', 'warning', 'success'] as $msg)
        @if(Session::has($msg))
            <div class="alert-{{ $msg }}" role="alert">
              <p>{{ Session::get($msg) }}</p>
            </div>
        @endif
      @endforeach
        @if(Session::has('errors'))
            @foreach (Session::get('errors')->messages() as $msg)
                <div class="alert-error" role="alert">
                    <p>{{ $msg[0] }}</p>
                </div>
            @endforeach
        @endif
      <div class="px-4 py-6 sm:px-0">
        @yield('content')
      </div>
    </div>
  </main>
</div>
</body>
</html>
