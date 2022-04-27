@extends('layouts.index')

@section('form')

<div>
    <label>Date: </label>
    <input type="text" id="fdate" placeholder="xxxx-xx-xx">
</div>

<div>
    <label>Search: </label>
    <input type="text" id="fteam">
    <button onclick="submit()">搜尋</button>
</div>
@endsection


@section('table')
<div id="detial">
    <table class="table">
        <tbody id="rows">
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script src="js/main.js"></script>
@endpush