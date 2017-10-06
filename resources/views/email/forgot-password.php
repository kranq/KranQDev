<html>
<head>
<link rel="stylesheet" href="<?php echo URL::to('/'); ?>/css/bootstrap.css" />
</head>
<body>
<div class="container">
  <div style="border:solid 1px #999999; position: relative;    left: 14.4em; width: 60% !important; border:1px solid #000; background-color:#fff; border-collapse:collapse;" align="center">
    <div style="background: #1fb5ad none repeat scroll 0 0; min-height:70px;"> <img  src="<?php echo URL::to('/'); ?>/images/logocrop.png"  style="width: 170px; " alt="KranQ" title="KranQ"></div>
    <section class="panel">
      <header class="panel-heading"><b><span class="tex-move-in" style="margin:0px 10px !important;">Reset Password</span></b> </header>
       <div class="panel-body clearfix">
        <div id="graph-area" class="main-chart" style="margin:0px 10px !important; padding-bottom:1em; text-align: center; margin-top: -1em !important;"> <?php echo $data['content']; ?> </div>
      </div>
    </section>
  </div>
</div>
</body>
</html>