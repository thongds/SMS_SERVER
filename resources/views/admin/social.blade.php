@extends('../layouts.admin')
@section('title','social  data')
@section('content')
    <form>
        <div class="form-group">
            <label for="exampleInputEmail1">video title OR status</label>
            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="post title">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Post image url</label>
            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="post image url">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Post video url</label>
            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="post video url">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">post content </label>
            <textarea type="text" rows="10" class="form-control" id="exampleInputPassword1" placeholder="post content"></textarea>
        </div>
        <select class="form-control">
            <option>facebook</option>
            <option>youtube</option>
        </select>
        <div class="checkbox">
            <label>
                <input type="checkbox"> has video
            </label>
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>

@endsection