<!DOCTYPE html>
<html id="puddashboard">

<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Admin Dashboard</title>
    <?php $site_url  = get_site_url();?>

    <script src="https://embed.ravenhub.io/js/app.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/contentmanager.css?v=2.59">

    <link rel="stylesheet" href="/wp-content/plugins/EGPL/cmtemplate/css/lib/lobipanel/lobipanel.min.css?v=2.22">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/cmtemplate/css/lib/jqueryui/jquery-ui.min.css?v=2.21">

    <link rel="stylesheet" href="/wp-content/plugins/EGPL/cmtemplate/css/lib/font-awesome/font-awesome.min.css?v=2.21">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="/wp-content/plugins/EGPL/cmtemplate/css/main.css?v=2.21">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/cmtemplate/css/customstyle.css?v=2.21">
    <link rel="stylesheet"
        href="/wp-content/plugins/EGPL/cmtemplate/css/lib/bootstrap-sweetalert/sweetalert.css?v=2.21" />
    <link rel="stylesheet"
        href="/wp-content/plugins/EGPL/cmtemplate/css/lib/clockpicker/bootstrap-clockpicker.min.css?v=2.21">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/cmtemplate/css/lib/lobipanel/lobipanel.min.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <!--    <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/bootstrap.min.css">-->

    <!--    contetnmanager css-->

    <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/watable.css?v=2.21">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/datepicker.css?v=2.21">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/bootstrap-multiselect.css?v=2.21">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/jquery-confirm.css?v=2.22">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/dataTables.tableTools.css?v=2.21">
    <link rel="stylesheet" type="text/css" href="/wp-content/plugins/EGPL/css/buttons.dataTables.min.css?v=2.21">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/css/admin-component.css?v=2.22">
    <link rel="stylesheet" href="/wp-content/plugins/EGPL/cmtemplate/css/lib/datatables-net/datatables.min.css?v=2.21">
    <?php  $virtualpluginstatus = get_option('Activated_VirtualEGPL');?>
    <?php if( $virtualpluginstatus == 'VirtualEGPL/virtualegpl.php' ) {
     
     
            include_once( WP_PLUGIN_DIR . '/VirtualEGPL/templates/cm_header_vu.php' );
     
     
        }
        
        $oldvalues = get_option( 'ContenteManager_Settings' );
        $aptycode = stripslashes($oldvalues['ContentManager']['aptycode']);
        if($aptycode == "remove"){
            
            $aptycode="";
        }
        ?>


    <script>
    <?php echo $aptycode;?>
    currentsiteurl = '<?php echo $site_url;?>';
    </script>