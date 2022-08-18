@extends('brackets/admin-ui::admin.layout.default')
<style>
    .message_bill{
        text-align: center;
        margin-top: 20%;
    }
</style>
@section('body')
<h2 class="message_bill">{{@$msg}}</h2>
@endsection
