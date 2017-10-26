@php $currentPage = Request::segment(1); @endphp
<div class="border-bot">
	<div class="main zerogrid">
		<h1><a href="{{ URL::to('/') }}/site/index">KranQ</a></h1>
		<nav>
			<ul class="menu">
				<li><a class="<?php if($currentPage =='Site'){echo 'active';}?>" href="{{ URL::to('/') }}/site/index" title="Home">Home</a></li>
				<li><a href="{{ URL::to('services') }}" class="<?php if($currentPage =='services'){echo 'active';}?>" title="Services">Services</a></li>
				<li><a href="{{ URL::to('contact') }}" class="<?php if($currentPage =='contact'){echo 'active';}?>" title="Contact Us">Contact Us</a></li>
			</ul>
		</nav>
		<div class="clear"></div>
	</div>
</div>
