@extends('../layouts.admin')
@section('title','home')
@section('content')
    <?php
    echo url()->full()." : ".url()->current().":".url()->previous();
    ?>
@endsection