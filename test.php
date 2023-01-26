<?php
require 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>

<!-- jQuery library -->

<!-- Latest compiled JavaScript -->

</head>
<body>
<div class="col-md-4 div-margin justify-content-center text-center">
	<div class="datepicker" width="1000px"></div>
</div>
<style>
    .datepicker,
.table-condensed {
  width: 35rem;
  height:35rem;
  margin: 2rem;
}
</style>
<script>
    $('.datepicker').datepicker({
        autoclose: false,
        clearBtn: true,
    });
</script>
</body>
</html>