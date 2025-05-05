<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>EcoFarm - Nông sản sạch, giao tận tay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    
    <!-- App favicon -->

    <!-- Plugins css -->
    <link href="../../assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/libs/ladda/ladda-themeless.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/libs/footable/footable.core.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../../assets/libs/YearPicker-master/docs/yearpicker.css">

    <!-- App css -->
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/css/app.min.css" rel="stylesheet" type="text/css" />

    <!-- Load Sweet Alert Javascript -->
    <script src="../../assets/js/swal.js"></script>

    <!-- Inject SWAL Alerts -->
    <?php if (isset($success)) { ?>
    <script>
        setTimeout(function () { 
            swal("Success", "<?php echo $success; ?>", "success");
        }, 100);
    </script>
    <?php } ?>

    <?php if (isset($err)) { ?>
    <script>
        setTimeout(function () { 
            swal("Failed", "<?php echo $err; ?>", "error");
        }, 100);
    </script>
    <?php } ?>
</head>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>EcoFarm - Nông sản sạch, giao tận tay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    
    <!-- App favicon -->

    <!-- Plugins css -->
    <link href="../../assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/libs/ladda/ladda-themeless.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/libs/footable/footable.core.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../../assets/libs/YearPicker-master/docs/yearpicker.css">

    <!-- App css -->
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/css/app.min.css" rel="stylesheet" type="text/css" />

    <!-- Load Sweet Alert Javascript -->
    <script src="../../assets/js/swal.js"></script>

    <!-- Inject SWAL Alerts -->
    <?php if (isset($success)) { ?>
    <script>
        setTimeout(function () { 
            swal("Success", "<?php echo $success; ?>", "success");
        }, 100);
    </script>
    <?php } ?>

    <?php if (isset($err)) { ?>
    <script>
        setTimeout(function () { 
            swal("Failed", "<?php echo $err; ?>", "error");
        }, 100);
    </script>
    <?php } ?>
</head>
<body>



