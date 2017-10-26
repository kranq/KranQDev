<?php
use App\Helpers\KranHelper;
?>
<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <div class="leftside-navigation">
            <ul class="sidebar-menu" id="nav-accordion">
                <!--li>
                    <a class="" href="<?php //echo url('/'); ?>">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li-->
                <li class="sub-menu">
                    <a href="javascript:;" class="<?php echo KranHelper::getActiveMenu(array('category')); ?>">
                        <i class="fa fa-laptop"></i>
                        <span>Category</span>
                    </a>
                    <ul class="sub">
                        <li class="<?php echo KranHelper::getActiveSubMenu('category/create'); ?>"><a href="<?php echo URL::to('category/create'); ?>">Add</a></li>
                        <li class="<?php echo KranHelper::getActiveSubMenu('category'); ?>"><a href="<?php echo URL::to('category'); ?>">List</a></li>
                    </ul>
                </li>

				<li class="sub-menu dcjq-parent-li">
                    <a href="javascript:;" class="<?php echo KranHelper::getActiveMenu(array('provider')); ?>">
                        <i class="fa fa-plus-square"></i>
                        <span>Service Provider</span>
                        <span class="dcjq-icon"></span>
                    </a>
                    <ul class="sub">
                         <li class="<?php echo KranHelper::getActiveSubMenu('provider/create'); ?>"><a href="<?php echo URL::to('provider/create'); ?>">Add</a></li>
                        <li class="<?php echo KranHelper::getActiveSubMenu('provider'); ?>"><a href="<?php echo URL::to('provider'); ?>">List</a></li>
                    </ul>
                </li>

			 	<li class="sub-menu">
                    <a href="javascript:;" class="<?php echo KranHelper::getActiveMenu(array('review')); ?>">
                        <i class="fa fa-comments"></i>
                        <span>Reviews & Ratings</span>
                    </a>
                    <ul class="sub">
                        <li class="<?php echo KranHelper::getActiveSubMenu('review'); ?>"><a href="<?php echo URL::to('review'); ?>">List</a></li>
                    </ul>
                </li>

                <!--li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-bookmark" aria-hidden="true"></i>
                        <span>Bookmarks</span>
                    </a>
                    <ul class="sub">
                        <li><a href="<?php //echo URL::to('bookmark'); ?>">List</a></li>
                    </ul>
                </li-->

				<li class="sub-menu">
                    <a href="javascript:;" class="<?php echo KranHelper::getActiveMenu(array('user')); ?>">
                        <i class="fa fa-users"></i>
                        <span>Users</span>
                    </a>
                    <ul class="sub">
                        <li class="<?php echo KranHelper::getActiveSubMenu('user/create'); ?>"><a href="<?php echo URL::to('user/create'); ?>">Add</a></li>
                        <li class="<?php echo KranHelper::getActiveSubMenu('user'); ?>"><a href="<?php echo URL::to('user'); ?>">List</a></li>
                    </ul>
                </li>

				<li class="sub-menu">
                    <a href="javascript:;" class="<?php echo KranHelper::getActiveMenu(array('city','location','cms','service','address')); ?>">
                        <i class="fa fa-cogs"></i>
                        <span>Defaults</span>
                    </a>
                    <ul class="sub">
                        <li class="<?php echo KranHelper::getActiveSubMenuDefault('city'); ?>"><a href="<?php echo URL::to('city'); ?>">City</a></li>
                        <li class="<?php echo KranHelper::getActiveSubMenuDefault('location'); ?>"><a href="<?php echo URL::to('location/'); ?>">Locality</a></li>
                        <li class="<?php echo KranHelper::getActiveSubMenuDefault('cms'); ?>"><a href="<?php echo URL::to('cms/'); ?>">CMS</a></li>
                        <li class="<?php echo KranHelper::getActiveSubMenuDefault('service'); ?>"><a href="<?php echo URL::to('service/'); ?>">Services</a></li>
                        <li class="<?php echo KranHelper::getActiveSubMenuDefault('address'); ?>"><a href="<?php echo URL::to('address/'); ?>">Address</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- sidebar menu end-->
    </div>
</aside>
