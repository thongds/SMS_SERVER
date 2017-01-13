@extends('../layouts.admin')
@section('title','new social')
@section('content')
    <?php echo Form::open(array('route'=>'addnewsocial','method'=>'post','enctype'=>'multipart/form-data')) ?>

        <div class="form-group">
            <label for="exampleInputEmail1">Social Name</label>
            <input type="text" class="form-control" name="social_name" id="exampleInputEmail1" placeholder="social name">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">color_tag</label>
            <input type="text"  class="form-control"   name="color_tag" id="exampleInputEmail1" placeholder="color tag">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Social logo url</label>
            <input type="file"  name="social_logo" id="exampleInputEmail1" placeholder="logo url">
        </div>

        <div class="form-group">
            <label for="exampleInputPassword1">video tag url </label>
            <input type="file" name="video_tag" id="exampleInputEmail1" placeholder=" video tag url">
        </div>

        <div class="checkbox">
            <label>
                <input type="checkbox" name="active" checked> active
            </label>
        </div>
        <button type="submit" class="btn btn-danger">Submit</button>
   <?php echo Form::close() ?>
    <img src="<?php echo $url?>" alt="Mountain View" style="width:304px;height:228px;">

@endsection