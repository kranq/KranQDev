<html>
<head>
<link rel="stylesheet" href="../../public/css/bootstrap.css" />
<link rel="stylesheet" href="../../public/css/style.css" />
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
    <div style="background: #1fb5ad none repeat scroll 0 0; min-height:70px;" align="center"> 
        <img src="../../public/images/logocrop.png" style="width: 170px; " alt="KranQ" title="KranQ"> 
      <!--img src="<?php //echo URL::to('/'); ?>/images/logocrop.png" style="width: 170px; margin-top: -15px;padding: 0.9em 22em;" alt="KranQ" title="KranQ" --> 
    </div>
    <section class="panel">
      <header class="panel-heading">
       
        <div class="panel-body">
        <div id="graph-area" class="main-chart" style="line-height: 28px;margin: -17px 10px;">
          <label><span class="text-form">Name:</span></label>
         {{ $data['name'] }}

        </div>
        </div>
          <div class="panel-body">
        <div id="graph-area" class="main-chart" style="line-height: 28px;margin: -17px 10px;">
          <label><span class="text-form">Email:</span></label>
          {{ $data['email'] }}
        </div>
      </div>
      
      <div class="panel-body">
        <div id="graph-area" class="main-chart" style="line-height: 28px;margin: -17px 10px;">
          <label><span class="text-form">Subject:</span></label>

          {{ $data['subject'] }}
        </div>
      </div>
        <div class="panel-body">
        <div id="graph-area" class="main-chart" style="line-height: 28px;margin: -17px 10px;">
          <label><span class="text-form">Message:</span></label>
          {{ $data['message'] }}
        </div>
      </div>
      </header>
    </section>
  </div>
</div>
</div>
    
<img src="../../public/images/logocrop.png" style="width: 170px; margin-top: -15px;padding: 0.9em 22em;" alt="KranQ" title="KranQ">
<!--img src="<?php //echo URL::to('/'); ?>/images/logocrop.png" style="width: 170px; margin-top: -15px;padding: 0.9em 22em;" alt="KranQ" title="KranQ"-->
</body>
</html>