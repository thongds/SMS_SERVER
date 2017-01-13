<html>
<body>
<h1>Hello, <?php
        foreach ($users as $user){
            echo $user->name;
        }
     ?></h1>
</body>
</html>