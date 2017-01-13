@extends('../layouts.admin')
@section('title','social setting')
@section('content')
    <br> <a href="{{url('/admin/addnewsocial')}}"> <button type="button" class="btn btn-success">Add new social</button></a>
   <a href="{{url('/admin/addnewfanpage')}}"> <button type="button" class="btn btn-danger">Add new fanpage</button></a>
    <br><br>
    <table class="table table table-striped">
        <h2 style="color: #CC3300"><u>Social</u> </h2>
        <thead>
        <tr>
            <th>Fanpage Name</th>
            <th>Logo </th>
            <th>video tag</th>
            <th>action</th>
        </tr>
        </thead>
        <tbody>
        <?php if($social_data !=null):?>
        <tr>
            <?php
                foreach ($social_data as $data){
                    $delete_url =url()->current().'?type=social&delete=true&id='.$data->id;
                    $edit_url = url('/').'/admin/addnewsocial?id='.$data->id;
                    if($data->status == 0){
                        $class = "danger";
                        $active_url =url()->current().'?type=social&active=1&id='.$data->id;
                        $status_button = '&nbsp;<a href='.$active_url.' <button type="button" class="btn btn-success">active</button></a>';
                    }else{
                        $class = "";
                        $active_url =url()->current().'?type=social&active=0&id='.$data->id;
                        $status_button = '&nbsp;<a href='.$active_url.' <button type="button" class="btn btn-warning">deactive</button></a>';
                    }

                    echo'<tr class ="'.$class.'">';
                    echo '<td>'.$data->name.'</td>';
                    echo '<td> <img src="'.$data->logo.'"style="width:150px;height:100px;></td>';
                    echo '<td><img src="'.$data->video_tag.'"></td>';
                    echo '<td><a href='.$edit_url.'><button type="button" class="btn btn-success">edit</button></a>
                        <a href='.$delete_url.'><button type="button" class="btn btn-danger">delete</button></a>
                        '.$status_button.'
                    </td>';
                    echo '</tr>';
                }
            ?>
        </tr>
        <?php endif;?>
        </tbody>
    </table>
    {{$social_data->links()}}
    <br><br>
    <table class="table table table-striped">
        <h2 style="color: #CC3300"> <u>Fanpage</u></h2>
        <thead>
        <tr>
            <th>fanpage name</th>
            <th>logo</th>
            <th>action</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($fanpage_data !=null):?>

            <?php
                foreach ($fanpage_data as $data){
                    $class = '';
                    $delete_url =url()->current().'?type=fanpage&delete=true&id='.$data->id;
                    $edit_url = url('/').'/admin/addnewsocial?id='.$data->id;
                    if($data->status == 0){
                        $class = "danger";
                        $active_url =url()->current().'?type=fanpage&active=1&id='.$data->id;
                        $status_button = '&nbsp;<a href='.$active_url.' <button type="button" class="btn btn-success">active</button></a>';
                    }else{
                        $class = "";
                        $active_url =url()->current().'?type=fanpage&active=0&id='.$data->id;
                        $status_button = '&nbsp;<a href='.$active_url.' <button type="button" class="btn btn-warning">deactive</button></a>';
                    }
                    echo'<tr class ="'.$class.'">';
                    echo '<td>'.$data->name.'</td>';
                    echo '<td><img src="'.$data->logo.'" style="width:100px;height:150px;"></td>';
                    echo '<td><a href='.$edit_url.'><button type="button" class="btn btn-success">edit</button></a>
                        <a href='.$delete_url.'><button type="button" class="btn btn-danger">delete</button></a>
                        '.$status_button.'
                    </td>';
                    echo '</tr>';
                }
            ?>
        <? endif?>
        </tbody>
    </table>
    {{$fanpage_data->links()}}



@endsection