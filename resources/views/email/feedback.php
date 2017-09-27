<html>
<head>
<link rel="stylesheet" href="../../../public/css/bootstrap.css" />
<style>
body {
	background-color: #fff;
}
.pull-right {
	float: left !important;
}
</style>
</head>
<body>
<div class="container clearfix">
<div style="background-color:#fff; padding:10px;">
  <div style="position: relative;    left: 14.4em; width: 60% !important; border:1px solid #000; background-color:#fff; border-collapse:collapse;">
    <div style="background: #1fb5ad none repeat scroll 0 0; min-height:70px;" align="center"> <img src="../../../public/images/logocrop.png" style="width: 170px; " alt="KranQ" title="KranQ"> 
      <!--img src="<?php //echo URL::to('/'); ?>/images/logocrop.png" style="width: 170px; margin-top: -15px;padding: 0.9em 22em;" alt="KranQ" title="KranQ"--> 
    </div>
    <section class="panel">
      <header class="panel-heading">
        <div style=" line-height:27px;margin:0px 10px; ">
          <?php //echo $data['title']; ?>
          <strong>Test title</strong></div>
        <div class="tools pull-right">
          <div class="form-group btn-toolbar" style="line-height: 28px;margin: 0px 10px;">
            <?php //echo $data['email']; ?>
            joanbritto18@gmail.com</div>
        </div>
      </header>
      <div class="panel-body">
        <div id="graph-area" class="main-chart" style="line-height: 28px;margin: 3px 10px;">
          <?php //echo $data['feedbackMessage']; ?>
          Test</div>
      </div>
    </section>
  </div>
</div>
</div>
<img src="<?php echo URL::to('/'); ?>/images/logocrop.png" style="width: 170px; margin-top: -15px;padding: 0.9em 22em;" alt="KranQ" title="KranQ">
</body>
</html>