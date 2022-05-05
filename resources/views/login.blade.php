@extends('layouts/login')

@section('title')
    <div class="row justify-content-center" style="text-align: center;">
        <div>
            <h1>早安 咖啡 呼啦</h1>
        </div>
    </div>
@endsection


@section('form')
    {{-- <form> --}}
    <div class="form-group row justify-content-center ">
        <label for="inputAccount" class="col-2 col-form-label">Account</label>
        <div class="col-6">
            <input type="text" class="form-control" id="inputAccount" placeholder="Account">
        </div>
    </div>
    <div class="form-group row justify-content-center">
        <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
        <div class="col-sm-6">
            <input type="password" class="form-control" id="inputPassword" placeholder="Password">
        </div>
    </div>
    <div class="form-group row justify-content-center">
        <label for="inputMail" class="col-sm-2 col-form-label">Mail</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" id="inputMail" placeholder="E-mail">
        </div>
    </div>


    <div class="form-group row" style="margin-top: 15px">
        <div class="col-1 offset-8">
            <button onclick="regist()" class="btn btn-primary">Regist</button>
        </div>
        {{-- <div class="col-sm-6"> --}}
        <div class="col-2 ">
            <button onclick="login()" class="btn btn-primary">Login</button>
        </div>
    </div>
    {{-- </form> --}}
@endsection


@push('scripts')
    <script src="{{ asset('js/login.js') }}"></script>
@endpush
