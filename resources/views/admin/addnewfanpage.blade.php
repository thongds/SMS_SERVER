@extends('../layouts.admin')
@section('title','new social')
@section('content')
    <?php echo Form::open(array('route'=>'addnewfanpage','method'=>'post','enctype'=>'multipart/form-data')) ?>

    <div class="form-group">
        <label for="exampleInputEmail1">fanpage Name</label>
        <input type="text" class="form-control" name="fanpage_name" id="exampleInputEmail1" placeholder="social name">
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">Fanpage logo url</label>
        <input type="file"  name="fanpage_logo" id="exampleInputEmail1" placeholder="logo url">
    </div>

    <div class="checkbox">
        <label>
            <input type="checkbox" name="active" checked> active
        </label>
    </div>

    <?php if($social_data!=null) :
        ?>
    <label > fanpage belog to social?</label><br><br>
    <select class="form-control" name="social_info_id">
                <?php
                    foreach ($social_data as $social_list){
                        echo '<option value="'.$social_list->id.'">'.$social_list->name."</option>";
                    }
                ?>
    </select>
    <? endif;
    ?>
    <br>
    <button type="submit" class="btn btn-danger">Submit</button>
    <?php echo Form::close() ?>

@endsection